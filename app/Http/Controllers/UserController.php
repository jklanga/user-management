<?php

namespace App\Http\Controllers;

use App\User;
use App\UserInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = [
                'name' => $request->input('name'),
                'surname' => $request->input('surname'),
                'mobile_number' => $request->input('mobile_number'),
                'id_number' => $request->input('id_number'),
                'dob' => $request->input('dob'),
                'language_id' => $request->input('language_id'),
            ];

            if (!empty($request->input('password'))) {
                $input['password'] = Hash::make($request->input('password'));
            }

            $validator = $this->validator($input);
            if ($validator->fails()) {
                return redirect('home')->withInput()->withErrors($validator);
            }

            $user = User::find(Auth::id());
            if (!$user->updateUser($input)) {
                return redirect('home')->withInput()->with('error', 'There was a problem updating the user.');
            }

            Session::flash('success', 'User updated.');
        }

        return redirect('home');
    }

    /**
     * @param array $input
     *
     * @return array|\Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $input)
    {
        $userId = Auth::id();

        $rules = [
            'name' => 'required',
            'surname' => 'required',
            'mobile_number' => 'required|unique:users,mobile_number,' . $userId,
            'id_number' => 'required|unique:users,id_number,' . $userId,
            'dob' => 'required',
            'language_id' => 'required',
        ];

        if (!empty($input['password'])) {
            $rules['password'] = 'same:confirm-password';
        }

        return Validator::make($input, $rules);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function editInterests(Request $request)
    {
        $userId = $request->get('id');

        if ($request->isMethod('post')) {
            $userInterest = new UserInterest();
            $userInterest->where('user_id', '=', $userId)->delete();

            $interests = $request->input('user_interests');
            foreach ($interests as $key => $interestId) {
                $userInterest->create(
                    [
                        'user_id' => $userId,
                        'interest_id' => $interestId
                    ]
                );
            }

            Session::flash('success', 'Interest(s) updated.');
        }

        return redirect('home');
    }
}
