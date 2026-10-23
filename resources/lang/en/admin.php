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
        'badges_title' => 'Status badges',
        'badges_help' => 'A status badge for each server, ready to embed on a forum signature, a Discord profile or a README.',
        'section_banner' => 'Sitewide notice',
        'show_sitewide_banner' => 'Show a pop-up notice on every page',
        'show_sitewide_banner_help' => 'Shows a one-time pop-up about the most relevant incident (an ongoing outage, or an upcoming/ongoing maintenance) on every page of the site, not just the status page. Once a player closes it, that same incident won\'t pop up again for them.',
        'maintenance_notice_hours' => 'Warn about upcoming maintenance (hours before it starts)',
        'maintenance_notice_hours_help' => 'A scheduled maintenance only appears in the pop-up once it starts within this many hours. Set to 0 to only show it once it has actually started.',
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
