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
        'latency' => ':ms ms',
        'none' => 'Aucun serveur n\'est actuellement surveillé.',
        'day_operational' => 'Opérationnel',
        'day_partial' => 'Coupure partielle',
        'day_major' => 'Coupure majeure',
        'day_nodata' => 'Aucune donnée',
    ],

    'incidents' => [
        'title' => 'Historique des incidents',
        'ongoing' => 'En cours',
        'resolved' => 'Résolu',
        'upcoming' => 'À venir',
        'upcoming_title' => 'Maintenance à venir',
        'none' => 'Aucun incident enregistré.',
        'all_servers' => 'Tous les serveurs',
        'outage_default' => 'Coupure sur :server',
        'maintenance_default' => 'Maintenance programmée sur :server',
    ],
];
