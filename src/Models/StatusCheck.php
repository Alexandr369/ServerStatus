<?php

namespace Azuriom\Plugin\ServerStatus\Models;

use Azuriom\Models\Server;
use Illuminate\Database\Eloquent\Model;

class StatusCheck extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'serverstatus_checks';

    protected $fillable = ['server_id', 'status', 'players', 'checked_at'];

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
