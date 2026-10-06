<?php

namespace App\Service;

class Store
{
    public function getEvenement():array
    {
        return $evenements = [
            1 => [
                'id' => 1,
                'titre' => 'Soirée Étudiante Halloween',
                'description' => 'Grande soirée costumée pour célébrer Halloween au campus !',
                'date_debut' => '2025-10-31 20:00:00',
                'date_fin' => '2025-11-01 02:00:00',
                'lieu' => 'Amphithéâtre Central',
                'categorie' => 'festif',
                'organisateur' => 'BDE Campus',
                'prix' => 8.0,
                'places_disponibles' => 150,
                'places_totales' => 200,
                'image' => 'halloween.jpg',
                'statut' => 'terminé'
            ],
            2 => [
                'id' => 2,
                'titre' => 'Tournoi de Badminton Inter-Filières',
                'description' => 'Compétition amicale de badminton en double ouverte à tous les étudiants.',
                'date_debut' => '2025-11-15 14:00:00',
                'date_fin' => '2025-11-15 18:00:00',
                'lieu' => 'Gymnase Universitaire',
                'categorie' => 'sportif',
                'organisateur' => 'BDS Campus',
                'prix' => 0.0,
                'places_disponibles' => 32,
                'places_totales' => 32,
                'image' => 'badminton.jpg',
                'statut' => 'terminé'
            ],
            3 => [
                'id' => 3,
                'titre' => 'Conférence : L\'IA et l\'Éthique',
                'description' => 'Table ronde animée par des enseignants-chercheurs sur l\'impact sociétal de l\'IA.',
                'date_debut' => '2025-11-20 18:30:00',
                'date_fin' => '2025-11-20 20:30:00',
                'lieu' => 'Grand Amphi A',
                'categorie' => 'culturel',
                'organisateur' => 'Département Informatique',
                'prix' => 0.0,
                'places_disponibles' => 45,
                'places_totales' => 120,
                'image' => 'conference_ia.jpg',
                'statut' => 'terminé'
            ],
            4 => [
                'id' => 4,
                'titre' => 'Collecte Alimentaire Solidaire',
                'description' => 'Mobilisation pour récolter des denrées non périssables au profit des étudiants précaires.',
                'date_debut' => '2026-01-25 09:00:00',
                'date_fin' => '2026-01-26 17:00:00',
                'lieu' => 'Hall Principal',
                'categorie' => 'associatif',
                'organisateur' => 'Agora Éco',
                'prix' => 0.0,
                'places_disponibles' => 0,
                'places_totales' => 300,
                'image' => 'collecte.jpg',
                'statut' => 'terminé'
            ],
            5 => [
                'id' => 5,
                'titre' => 'Gala de Fin de Semestre',
                'description' => 'Grande soirée de gala annuelle avec buffet, concert et DJ set.',
                'date_debut' => '2026-02-13 20:30:00',
                'date_fin' => '2026-02-14 04:00:00',
                'lieu' => 'Salle des Fêtes',
                'categorie' => 'festif',
                'organisateur' => 'BDE Campus',
                'prix' => 15.0,
                'places_disponibles' => 0,
                'places_totales' => 300,
                'image' => 'gala.jpg',
                'statut' => 'terminé'
            ],
            6 => [
                'id' => 6,
                'titre' => 'Exposition Photo : Regards croisés',
                'description' => 'Vernissage et exposition des plus beaux clichés réalisés par le club photo du campus.',
                'date_debut' => '2026-10-02 10:00:00',
                'date_fin' => '2026-10-06 18:00:00',
                'lieu' => 'Galerie de la BU',
                'categorie' => 'culturel',
                'organisateur' => 'Club Photo',
                'prix' => 0.0,
                'places_disponibles' => 80,
                'places_totales' => 80,
                'image' => 'exposition.jpg',
                'statut' => 'ouvert'
            ],
            7 => [
                'id' => 7,
                'titre' => 'E-sport Tournament : League of Legends',
                'description' => 'LAN party et tournoi sur PC par équipes de 5 avec lots à la clé.',
                'date_debut' => '2026-11-07 10:00:00',
                'date_fin' => '2026-11-07 22:00:00',
                'lieu' => 'Salle Informatique 3',
                'categorie' => 'sportif',
                'organisateur' => 'Gaming Club',
                'prix' => 5.0,
                'places_disponibles' => 0,
                'places_totales' => 40,
                'image' => 'esport.jpg',
                'statut' => 'complet'
            ],
            8 => [
                'id' => 8,
                'titre' => 'Atelier Réparation & Reconditionnement',
                'description' => 'Apprenez à réparer vos petits appareils électroniques et votre vélo gratuitement.',
                'date_debut' => '2026-12-10 14:00:00',
                'date_fin' => '2026-12-10 17:00:00',
                'lieu' => 'Atelier FabLab',
                'categorie' => 'associatif',
                'organisateur' => 'EcoCampus',
                'prix' => 0.0,
                'places_disponibles' => 5,
                'places_totales' => 15,
                'image' => 'repair_cafe.jpg',
                'statut' => 'ouvert'
            ],
        ];
    }
}
