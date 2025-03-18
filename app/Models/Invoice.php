<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'amount',
        'tax',
        'status',
        'due_date',
        'description',
    ];

    const STATUS_PENDING = 'Pending';
    const STATUS_PAID = 'Paid';
    const STATUS_OVERDUE = 'Overdue';

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_PAID,
            self::STATUS_OVERDUE,
        ];
    }

    // Auto-generate a unique invoice number when creating an invoice
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->invoice_number = 'INV-' . strtoupper(uniqid());
        });
    }

    // Dynamically calculate total amount (amount + tax)
    public function getTotalAmountAttribute()
    {
        return $this->amount + $this->tax;
    }

    // Define relationship to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // An invoice can have many logs.
    public function logs()
    {
        return $this->hasMany(InvoiceLog::class);
    }
}
