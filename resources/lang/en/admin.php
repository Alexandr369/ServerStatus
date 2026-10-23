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
        'check_retention_days' => 'Keep raw checks for (days)',
        'check_retention_days_help' => 'Incidents are kept forever; only the raw check samples used to refresh the page are purged.',
        'intro_text' => 'Intro text (shown above the status page)',
        'section_discord' => 'Discord notifications',
        'discord_webhook' => 'Discord webhook URL',
        'discord_message_down' => 'Message when a server goes down',
        'discord_message_up' => 'Message when a server comes back up',
        'discord_message_help' => 'Use {server} to insert the server name.',
        'discord_message_down_default' => ':warning: **{server}** appears to be down.',
        'discord_message_up_default' => ':white_check_mark: **{server}** is back online.',
    ],

    'incidents' => [
        'add_maintenance' => 'Schedule a maintenance',
        'server' => 'Server',
        'title_field' => 'Title',
        'started_at' => 'Start',
        'resolved_at' => 'End',
        'resolved_at_help' => 'Leave the end empty for a maintenance that is still ongoing.',
        'all_servers' => 'All servers',
        'type_outage' => 'Outage',
        'type_maintenance' => 'Maintenance',
        'resolve_now' => 'Resolve now',
        'delete_confirm' => 'Delete this incident?',
        'none' => 'No incident recorded yet.',
    ],
];
