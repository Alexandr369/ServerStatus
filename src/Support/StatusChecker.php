<?php

namespace Azuriom\Plugin\ServerStatus\Support;

use Azuriom\Models\Server;
use Azuriom\Plugin\ServerStatus\Models\StatusCheck;
use Azuriom\Plugin\ServerStatus\Models\StatusIncident;
use Azuriom\Plugin\ServerStatus\Notifications\DiscordNotifier;

/**
 * Pings a server, records the sample, and opens/closes the matching outage
 * incident. Shared by the scheduled command and the lazy check-on-visit
 * fallback used when the site has no system cron configured.
 */
class StatusChecker
{
    public function check(Server $server): StatusCheck
    {
        $online = $server->isOnline();

        $check = StatusCheck::create([
            'server_id' => $server->id,
            'status' => $online ? 'up' : 'down',
            'players' => $online ? $server->getOnlinePlayers() : null,
            'checked_at' => now(),
        ]);

        $this->syncIncident($server, $online);

        return $check;
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
                'started_at' => now(),
            ]);

            (new DiscordNotifier())->notifyDown($server, $incident);
        } elseif ($online && $incident !== null) {
            $incident->update(['resolved_at' => now()]);

            (new DiscordNotifier())->notifyUp($server, $incident);
        }
    }
}
