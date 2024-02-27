<?php

namespace App\Models;

use App\Models\Tarif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function ligne()
    {
        return $this->belongsTo(Ligne::class);
    }
    public function tarif()
    {
        return $this->belongsTo(Tarif::class);
    }
}
