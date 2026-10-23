<?php

namespace Azuriom\Plugin\ServerStatus\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Server;
use Azuriom\Plugin\ServerStatus\Models\StatusCheck;
use Azuriom\Plugin\ServerStatus\Models\StatusIncident;
use Azuriom\Plugin\ServerStatus\Support\MonitoredServers;
use Azuriom\Plugin\ServerStatus\Support\StatusChecker;
use Azuriom\Plugin\ServerStatus\Support\UptimeCalculator;

class StatusController extends Controller
{
    /**
     * Display the public status page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $serverIds = MonitoredServers::ids();
        $servers = Server::whereIn('id', $serverIds)->get();

        $this->refreshStaleChecks($servers);

        $uptimeCalculator = new UptimeCalculator();
        $windowDays = (int) setting('serverstatus.uptime_window_days', 30);

        $servers = $servers->map(function (Server $server) use ($uptimeCalculator, $windowDays) {
            return [
                'server' => $server,
                'online' => $server->isOnline(),
                'players' => $server->getOnlinePlayers(),
                'maxPlayers' => $server->getMaxPlayers(),
                'uptime' => $uptimeCalculator->percentage($server, $windowDays),
            ];
        });

        $incidents = StatusIncident::with('server')
            ->where(function ($query) use ($serverIds) {
                $query->whereIn('server_id', $serverIds)->orWhereNull('server_id');
            })
            ->latest('started_at')
            ->limit(10)
            ->get();

        $hasOngoingIncident = $incidents->contains(fn (StatusIncident $incident) => $incident->isOngoing());

        return view('serverstatus::index', [
            'servers' => $servers,
            'incidents' => $incidents,
            'hasOngoingIncident' => $hasOngoingIncident,
            'introText' => setting('serverstatus.intro_text'),
            'windowDays' => $windowDays,
        ]);
    }

    /**
     * Refresh any monitored server whose last recorded check is stale, so the
     * page stays accurate even when the site has no system cron configured
     * to run the scheduled "serverstatus:check" command.
     */
    protected function refreshStaleChecks($servers): void
    {
        $checker = new StatusChecker();
        $staleBefore = now()->subMinutes(2);

        foreach ($servers as $server) {
            $latest = StatusCheck::where('server_id', $server->id)->latest('checked_at')->first();

            if ($latest === null || $latest->checked_at->lt($staleBefore)) {
                $checker->check($server);
            }
        }
    }
}
