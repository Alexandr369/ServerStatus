<?php

namespace Azuriom\Plugin\ServerStatus\Support;

use Azuriom\Models\Server;
use Azuriom\Plugin\ServerStatus\Models\StatusIncident;

class UptimeCalculator
{
    /**
     * The percentage of the given window during which the server had no open
     * outage incident, bounding any still-ongoing incident to "now".
     */
    public function percentage(Server $server, int $windowDays): float
    {
        $windowStart = now()->subDays($windowDays);
        $windowEnd = now();
        $windowSeconds = $windowStart->diffInSeconds($windowEnd);

        if ($windowSeconds <= 0) {
            return 100.0;
        }

        $incidents = StatusIncident::outages()
            ->where('server_id', $server->id)
            ->where('started_at', '<', $windowEnd)
            ->where(function ($query) use ($windowStart) {
                $query->whereNull('resolved_at')->orWhere('resolved_at', '>', $windowStart);
            })
            ->get();

        $downSeconds = 0;

        foreach ($incidents as $incident) {
            $start = $incident->started_at->lt($windowStart) ? $windowStart : $incident->started_at;
            $end = $incident->effectiveEnd();
            $end = $end->gt($windowEnd) ? $windowEnd : $end;

            if ($end->gt($start)) {
                $downSeconds += $start->diffInSeconds($end);
            }
        }

        $uptimeSeconds = max(0, $windowSeconds - $downSeconds);

        return round($uptimeSeconds / $windowSeconds * 100, 2);
    }
}
