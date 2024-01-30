<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    }
}
