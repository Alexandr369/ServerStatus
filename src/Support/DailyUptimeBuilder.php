<?php

namespace Azuriom\Plugin\ServerStatus\Support;

use Azuriom\Models\Server;
use Azuriom\Models\ServerStat;
use Azuriom\Plugin\ServerStatus\Models\StatusIncident;

/**
 * Builds the day-by-day status bars shown on the public page (one entry per
 * day, newest last): "operational", "partial", "major", or "nodata" for a
 * day that predates the server's first core ServerStat record (i.e. before
 * Azuriom started tracking this server at all).
 */
class DailyUptimeBuilder
{
    public function build(Server $server, int $days): array
    {
        $earliestStat = ServerStat::where('server_id', $server->id)->oldest()->first();

        $windowStart = now()->subDays($days)->startOfDay();

        $outages = StatusIncident::outages()
            ->where('server_id', $server->id)
            ->where('started_at', '<', now())
            ->where(function ($query) use ($windowStart) {
                $query->whereNull('resolved_at')->orWhere('resolved_at', '>', $windowStart);
            })
            ->get();

        $result = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $dayStart = now()->subDays($i)->startOfDay();
            $dayEnd = $dayStart->copy()->endOfDay();

            if ($earliestStat === null || $dayStart->lt($earliestStat->created_at->copy()->startOfDay())) {
                $result[] = ['date' => $dayStart, 'status' => 'nodata', 'downtimePercent' => null];

                continue;
            }

            $downSeconds = 0;

            foreach ($outages as $incident) {
                $start = $incident->started_at->lt($dayStart) ? $dayStart : $incident->started_at;
                $end = $incident->effectiveEnd()->gt($dayEnd) ? $dayEnd : $incident->effectiveEnd();

                if ($end->gt($start)) {
                    $downSeconds += $start->diffInSeconds($end);
                }
            }

            $daySeconds = max(1, $dayStart->diffInSeconds($dayEnd));
            $downRatio = min(1, $downSeconds / $daySeconds);

            $status = match (true) {
                $downRatio <= 0 => 'operational',
                $downRatio >= 0.95 => 'major',
                default => 'partial',
            };

            $result[] = [
                'date' => $dayStart,
                'status' => $status,
                'downtimePercent' => round($downRatio * 100, 1),
            ];
        }

        return $result;
    }
}
