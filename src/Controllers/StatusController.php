<?php

namespace Azuriom\Plugin\ServerStatus\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Server;
use Azuriom\Plugin\ServerStatus\Models\StatusIncident;
use Azuriom\Plugin\ServerStatus\Support\DailyUptimeBuilder;
use Azuriom\Plugin\ServerStatus\Support\MonitoredServers;
use Azuriom\Plugin\ServerStatus\Support\StatusChecker;
use Azuriom\Plugin\ServerStatus\Support\UptimeCalculator;

class StatusController extends Controller
{
    /**
     * Number of days shown in the daily status bars, independent of the
     * admin-configured uptime percentage window.
     */
    private const DAILY_BARS_DAYS = 90;

    /**
     * Display the public status page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $serverIds = MonitoredServers::ids();
        $servers = Server::whereIn('id', $serverIds)->get();

        $this->syncServers($servers);

        $uptimeCalculator = new UptimeCalculator();
        $dailyUptimeBuilder = new DailyUptimeBuilder();
        $windowDays = (int) setting('serverstatus.uptime_window_days', 30);

        $statusServers = $servers->map(function (Server $server) use ($uptimeCalculator, $dailyUptimeBuilder, $windowDays) {
            return [
                'server' => $server,
                'online' => $server->isOnline(),
                'players' => $server->getOnlinePlayers(),
                'maxPlayers' => $server->getMaxPlayers(),
                'uptime' => $uptimeCalculator->percentage($server, $windowDays),
                'dailyStatus' => $dailyUptimeBuilder->build($server, self::DAILY_BARS_DAYS),
            ];
        });

        $incidents = StatusIncident::with('server')
            ->where(function ($query) use ($serverIds) {
                $query->whereIn('server_id', $serverIds)->orWhereNull('server_id');
            })
            ->where('started_at', '<=', now())
            ->latest('started_at')
            ->limit(10)
            ->get();

        $upcomingMaintenances = StatusIncident::with('server')
            ->where('type', StatusIncident::TYPE_MAINTENANCE)
            ->where(function ($query) use ($serverIds) {
                $query->whereIn('server_id', $serverIds)->orWhereNull('server_id');
            })
            ->where('started_at', '>', now())
            ->oldest('started_at')
            ->get();

        $hasOngoingIncident = $incidents->contains(fn (StatusIncident $incident) => $incident->isOngoing());

        // Note: the view variable is "statusServers", not "servers" - the default
        // theme's layout already shares a global $servers variable of its own
        // (a server list widget), which would otherwise silently override ours.
        return view('serverstatus::index', [
            'statusServers' => $statusServers,
            'incidents' => $incidents,
            'upcomingMaintenances' => $upcomingMaintenances,
            'hasOngoingIncident' => $hasOngoingIncident,
            'introText' => setting('serverstatus.intro_text'),
            'windowDays' => $windowDays,
        ]);
    }

    /**
     * A shields.io status badge for a single server (e.g. for embedding in a
     * forum signature or a Discord profile). Read-only: it never writes a
     * check or opens/closes an incident, so it stays cheap no matter how
     * often it is embedded.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function badge(Server $server)
    {
        $online = $server->isOnline();

        $label = $this->shieldsEscape($server->name);
        $message = $this->shieldsEscape($online
            ? trans('serverstatus::messages.servers.online')
            : trans('serverstatus::messages.servers.offline'));
        $color = $online ? 'brightgreen' : 'red';

        return redirect("https://img.shields.io/badge/{$label}-{$message}-{$color}")
            ->header('Cache-Control', 'public, max-age=60');
    }

    /**
     * Escapes a label/message segment for the shields.io static badge URL
     * format (dash, underscore and space are significant in that syntax).
     */
    private function shieldsEscape(string $value): string
    {
        return strtr($value, ['-' => '--', '_' => '__', ' ' => '_']);
    }

    /**
     * Sync the incident history for every monitored server, so it stays
     * accurate even when the site has no system cron configured to run the
     * scheduled "serverstatus:sync" command. This only reads each server's
     * already-cached online status (see StatusChecker), never pinging it.
     */
    protected function syncServers($servers): void
    {
        $checker = new StatusChecker();

        foreach ($servers as $server) {
            $checker->sync($server);
        }
    }
}
