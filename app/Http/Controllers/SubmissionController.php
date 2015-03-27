<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Session;
use App\Submission;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class SubmissionController extends Controller
{

    /*
	|--------------------------------------------------------------------------
	| Submission Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for showing to the teacher all the submissions
	| from the students.
	|
	*/

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (Auth::user()->id != 1)
            return redirect('home');

        $users = User::with('sessions.submissions')->get(); // This method makes a query to load all the users from the database, eager
        // loading their Sessions & their Session's Submissions

        return view('submission/list')// loads view @ submission/list.blade.php
        ->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $processes = Input::get('processes');
        $method = Input::get('method');
        $session_id = Input::get('session_id');

        foreach ($processes as $process) {
            $p = new Submission;
            $p->process_time = $process['time'];
            $p->process_number = $process['number'];
            $p->quantum_time = $process['quant'];
            $p->arrival_time = $process['arrive'];
            $p->session_id = $session_id;
            $p->method = $method;
            $p->save();
        }

        echo var_dump($processes); // returns the input, just for code check.
    }


    public function endSession($session_id) // gets the end of the session, receiving the time it took & its Session_id
    {
        $session = Session::find($session_id);
        $session->finished++;
        $session->save();
    }

}
