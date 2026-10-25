<?php

/*
|--------------------------------------------------------------------------
| Informations du club pour les pages « Devenir mécène » et « Faire un don »
|--------------------------------------------------------------------------
| À renseigner dans le fichier .env (aucune donnée bancaire dans le dépôt git) :
|
|   CLUB_DON_ORDRE="Cercle des Nageurs du Bocage Bressuirais"
|   CLUB_DON_ADRESSE="CNBB — 40 boulevard de la République, 79300 Bressuire"
|   CLUB_DON_TITULAIRE="Cercle des Nageurs du Bocage Bressuirais"
|   CLUB_DON_IBAN="FR76 …"
|   CLUB_DON_BIC="…"
|
| Sans IBAN, la page indique que le RIB est envoyé sur demande (formulaire de contact).
| Après modification du .env en production : php artisan config:cache (ou php artisan optimize).
*/

return [
    /*
    | Module Records : libellés et zones FFN (idzon d'extraNat) du département et de la région.
    | CLUB_RECORDS_DEPARTEMENT="des Deux-Sèvres", CLUB_RECORDS_ZONE_DEPARTEMENT=1695 …
    */
    'records' => [
        'departement' => env('CLUB_RECORDS_DEPARTEMENT', 'des Deux-Sèvres'),
        'region' => env('CLUB_RECORDS_REGION', 'de Nouvelle-Aquitaine'),
        'zone_departement' => env('CLUB_RECORDS_ZONE_DEPARTEMENT', '1695'),
        'zone_region' => env('CLUB_RECORDS_ZONE_REGION', '3002'),
    ],

    'dons' => [
        'ordre' => env('CLUB_DON_ORDRE', 'Cercle des Nageurs du Bocage Bressuirais'),
        'adresse' => env('CLUB_DON_ADRESSE', 'CNBB — 40 boulevard de la République, 79300 Bressuire'),
        'titulaire' => env('CLUB_DON_TITULAIRE', 'Cercle des Nageurs du Bocage Bressuirais'),
        'iban' => env('CLUB_DON_IBAN'),
        'bic' => env('CLUB_DON_BIC'),
    ],
];
