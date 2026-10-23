<?php

namespace Azuriom\Plugin\ServerStatus\Console\Commands;

use Azuriom\Plugin\ServerStatus\Models\StatusCheck;
use Illuminate\Console\Command;

class PurgeChecksCommand extends Command
{
    protected $signature = 'serverstatus:purge';

    protected $description = 'Delete old status check samples, keeping incidents intact.';

    public function handle(): int
    {
        $retentionDays = (int) setting('serverstatus.check_retention_days', 90);

        $deleted = StatusCheck::where('checked_at', '<', now()->subDays($retentionDays))->delete();

        $this->info("Purged {$deleted} old check(s).");

        return self::SUCCESS;
    }
}
