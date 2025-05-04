<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'logo_url',
        'logo_width',
        'logo_height',
        'payment_methods',
        'template',
        'primary_color',
        'secondary_color',
        'slug'
    ];

    protected $casts = [
        'payment_methods' => 'array',
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->title) . '-' . Str::random(8);
        $this->save();
        
        return $this->slug;
    }

    public function getCheckoutUrl()
    {
        return url('/checkout/' . $this->slug);
    }

    public function getSalesCount()
    {
        return $this->orders()->where('status', 'approved')->count();
    }
    
    public function getTotalRevenue()
    {
        return $this->orders()->where('status', 'approved')->sum('amount');
    }
}
