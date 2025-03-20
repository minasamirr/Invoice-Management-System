<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceUpdated;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $invoices = Invoice::paginate($perPage);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        // Assuming you have customers to select from in the create form.
        $customers = Customer::all();
        return view('invoices.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount'      => 'required|numeric|min:0',
            'tax'         => 'nullable|numeric|min:0',
            'due_date'    => 'required|date',
            'description' => 'nullable|string',
            'status'      => 'required|in:' . implode(',', Invoice::statuses()),
        ]);

        $invoice = Invoice::create($validated);

        // Log the creation action
        InvoiceLog::create([
            'invoice_id' => $invoice->id,
            'user_id'    => auth()->id(),
            'action'     => 'create',
            'role'       => auth()->user()->role,
            'details'    => 'Invoice created successfully.',
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        if ($invoice->trashed()) {
            abort(404);
        }
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        // Prevent editing if the invoice is soft-deleted.
        if ($invoice->trashed()) {
            abort(404);
        }
        // You may want to pass customers list if changing the customer is allowed.
        $customers = Customer::all();
        return view('invoices.edit', compact('invoice', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->trashed()) {
            abort(404);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount'      => 'required|numeric|min:0',
            'tax'         => 'nullable|numeric|min:0',
            'due_date'    => 'required|date',
            'description' => 'nullable|string',
            'status'      => 'required|in:' . implode(',', Invoice::statuses()),
        ]);

        $oldValues = $invoice->getOriginal();

        $invoice->update($validated);

        $newValues = $invoice->getChanges();

        $changes = [];
        foreach ($newValues as $field => $newVal) {
            if ($field === 'updated_at') {
                continue;
            }
            $oldVal = $oldValues[$field] ?? '';
            $changes[$field] = [
                'old' => $oldVal,
                'new' => $newVal,
            ];
        }

        // Log the update action
        InvoiceLog::create([
            'invoice_id' => $invoice->id,
            'user_id'    => auth()->id(),
            'action'     => 'update',
            'role'       => auth()->user()->role,
            'details'    => 'Invoice updated successfully.',
        ]);

        if ($invoice->customer && $invoice->customer->email) {
            try {
                Mail::to($invoice->customer->email)->send(new InvoiceUpdated($invoice, $changes));
            } catch (\Exception $e) {
                Log::error("Fail to send the email for invoice #{$invoice->invoice_number}: ". $e->getMessage());
            }
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $invoice->delete();

        // Log the delete action
        InvoiceLog::create([
            'invoice_id' => $invoice->id,
            'user_id'    => auth()->id(),
            'action'     => 'delete',
            'role'       => auth()->user()->role,
            'details'    => 'Invoice soft deleted successfully.',
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = Invoice::query();

        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'LIKE', '%' . $request->invoice_number . '%');
        }

        if ($request->filled('customer_name')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->customer_name . '%');
            });
        }

        if ($request->filled('invoice_date_from')) {
            $query->whereDate('due_date', '>=', $request->invoice_date_from);
        }

        if ($request->filled('invoice_date_to')) {
            $query->whereDate('due_date', '<=', $request->invoice_date_to);
        }

        if ($request->filled('invoice_amount_from')) {
            $query->where('amount', '>=', $request->invoice_amount_from);
        }

        if ($request->filled('invoice_amount_to')) {
            $query->where('amount', '<=', $request->invoice_amount_to);
        }

        if ($request->filled('payment_status')) {
            $query->where('status', $request->payment_status);
        }

        $query->orderBy('due_date', 'desc');
        $perPage = $request->input('per_page', 10);
        $invoices = $query->paginate($perPage);

        $statuses = Invoice::statuses();

        return view('invoices.search', compact('invoices', 'statuses'));
    }
}
