<?php namespace App\Http\Controllers;

use App\Session;
use App\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); // Required the user to be authenticated to access this controller
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $user_id = Auth::user()->id; // gets the current authenticated user
        $user = User::find($user_id);

        $sessions = $user->sessions->where('finished', '==', 0); // check if there is any available session for this user, still to be finished.
        if ($sessions->count() == 0) { // Create Session if there is none to be finished
            $session = new Session;
            $session->user_id = $user_id;
            $session->save();
        } else
            $session = $sessions->first(); // if there is... use the first result ( Older )

        $session_n = User::find($user_id)->sessions->count();
        return view('home')
            ->with('session_id', $session->id)
            ->with('session_n', $session_n);
    }

}
