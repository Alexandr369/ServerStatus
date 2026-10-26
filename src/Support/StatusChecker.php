<?php

namespace Azuriom\Plugin\ServerStatus\Support;

use Azuriom\Models\Server;
use Azuriom\Plugin\ServerStatus\Models\StatusIncident;
use Azuriom\Plugin\ServerStatus\Notifications\DiscordNotifier;

/**
 * Opens/closes the matching outage incident for a server based on its current
 * status. Reads $server->isOnline(), which is Azuriom core's own cached ping
 * result (refreshed every minute by the core's "game:ping" schedule), so this
 * never pings the server itself and never duplicates core's own monitoring.
 */
class StatusChecker
{
    public function sync(Server $server): void
    {
        $this->syncIncident($server, $server->isOnline());
    }

    protected function syncIncident(Server $server, bool $online): void
    {
        $incident = StatusIncident::outages()->ongoing()
            ->where('server_id', $server->id)
            ->first();

        if (! $online && $incident === null) {
            $incident = StatusIncident::create([
                'server_id' => $server->id,
                'type' => StatusIncident::TYPE_OUTAGE,
                'category' => StatusIncident::CATEGORY_OUTAGE,
                'started_at' => now(),
            ]);

            (new DiscordNotifier())->notifyDown($server, $incident);
        } elseif ($online && $incident !== null) {
            $incident->update(['resolved_at' => now()]);

            (new DiscordNotifier())->notifyUp($server, $incident);
        }
    }
}
