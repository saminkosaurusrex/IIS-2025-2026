<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(Request $request): Response
    {
        $accessCodesParam = $request->query('accessCode');
        $accessCodes = $accessCodesParam ? explode(',', $accessCodesParam) : [];
        $name = $request->query('name');
        $email = $request->query('email');

        return Inertia::render('auth/Register',["access_codes" => $accessCodes, "name" => $name, "email" => $email]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'reservation_email' => 'nullable|string|email|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'access_codes' => ['nullable', 'array'],
            'access_codes.*' => ['string']
        ]);

        $join_reservation = isset($data['access_codes']) && isset($data['reservation_email']);

        if($join_reservation){
            $reservations = Reservation::whereIn('access_code', $data['access_codes'])->where("email", $data['reservation_email'])->get();
            if(count($reservations) != count($data['access_codes'])){
            return back()->withErrors([
                  'access_codes' => "Chyba so spárovaním rezervácií. Skúste pokračovať s registráciou priamo pomocou tlačidla na stránke moje rezervácie"
             ])->withInput();
            }
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        if($join_reservation){
            $reservations->each(function ($reservation) use ($user) {
                $reservation->user_id = $user->id;
                $reservation->save();
            });
            return redirect()->to("my-reservations");
        }

        return to_route('dashboard');
    }
}
