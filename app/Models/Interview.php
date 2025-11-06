<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $table = 'interviews';

    protected $fillable = [
        'titre',
        'sous_titre',
        'image',
        'nom_interviewe',
        'poste_interviewe',
        'club_equipe',
        'equipe_id',  // AJOUT ICI
        'introduction',
        'contenu',
        'date_interview',
        'date_publication',
        'auteur',
        'categorie',
        'statut',
        'vues',
        'admin_id'
    ];

    protected $casts = [
        'date_interview' => 'date',
        'date_publication' => 'datetime',
        'vues' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relation avec Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    // Relation avec Equipe - AJOUT ICI
    public function equipe()
    {
        return $this->belongsTo(Equipe::class, 'equipe_id');
    }

    // Méthode pour obtenir le nombre total d'interviews
    public static function total()
    {
        return self::count();
    }

    // OU si vous voulez seulement les interviews publiées
    public static function totalPublie()
    {
        return self::publie()->count();
    }

    // OU si vous voulez le total des vues
    public static function totalVues()
    {
        return self::sum('vues');
    }

    // Vérifier si c'est une vidéo
    public function isVideo()
    {
        if (empty($this->image)) {
            return false;
        }

        $extension = pathinfo($this->image, PATHINFO_EXTENSION);
        return in_array(strtolower($extension), ['mp4', 'webm', 'ogg', 'mov']);
    }

    // Scope pour les interviews publiées
    public function scopePublie($query)
    {
        return $query->where('statut', 'publie')
                     ->whereNotNull('date_publication')
                     ->where('date_publication', '<=', now());
    }

    // Scope par catégorie
    public function scopeCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    // Incrémenter les vues
    public function incrementVues()
    {
        $this->increment('vues');
        return $this;
    }
}
