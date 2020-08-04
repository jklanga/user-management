<?php

namespace App\Http\Controllers;

use App\Interest;
use App\Language;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!Auth::id()) {
            redirect('/');
        }

        $user = User::find(Auth::id());

        $userInterests = [];
        foreach ($user->interests as $interest) {
            $userInterests[$interest->id] = $interest->interest;
        }

        $pageData = [
            'interests' => Interest::orderBy('interest')->get(),
            'languages' => Language::get(),
            'user' => $user,
            'userInterests' => $userInterests,
        ];

        return view('home')->with($pageData);
    }
}
