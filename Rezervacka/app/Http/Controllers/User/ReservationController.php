<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;


class ReservationController extends Controller
{
    public function index(){
        $user = auth()->user();
        $reservations = Reservation::with(["event.hall","event.show.tags"])
            ->where("user_id",$user->id)
            ->orderBy('id','desc')->get();

        return Inertia::render('user/reservations/Index',['reservations'=>$reservations]);
    }

    public function downloadPdf(Request $request){

        $accessCodesParam = $request->query('accessCode');
        $accessCodes = $accessCodesParam ? explode(',', $accessCodesParam) : [];

        $reservations = Reservation::whereIn("access_code",$accessCodes)
            ->with(["event.hall","event.show.tags"])
            ->get();

        if ($reservations->isEmpty()) {
            abort(404, 'Žiadne rezervacie k stiahnutiu');
        }

        $pdf = PDF::loadView('reservations/pdf', ['reservations' => $reservations]);
        return $pdf->download('reservation_' . Str::uuid() . '.pdf');
    }
}
