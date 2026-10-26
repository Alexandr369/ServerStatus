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
        'player_history' => 'Players over the last :hours hours',
        'none' => 'No server is currently monitored.',
        'day_operational' => 'Operational',
        'day_partial' => 'Partial outage',
        'day_major' => 'Major outage',
        'day_nodata' => 'No data',
    ],

    'incidents' => [
        'title' => 'Incident history',
        'ongoing' => 'Ongoing',
        'resolved' => 'Resolved',
        'upcoming' => 'Upcoming',
        'upcoming_title' => 'Upcoming maintenance',
        'none' => 'No incident has been recorded.',
        'all_servers' => 'All servers',
        'duration_ongoing' => 'Ongoing for :duration',
        'duration_resolved' => 'Lasted :duration',
        'view_all' => 'View all',
        'back_to_status' => '← Back to status',
        'category_outage' => 'Outage',
        'category_degraded' => 'Degraded performance',
        'category_maintenance' => 'Maintenance',
        'category_info' => 'Information',
        'category_outage_default' => 'Outage on :server',
        'category_degraded_default' => 'Degraded performance on :server',
        'category_maintenance_default' => 'Scheduled maintenance on :server',
        'category_info_default' => 'Information about :server',
    ],
];
