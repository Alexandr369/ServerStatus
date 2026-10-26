<?php

namespace Azuriom\Plugin\ServerStatus\Support;

use Azuriom\Models\Server;
use Azuriom\Models\ServerStat;

/**
 * Builds the points for the lightweight player-count sparkline shown on the
 * public status page, entirely from Azuriom core's own ServerStat history:
 * this plugin never samples player counts itself.
 */
class PlayerHistoryBuilder
{
    /**
     * @return array{points: string}|null Null when there isn't enough
     * history yet (fewer than 2 samples) to draw a meaningful line.
     */
    public function build(Server $server, int $hours): ?array
    {
        $stats = ServerStat::where('server_id', $server->id)
            ->where('created_at', '>=', now()->subHours($hours))
            ->orderBy('created_at')
            ->get(['players', 'created_at']);

        $count = $stats->count();

        if ($count < 2) {
            return null;
        }

        $max = max(1, $stats->max('players'));

        $points = $stats->values()->map(function ($stat, $index) use ($count, $max) {
            $x = round($index / ($count - 1) * 100, 2);
            $y = round(30 - ($stat->players / $max) * 28, 2);

            return "{$x},{$y}";
        })->implode(' ');

        return ['points' => $points];
    }
}
