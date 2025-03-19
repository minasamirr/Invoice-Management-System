<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceLog;

class InvoiceLogController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized.');
        }

        $invoiceLogs = InvoiceLog::with(['invoice', 'user'])->orderBy('created_at', 'desc')->get();
        return view('invoice_logs.index', compact('invoiceLogs'));
    }
}
