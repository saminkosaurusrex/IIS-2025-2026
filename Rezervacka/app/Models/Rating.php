<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'show_user';
    protected $fillable = ['show_id','user_id', 'rating'];

    public function show(){
        return $this->belongsTo(Show::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
