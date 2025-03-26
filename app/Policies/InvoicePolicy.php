<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function update(User $user, Invoice $invoice)
    {
        return in_array($user->role, User::statuses());
    }

    public function manageInvoices(User $user)
    {
        return $user->role === User::STATUS_ADMIN;
    }
}
