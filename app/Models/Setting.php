<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    /**
     * Devuelve la fila única de ajustes (la crea si no existe).
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
