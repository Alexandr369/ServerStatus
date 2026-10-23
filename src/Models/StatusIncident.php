<?php

namespace Azuriom\Plugin\ServerStatus\Models;

use Azuriom\Models\Server;
use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;

class StatusIncident extends Model
{
    public const TYPE_OUTAGE = 'outage';

    public const TYPE_MAINTENANCE = 'maintenance';

    protected $table = 'serverstatus_incidents';

    protected $fillable = ['server_id', 'type', 'title', 'started_at', 'resolved_at', 'created_by'];

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
        return $this->resolved_at === null && ! $this->started_at->isFuture();
    }

    /**
     * Whether this is a maintenance scheduled to start later.
     */
    public function isUpcoming(): bool
    {
        return $this->resolved_at === null && $this->started_at->isFuture();
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

    public function scopeOutages($query)
    {
        return $query->where('type', self::TYPE_OUTAGE);
    }

    public function scopeOngoing($query)
    {
        return $query->whereNull('resolved_at');
    }
}
