<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Historique extends Model
{
    use HasFactory;
    protected $table = 'historiques';

    public static function enregistrerHistorique(
        $classe,
        $idEntite,
        $iduser,
        $typeOperation,
        $email,
        $reseau,
        $valeurAvant = null,
        $valeurApres = null,
        $motifSuppression = null
    ) {
        $historique = new Historique();
        $historique->Entite = $classe;
        $historique->ID_Entite = $idEntite;
        $historique->id_user = $iduser;
        $historique->Operation = $typeOperation;
        $historique->Utilisateur = $email;
        $historique->reseau_utilisateur =  $reseau;
        $historique->Valeur_Avant = $valeurAvant;
        $historique->Valeur_Apres = $valeurApres;
        $historique->motif_blockage = $motifSuppression;
        $historique->save();
        Cache::forget($classe . '_actifs');
        Cache::forget('historiques');
        Cache::forget('historiques_user');
        Cache::forget('historiques_classe');
        if ($classe !== "users") {
            Cache::forget('mes_' . $classe . '_actifs');
            Cache::forget($classe . '_supprimes');
        }
    }
}
