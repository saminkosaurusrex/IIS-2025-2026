<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request){
        $data = $request->validate([
            'rating' => 'required|numeric|min:0|max:10',
            'show_id' => 'required|exists:shows,id',
        ]);
        Rating::updateOrCreate(
            [
                'show_id' => $data['show_id'],
                'user_id' => auth()->id(),
            ],
            [
                'rating' => $data['rating']*2,
            ]
        );
        return redirect()->back();
    }
}
