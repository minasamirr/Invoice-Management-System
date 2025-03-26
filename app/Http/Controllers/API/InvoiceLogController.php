<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\InvoiceLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class InvoiceLogController extends Controller
{
    public function index()
    {
        $this->authorize('manageInvoices');

        try {
            $perPage = request()->query('per_page', 10);
            $invoiceLogs = InvoiceLog::with(['invoice', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

            return response()->json([
                'status'  => 'success',
                'message' => 'Invoice logs retrieved successfully.',
                'data'    => $invoiceLogs
            ], 200);
        } catch (\Exception $e) {
            Log::error('Invoice log retrieval error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to retrieve invoice logs. Please try again later.'
            ], 500);
        }
    }
}
