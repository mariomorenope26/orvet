<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if (blank($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /** Productos de esta categoría y de sus subcategorías. */
    public function allProducts()
    {
        $ids = $this->children()->pluck('id')->push($this->id);

        return Product::whereIn('category_id', $ids);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort');
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** Nombre con prefijo para selects jerárquicos en el admin. */
    public function getLabelPathAttribute(): string
    {
        return $this->parent ? "{$this->parent->name} › {$this->name}" : $this->name;
    }
}
