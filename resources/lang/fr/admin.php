<?php

return [
    'permissions' => [
        'manage' => 'Gérer les réglages et les incidents de la page de statut',
    ],

    'nav' => [
        'title' => 'Statut',
        'incidents' => 'Incidents',
        'settings' => 'Réglages',
    ],

    'settings' => [
        'title' => 'Réglages de Server Status',
        'section_servers' => 'Serveurs surveillés',
        'monitored_servers' => 'Serveurs affichés sur la page de statut',
        'monitored_servers_help' => 'Si aucun n\'est cochée, tous les serveurs du site sont surveillés par défaut.',
        'no_servers' => 'Aucun serveur n\'a encore été configuré.',
        'uptime_window_days' => 'Fenêtre de calcul de la disponibilité (jours)',
        'check_retention_days' => 'Conserver les contrôles bruts pendant (jours)',
        'check_retention_days_help' => 'Les incidents sont conservés indéfiniment ; seuls les échantillons bruts utilisés pour rafraîchir la page sont purgés.',
        'intro_text' => 'Texte d\'introduction (affiché au-dessus de la page de statut)',
        'section_discord' => 'Notifications Discord',
        'discord_webhook' => 'URL du webhook Discord',
        'discord_message_down' => 'Message quand un serveur tombe',
        'discord_message_up' => 'Message quand un serveur revient',
        'discord_message_help' => 'Utilisez {server} pour insérer le nom du serveur.',
        'discord_message_down_default' => ':warning: **{server}** semble hors ligne.',
        'discord_message_up_default' => ':white_check_mark: **{server}** est de nouveau en ligne.',
        'badges_title' => 'Badges de statut',
        'badges_help' => 'Un badge de statut pour chaque serveur, à coller dans une signature de forum, un profil Discord ou un README.',
        'section_banner' => 'Bannière sur le site',
        'show_sitewide_banner' => 'Afficher une bannière sur toutes les pages',
        'show_sitewide_banner_help' => 'Affiche une bannière refermable sur tout le site (pas seulement la page de statut) à propos de l\'incident le plus pertinent : une panne en cours, ou une maintenance à venir/en cours.',
        'maintenance_notice_hours' => 'Prévenir des maintenances à venir (heures avant le début)',
        'maintenance_notice_hours_help' => 'Une maintenance programmée n\'apparaît dans la bannière du site que lorsqu\'elle commence dans ce délai. Mettez 0 pour ne l\'afficher qu\'une fois réellement commencée.',
    ],

    'incidents' => [
        'add_incident' => 'Ajouter un incident',
        'category' => 'Catégorie',
        'origin' => 'Origine',
        'server' => 'Serveur',
        'title_field' => 'Titre',
        'started_at' => 'Début',
        'resolved_at' => 'Fin',
        'resolved_at_help' => 'Laissez la fin vide pour un incident encore en cours, ou mettez un début dans le futur pour une maintenance programmée.',
        'all_servers' => 'Tous les serveurs',
        'type_outage' => 'Automatique',
        'type_maintenance' => 'Manuel',
        'resolve_now' => 'Résoudre maintenant',
        'delete_confirm' => 'Supprimer cet incident ?',
        'none' => 'Aucun incident enregistré.',
    ],
];
