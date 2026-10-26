<?php

return [
    'permissions' => [
        'manage' => 'Manage the status page settings and incidents',
    ],

    'nav' => [
        'title' => 'Status',
        'incidents' => 'Incidents',
        'settings' => 'Settings',
    ],

    'settings' => [
        'title' => 'Server Status settings',
        'section_servers' => 'Monitored servers',
        'monitored_servers' => 'Servers shown on the status page',
        'monitored_servers_help' => 'If none is checked, every server on the site is monitored by default.',
        'no_servers' => 'No server has been configured yet.',
        'uptime_window_days' => 'Uptime window (days)',
        'intro_text' => 'Intro text (shown above the status page)',
        'section_discord' => 'Discord notifications',
        'discord_webhook' => 'Discord webhook URL',
        'discord_message_down' => 'Message when a server goes down',
        'discord_message_up' => 'Message when a server comes back up',
        'discord_message_help' => 'Use {server} to insert the server name.',
        'discord_message_down_default' => ':warning: **{server}** appears to be down.',
        'discord_message_up_default' => ':white_check_mark: **{server}** is back online.',
        'badges_title' => 'Status badges',
        'badges_help' => 'A status badge for each server, ready to embed on a forum signature, a Discord profile or a README.',
    ],

    'incidents' => [
        'add_incident' => 'Add an incident',
        'category' => 'Category',
        'origin' => 'Origin',
        'server' => 'Server',
        'title_field' => 'Title',
        'started_at' => 'Start',
        'resolved_at' => 'End',
        'resolved_at_help' => 'Leave the end empty for an incident that is still ongoing, or set a future start for a scheduled maintenance.',
        'all_servers' => 'All servers',
        'type_outage' => 'Automatic',
        'type_maintenance' => 'Manual',
        'resolve_now' => 'Resolve now',
        'delete_confirm' => 'Delete this incident?',
        'none' => 'No incident recorded yet.',
    ],
];
