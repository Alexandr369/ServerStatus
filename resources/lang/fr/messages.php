<?php

return [
    'title' => 'Statut des serveurs',

    'banner' => [
        'operational' => 'Tous les systèmes sont opérationnels.',
        'incident' => 'Un ou plusieurs services rencontrent un incident.',
    ],

    'servers' => [
        'title' => 'Serveurs',
        'online' => 'En ligne',
        'offline' => 'Hors ligne',
        'players' => ':online / :max joueurs',
        'uptime' => ':percent% de disponibilité (:days jours)',
        'none' => 'Aucun serveur n\'est actuellement surveillé.',
    ],

    'incidents' => [
        'title' => 'Historique des incidents',
        'ongoing' => 'En cours',
        'resolved' => 'Résolu',
        'none' => 'Aucun incident enregistré.',
        'all_servers' => 'Tous les serveurs',
        'outage_default' => 'Coupure sur :server',
        'maintenance_default' => 'Maintenance programmée sur :server',
    ],
];
