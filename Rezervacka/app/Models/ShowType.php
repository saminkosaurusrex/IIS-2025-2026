<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ShowType extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];

    protected static function booted()
    {
        static::deleting(function ($show_type) {
            $showIds = $show_type->show()->pluck('id');
            $show_type->show()->update([
                'deleted_at' => now()
            ]);

            if ($showIds->isNotEmpty()) {
                $eventIds = DB::table('events')
                    ->whereIn('show_id', $showIds)
                    ->pluck('id');

                DB::table('events')
                    ->whereIn('id', $eventIds)
                    ->update(['deleted_at' => now()]);

                if ($eventIds->isNotEmpty()) {
                    DB::table('reservations')
                        ->whereIn('event_id', $eventIds)
                        ->update(['deleted_at' => now()]);
                }
            }
        });
    }

    public function show()
    {
        return $this->hasMany(Show::class);
    }
}
