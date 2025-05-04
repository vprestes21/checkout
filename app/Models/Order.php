<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'buyer_id',
        'status',
        'payment_method',
        'transaction_id',
        'amount',
        'buyer_email',
        'buyer_name',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    
    public function seller()
    {
        return $this->product->user;
    }
    
    public function markAsApproved()
    {
        if ($this->status !== 'approved') {
            $this->update(['status' => 'approved']);
            
            // Adicionar valor à carteira do vendedor
            $seller = $this->product->user;
            $wallet = $seller->wallet ?? $seller->wallet()->create(['balance' => 0]);
            $wallet->balance += $this->amount;
            $wallet->save();
        }
    }
    
    public function markAsRejected()
    {
        $this->update(['status' => 'rejected']);
    }
}
