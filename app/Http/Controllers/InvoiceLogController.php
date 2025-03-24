<?php

namespace App\Http\Controllers;

use App\Models\InvoiceLog;
use Illuminate\Support\Facades\Log;

class InvoiceLogController extends Controller
{
    public function index()
    {
        $this->authorize('manageInvoices');

        try {
            $invoiceLogs = InvoiceLog::with(['invoice', 'user'])->orderBy('created_at', 'desc')->get();
            return view('invoice_logs.index', compact('invoiceLogs'));
        } catch (\Exception $e) {
            Log::error('Invoice log retrieval error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to retrieve invoice logs. Please try again later.');
        }
    }
}
