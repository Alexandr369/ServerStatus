<?php

namespace Azuriom\Plugin\ServerStatus\Console\Commands;

use Azuriom\Models\Server;
use Azuriom\Plugin\ServerStatus\Support\MonitoredServers;
use Azuriom\Plugin\ServerStatus\Support\StatusChecker;
use Illuminate\Console\Command;

class SyncServersCommand extends Command
{
    protected $signature = 'serverstatus:sync';

    protected $description = 'Sync the incident history against each monitored server\'s current status.';

    public function handle(StatusChecker $checker): int
    {
        $servers = Server::whereIn('id', MonitoredServers::ids())->get();

        foreach ($servers as $server) {
            $checker->sync($server);
        }

        $this->info("Synced {$servers->count()} server(s).");

        return self::SUCCESS;
    }
}
