<?php

namespace Azuriom\Plugin\ServerStatus\Support;

use Azuriom\Models\Server;

/**
 * The IDs of the servers shown on the public status page and checked by the
 * scheduled command, as configured in the settings. Defaults to every server
 * on the site when the admin never narrowed the selection down.
 */
class MonitoredServers
{
    public static function ids(): array
    {
        $value = setting('serverstatus.monitored_servers');

        if ($value === null) {
            return Server::pluck('id')->all();
        }

        $decoded = json_decode((string) $value, true);

        return is_array($decoded) ? array_map('intval', $decoded) : [];
    }
}
