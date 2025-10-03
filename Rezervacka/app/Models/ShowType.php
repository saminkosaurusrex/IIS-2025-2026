<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShowType extends Model
{
    public function show()
    {
        return $this->hasMany(Show::class);
    }
}
