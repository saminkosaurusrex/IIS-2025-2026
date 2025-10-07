<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Carbon;

class Event extends Model
{


    protected $table = 'events';
    protected $fillable = ['hall_id', 'show_id', 'starting_at','ending_at','price'];
    protected $appends = ['starting_at_human', 'ending_at_human'];
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

    public function getStartingAtHumanAttribute()
    {
        if (!$this->starting_at) {
            return null;
        }

        $date = Carbon::parse($this->starting_at)->locale('sk');
        return $date->translatedFormat('l j. F Y \o H:i');
    }

    // ⏱️ trvanie v minútach
    public function getEndingAtHumanAttribute()
    {
        if (!$this->starting_at || !$this->ending_at) {
            return null;
        }

        $start = Carbon::parse($this->starting_at);
        $end = Carbon::parse($this->ending_at);

        $minutes = $start->diffInMinutes($end);

        return $minutes;
    }
}
