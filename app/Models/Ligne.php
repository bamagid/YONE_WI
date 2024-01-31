<?php

namespace App\Models;

use App\Models\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ligne extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
    public function reseau()
    {
        return $this->hasMany(Reseau::class);
    }
}
