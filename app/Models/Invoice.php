<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_service_id',
        'user_id',
        'billing_to',
        'address',
        'checkout_link',
        'external_id',
        'invoice_code',
        'status',
        'due_date'
    ];

    protected $with = ['invoice_item'];

    public function invoice_item(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function user_service(): BelongsTo
    {
        return $this->belongsTo(UserService::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
