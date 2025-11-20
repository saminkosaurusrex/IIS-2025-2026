<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Show extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'show_type_id',
        'image',
        'description',
    ];
    protected $appends = ['average_rating'];


    protected static function booted()
    {
        static::deleting(function ($show) {
            $eventIds = $show->events()->pluck('id');
            $show->events()->update([
                'deleted_at' => now()
            ]);

            if ($eventIds->isNotEmpty()) {
                DB::table('reservations')
                    ->whereIn('event_id', $eventIds)
                    ->update(['deleted_at' => now()]);
            }


        });
    }

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

