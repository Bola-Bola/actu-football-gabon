<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $stats = [
            'articles_publies' => 500,
            'equipes_couvertes' => 24,
            'matchs_commentes' => 300,
            'utilisateurs_actifs' => 15000
        ];

        $teamMembers = [
            [
                'nom' => 'Oky Chris',
                'poste' => 'Directeur Général',
                'photo' => 'default-avatar.jpg',
                'bio' => 'Fondateur et visionnaire d\'ActuFootGabon, passionné par le développement du football gabonais.'
            ],
            [
                'nom' => 'OPAPE MENSAH Christ Michaël',
                'poste' => 'Responsable Communication',
                'photo' => 'default-avatar.jpg',
                'bio' => 'Expert en communication digitale et gestion des réseaux sociaux.'
            ],
            [
                'nom' => 'Équipe Rédactionnelle',
                'poste' => 'Journalistes & Analystes',
                'photo' => 'default-avatar.jpg',
                'bio' => 'Une équipe de passionnés dédiée à couvrir l\'actualité footballistique gabonaise.'
            ]
        ];

        $valeurs = [
            [
                'titre' => 'Fiabilité',
                'description' => 'Des informations vérifiées et fiables pour nos lecteurs',
                'icon' => 'fa-shield-alt'
            ],
            [
                'titre' => 'Rapidité',
                'description' => 'L\'actualité en temps réel pour ne rien manquer',
                'icon' => 'fa-bolt'
            ],
            [
                'titre' => 'Proximité',
                'description' => 'Au plus près des clubs, joueurs et supporters',
                'icon' => 'fa-users'
            ],
            [
                'titre' => 'Innovation',
                'description' => 'Des outils modernes pour une expérience optimale',
                'icon' => 'fa-rocket'
            ]
        ];

        return view('home.about', compact('stats', 'teamMembers', 'valeurs'));
    }
}
