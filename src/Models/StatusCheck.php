<?php

namespace Azuriom\Plugin\ServerStatus\Models;

use Azuriom\Models\Server;
use Illuminate\Database\Eloquent\Model;

class StatusCheck extends Model
{
    /**
     * This table only has its own "checked_at" column, no created_at/updated_at.
     */
    public $timestamps = false;

    protected $table = 'serverstatus_checks';

    protected $fillable = ['server_id', 'status', 'players', 'latency_ms', 'checked_at'];

    protected $casts = [
        'checked_at' => 'datetime',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function isUp(): bool
    {
        return $this->status === 'up';
    }
}
