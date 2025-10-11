<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{

    protected $fillable = [
        'name',
        'show_type_id',
        'image',
        'description',
    ];
    protected $appends = ['average_rating'];

    public function show_type()
    {
        return $this->belongsTo(ShowType::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, ); // pivot table name
    }

    public function performers()
    {
        return $this->belongsToMany(Performer::class ); // pivot table
    }
    public function halls()
    {
        return $this->belongsToMany(Hall::class, 'events')
            ->using(Event::class)
            ->withPivot('starting_at', 'ending_at', 'price');
    }

    public function getAverageRatingAttribute(): ?float
    {
        return $this->rated_by_users()->avg('show_user.rating');

    }

    public function rated_by_users(){
        return $this->belongsToMany(User::class)
                ->withPivot('rating');
    }


}

