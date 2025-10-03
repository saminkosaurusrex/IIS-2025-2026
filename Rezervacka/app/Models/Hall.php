<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;


    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function shows()
    {
        return $this->belongsToMany(Show::class, 'events')
            ->using(Event::class)
            ->withPivot('starting_at', 'ending_at', 'price');
    }

    public function managed_by_users(){
        return $this->belongsToMany(User::class);
    }

}
