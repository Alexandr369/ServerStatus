<?php

namespace Azuriom\Plugin\ServerStatus\Console\Commands;

use Azuriom\Models\Server;
use Azuriom\Plugin\ServerStatus\Support\MonitoredServers;
use Azuriom\Plugin\ServerStatus\Support\StatusChecker;
use Illuminate\Console\Command;

class CheckServersCommand extends Command
{
    protected $signature = 'serverstatus:check';

    protected $description = 'Ping the monitored servers and update their status history.';

    public function handle(StatusChecker $checker): int
    {
        $servers = Server::whereIn('id', MonitoredServers::ids())->get();

        foreach ($servers as $server) {
            $checker->check($server);
        }

        $this->info("Checked {$servers->count()} server(s).");

        return self::SUCCESS;
    }
}
