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
        $this->authorize(('manageInvoices'));

        $customers = Customer::all();
        $statuses = Invoice::statuses();
        $currencies = Invoice::currencies();
        return view('invoices.create', compact('customers', 'statuses', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manageInvoices');
        try {
            $validated = $request->validate(Invoice::rules());

            $invoice = Invoice::create($validated);

            InvoiceLog::create([
                'invoice_id' => $invoice->id,
                'user_id'    => auth()->id(),
                'action'     => 'create',
                'role'       => auth()->user()->role,
                'details'    => 'Invoice created successfully.',
            ]);

            return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            Log::error('Invoice store error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create invoice. Please try again later.']);
        }
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

        $customers = Customer::all();
        $statuses = Invoice::statuses();
        $currencies = Invoice::currencies();
        return view('invoices.edit', compact('invoice', 'customers', 'statuses', 'currencies'));
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

        $this->authorize('update', $invoice);
        try {
            $validated = $request->validate(Invoice::rules());

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

            InvoiceLog::create([
                'invoice_id' => $invoice->id,
                'user_id'    => auth()->id(),
                'action'     => 'update',
                'role'       => auth()->user()->role,
                'details'    => 'Invoice updated successfully.',
            ]);

            if ($invoice->customer && $invoice->customer->email) {
                try {
                    $title = 'Invoice #' . $invoice->invoice_number . ' Updated';
                    $subject = 'An update has been made to your invoice #' . $invoice->invoice_number;
                    Mail::to($invoice->customer->email)->send(new InvoiceUpdated($invoice, $changes, $subject, $title));
                } catch (\Exception $e) {
                    Log::error("Fail to send the email for invoice #{$invoice->invoice_number}: ". $e->getMessage());
                }
            }

            return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            Log::error('Invoice update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update invoice. Please try again later.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $this->authorize('manageInvoices');
        try {
            $invoice->delete();

            InvoiceLog::create([
                'invoice_id' => $invoice->id,
                'user_id'    => auth()->id(),
                'action'     => 'delete',
                'role'       => auth()->user()->role,
                'details'    => 'Invoice soft deleted successfully.',
            ]);

            return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Invoice deletion error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete invoice. Please try again later.']);
        }
    }

    public function search(Request $request)
    {
        try {
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

            if ($request->filled('currency')) {
                $query->where('currency', $request->currency);
            }

            $query->orderBy('due_date', 'desc');
            $perPage = $request->input('per_page', 10);
            $invoices = $query->paginate($perPage);

            $statuses = Invoice::statuses();
            $currencies = Invoice::currencies();

            return view('invoices.search', compact('invoices', 'statuses', 'currencies'));
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while performing the search.']);
        }
    }
}
