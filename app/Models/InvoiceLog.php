<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'user_id',
        'action',
        'role',
        'details',
    ];

    // Each log belongs to an invoice.
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }


    // Each log is created by a user.
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
