<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservation extends Model
{
    public $timestamps = false;
    protected $fillable = ['event_id', 'user_id', 'name', 'email', 'row', 'column', 'selected_at', 'confirmed_at'];

    public function seat(){
        return $this->belongsTo(Seat::class);
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }

}
