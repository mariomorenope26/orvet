<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort');
    }

    public function specs()
    {
        return $this->hasMany(ProductSpec::class)->orderBy('sort');
    }

    public function sections()
    {
        return $this->hasMany(ProductSection::class)->orderBy('sort');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getAvailabilityLabelAttribute(): string
    {
        return match ($this->availability) {
            'in_stock' => 'En stock',
            'out_of_stock' => 'Agotado',
            'on_request' => 'A pedido',
            default => 'Consultar',
        };
    }
}
