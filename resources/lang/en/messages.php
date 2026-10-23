<?php

return [
    'title' => 'Server status',

    'banner' => [
        'operational' => 'All systems are operational.',
        'incident' => 'One or more services are experiencing an incident.',
    ],

    'servers' => [
        'title' => 'Servers',
        'online' => 'Online',
        'offline' => 'Offline',
        'players' => ':online / :max players',
        'uptime' => ':percent% uptime (:days days)',
        'none' => 'No server is currently monitored.',
    ],

    'incidents' => [
        'title' => 'Incident history',
        'ongoing' => 'Ongoing',
        'resolved' => 'Resolved',
        'none' => 'No incident has been recorded.',
        'all_servers' => 'All servers',
        'outage_default' => 'Outage on :server',
        'maintenance_default' => 'Scheduled maintenance on :server',
    ],
];
