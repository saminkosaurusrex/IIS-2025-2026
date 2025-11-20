<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Hall extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'address',
        'description',
        'rows',
        'columns',
    ];



    protected static function booted()
    {
        static::deleting(function ($hall) {
            $eventIds = $hall->events()->pluck('id');

            $hall->events()->update([
                'deleted_at' => now()
            ]);

            if ($eventIds->isNotEmpty()) {
                DB::table('reservations')
                    ->whereIn('event_id', $eventIds)
                    ->update(['deleted_at' => now()]);
            }

        });
    }

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
