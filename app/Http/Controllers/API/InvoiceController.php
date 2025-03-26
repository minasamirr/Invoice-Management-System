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
    // Display all invoices.
    public function index()
    {
        try {
            $perPage = request()->query('per_page', 10);
            $invoices = Invoice::paginate($perPage);
            return response()->json([
                'status'  => 'success',
                'message' => 'Invoices retrieved successfully.',
                'data'    => $invoices,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching invoices: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch invoices.',
            ], 500);
        }
    }

    //  Display a specific invoice.
    public function show(Invoice $invoice)
    {
        try {
            return response()->json([
                'status'  => 'success',
                'message' => 'Invoice retrieved successfully.',
                'data'    => $invoice,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error showing invoice: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to fetch invoice.',
            ], 500);
        }
    }

    // Create a new invoice.
    public function store(Request $request, Invoice $invoice)
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

            return response()->json([
                'status'  => 'success',
                'message' => 'Invoice created successfully.',
                'data'    => $invoice,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating invoice: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to create invoice.',
            ], 500);
        }
    }

    public function update(Request $request, Invoice $invoice)
    {
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

            return response()->json([
                'status'  => 'success',
                'message' => 'Invoice updated successfully.',
                'data'    => $invoice,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating invoice: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to update invoice.',
            ], 500);
        }
}

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

            return response()->json([
                'status'  => 'success',
                'message' => 'Invoice deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting invoice: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to delete invoice.',
            ], 500);
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

            return response()->json([
                'status'     => 'success',
                'message'    => 'Invoices retrieved successfully.',
                'data'       => [
                    'invoices'   => $invoices,
                    'statuses'   => $statuses,
                    'currencies' => $currencies,
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while performing the search.'
            ], 500);
        }
    }
}
