<?php

namespace Config;

return [
    'APP_NAME' => 'GES STOCK Administration',
    'APP_ENV' => $_ENV['APP_ENV'] ?? 'local',
    'APP_DEBUG' => $_ENV['APP_DEBUG'] ?? false,
    'APP_URL' => $_ENV['APP_URL'] ?? 'http://localhost:8000',

    'DB_HOST' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'DB_PORT' => $_ENV['DB_PORT'] ?? 3306,
    'DB_NAME' => $_ENV['DB_DATABASE'] ?? 'ges_stock',
    'DB_USER' => $_ENV['DB_USERNAME'] ?? 'root',
    'DB_PASS' => $_ENV['DB_PASSWORD'] ?? '',

    'PAGINATION_LIMIT' => 15,

    'ROLES' => [
        'admin' => 'Administrateur',
        'magasinier' => 'Magasinier',
        'chef_service' => 'Chef de Service',
        'consultateur' => 'Consultateur'
    ],

    'PERMISSIONS' => [
        'admin' => ['*'],
        'magasinier' => [
            'articles.view', 'articles.create', 'articles.edit',
            'stock.view', 'stock.create', 'stock.export',
            'demandes.view', 'demandes.prepare',
            'bons.view', 'bons.create',
            'reports.view'
        ],
        'chef_service' => [
            'articles.view',
            'demandes.view', 'demandes.create', 'demandes.validate',
            'stock.view', 'stock.export',
            'reports.view'
        ],
        'consultateur' => [
            'articles.view',
            'demandes.view',
            'stock.view',
            'reports.view'
        ]
    ],

    'STOCK_TYPES' => [
        'entree' => 'Entrée',
        'sortie' => 'Sortie',
        'inventaire' => 'Inventaire',
        'transfert' => 'Transfert',
        'ajustement' => 'Ajustement'
    ],

    'UNITS' => [
        'piece' => 'Pièce(s)',
        'kg' => 'Kilogramme(s)',
        'litre' => 'Litre(s)',
        'metre' => 'Mètre(s)',
        'carton' => 'Carton(s)',
        'colis' => 'Colis'
    ],

    'LOG_RETENTION_DAYS' => 2555, // 7 ans
    'MAX_LOGIN_ATTEMPTS' => 5,
    'LOCK_DURATION_SECONDS' => 900, // 15 minutes
];
