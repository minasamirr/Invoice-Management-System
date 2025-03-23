<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceUpdated;
use App\Models\Invoice;
use App\Models\InvoiceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    public function index()
    {
        return response()->json(Invoice::all());
    }

    public function show(Invoice $invoice)
    {
        return response()->json($invoice);
    }

    public function store(Request $request, Invoice $invoice)
    {
        $this->authorize('manageInvoices');

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount'      => 'required|numeric|min:0',
            'tax'         => 'required|numeric|min:0',
            'due_date'    => 'required|date',
            'description' => 'nullable|string',
            'status'      => 'required|in:Pending,Paid,Overdue',
        ]);

        $invoice = Invoice::create($validated);

        InvoiceLog::create([
            'invoice_id' => $invoice->id,
            'user_id'    => auth()->id(),
            'action'     => 'create',
            'role'       => auth()->user()->role,
            'details'    => 'Invoice created successfully.',
        ]);

        return response()->json(['message' => 'Invoice created successfully.', 'invoice' => $invoice], 201);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

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

        return response()->json(['message' => 'Invoice updated successfully', 'invoice' => $invoice]);
}

    public function destroy(Invoice $invoice)
    {
        $this->authorize('manageInvoices');

        $invoice->delete();

        InvoiceLog::create([
            'invoice_id' => $invoice->id,
            'user_id'    => auth()->id(),
            'action'     => 'delete',
            'role'       => auth()->user()->role,
            'details'    => 'Invoice soft deleted successfully.',
        ]);

        return response()->json(['message' => 'Invoice deleted successfully']);
    }
}
