<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }
    
    public function withdraw($amount, $pixKey)
    {
        if ($amount > $this->balance) {
            throw new \Exception('Saldo insuficiente');
        }
        
        $transfer = $this->transfers()->create([
            'amount' => $amount,
            'pix_key' => $pixKey,
            'status' => 'pending',
        ]);
        
        // Em produção, use API de pagamento real para PIX
        $this->balance -= $amount;
        $this->save();
        
        $transfer->update(['status' => 'completed']);
        
        return $transfer;
    }
}
