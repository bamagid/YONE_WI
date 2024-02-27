<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function reseau()
    {
        return $this->belongsTo(Reseau::class);
    }
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
