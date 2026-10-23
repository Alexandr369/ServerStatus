<?php

namespace Azuriom\Plugin\ServerStatus\Notifications;

use Azuriom\Models\Server;
use Azuriom\Plugin\ServerStatus\Models\StatusIncident;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Sends Discord notifications about outages through the webhook configured in
 * the settings. The message text is admin-configurable, using a {server}
 * placeholder. Failures are always swallowed: a Discord outage must never
 * block the status check itself.
 */
class DiscordNotifier
{
    public function notifyDown(Server $server, StatusIncident $incident): void
    {
        $template = setting('serverstatus.discord_message_down', trans('serverstatus::admin.settings.discord_message_down_default'));

        $this->send($template, 15158332, $server, $incident);
    }

    public function notifyUp(Server $server, StatusIncident $incident): void
    {
        $template = setting('serverstatus.discord_message_up', trans('serverstatus::admin.settings.discord_message_up_default'));

        $this->send($template, 3066993, $server, $incident);
    }

    private function send(string $template, int $color, Server $server, StatusIncident $incident): void
    {
        $webhook = setting('serverstatus.discord_webhook');

        if (! $webhook) {
            return;
        }

        try {
            Http::timeout(5)->post($webhook, [
                'embeds' => [[
                    'description' => str_replace('{server}', $server->name, $template),
                    'color' => $color,
                    'fields' => [
                        ['name' => trans('serverstatus::admin.incidents.server'), 'value' => $server->name, 'inline' => true],
                        ['name' => trans('serverstatus::admin.incidents.started_at'), 'value' => $incident->started_at->toDateTimeString(), 'inline' => true],
                    ],
                ]],
            ]);
        } catch (Throwable) {
            // Ignored on purpose: a notification failure must not break the status check.
        }
    }
}
