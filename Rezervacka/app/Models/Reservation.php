<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservation extends Model
{
    public $timestamps = false;
    protected $fillable = ['event_id', 'access_code','user_id', 'name', 'email', 'row', 'column', 'reserved_at', 'confirmed_at','paid_at','canceled_at'];

    public function seat(){
        return $this->belongsTo(Seat::class);
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
