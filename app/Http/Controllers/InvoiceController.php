<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceLog;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::all();
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

        $invoice->update($validated);

        // Log the update action
        InvoiceLog::create([
            'invoice_id' => $invoice->id,
            'user_id'    => auth()->id(),
            'action'     => 'update',
            'role'       => auth()->user()->role,
            'details'    => 'Invoice updated successfully.',
        ]);

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

}
