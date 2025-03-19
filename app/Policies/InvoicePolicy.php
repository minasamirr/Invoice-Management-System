<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function update(User $user, Invoice $invoice)
    {
        return $user->role === 'employee' || $user->role === 'admin';
    }

    public function manageInvoices(User $user)
    {
        return $user->role === 'admin';
    }
}
