<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Event extends Model
{
    protected $table = 'events';
    protected $fillable = ['hall_id', 'show_id', 'starting_at','ending_at','price'];

    public $timestamps = false;
    public function hall()
    {
        return $this->belongsTo(Hall::class, 'hall_id');
    }

    public function show()
    {
        return $this->belongsTo(Show::class, 'show_id');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
