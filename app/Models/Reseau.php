<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reseau extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }
    public  function types()
    {
        return $this->hasMany(Type::class);
    }

    public function lignes()
    {
        return $this->hasMany(Ligne::class);
    }
    public function tarifs()
    {
        return $this->hasMany(Tarif::class);
    }
}
