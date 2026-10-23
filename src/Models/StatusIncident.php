<?php

namespace Azuriom\Plugin\ServerStatus\Models;

use Azuriom\Models\Server;
use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;

class StatusIncident extends Model
{
    /**
     * "type" tracks how the incident was created (auto-detected vs entered by
     * staff), which StatusChecker relies on to find the open auto-outage for a
     * server without touching staff-created incidents. It is not shown as-is
     * to visitors; "category" below is the user-facing classification.
     */
    public const TYPE_OUTAGE = 'outage';

    public const TYPE_MAINTENANCE = 'maintenance';

    public const CATEGORY_OUTAGE = 'outage';

    public const CATEGORY_DEGRADED = 'degraded';

    public const CATEGORY_MAINTENANCE = 'maintenance';

    public const CATEGORY_INFO = 'info';

    public const CATEGORIES = [
        self::CATEGORY_OUTAGE,
        self::CATEGORY_DEGRADED,
        self::CATEGORY_MAINTENANCE,
        self::CATEGORY_INFO,
    ];

    protected $table = 'serverstatus_incidents';

    protected $fillable = ['server_id', 'type', 'category', 'title', 'started_at', 'resolved_at', 'created_by'];

    protected $casts = [
        'started_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Whether the incident is currently active: not resolved, and already started.
     * A scheduled maintenance whose start is still in the future is "upcoming"
     * instead, see isUpcoming().
     */
    public function isOngoing(): bool
    {
        return ! $this->isActuallyResolved() && ! $this->started_at->isFuture();
    }

    /**
     * Whether this is a maintenance scheduled to start later.
     */
    public function isUpcoming(): bool
    {
        return ! $this->isActuallyResolved() && $this->started_at->isFuture();
    }

    /**
     * "resolved_at" doubles as a planned end time when an admin schedules a
     * maintenance with a known end: in that case it can be in the future,
     * meaning the incident is not actually over yet, just has a known end.
     * Only a resolved_at that has already passed means it is truly resolved.
     */
    public function isActuallyResolved(): bool
    {
        return $this->resolved_at !== null && ! $this->resolved_at->isFuture();
    }

    public function isMaintenance(): bool
    {
        return $this->type === self::TYPE_MAINTENANCE;
    }

    /**
     * The incident's end, bounded to now when it is still ongoing.
     */
    public function effectiveEnd()
    {
        return $this->resolved_at ?? now();
    }

    /**
     * The incident's category, falling back to "outage" for rows created
     * before categories existed. Named distinctly from the "category"
     * attribute itself to avoid confusing the two.
     */
    public function categoryOrDefault(): string
    {
        return $this->category ?? self::CATEGORY_OUTAGE;
    }

    /**
     * Bootstrap color matching a given category, used in the status page,
     * the admin incident list, and the sitewide banner.
     */
    public static function categoryColor(string $category): string
    {
        return match ($category) {
            self::CATEGORY_OUTAGE => 'danger',
            self::CATEGORY_DEGRADED => 'warning',
            self::CATEGORY_MAINTENANCE => 'info',
            self::CATEGORY_INFO => 'secondary',
            default => 'secondary',
        };
    }

    public function scopeOutages($query)
    {
        return $query->where('type', self::TYPE_OUTAGE);
    }

    public function scopeOngoing($query)
    {
        return $query->whereNull('resolved_at');
    }
}
