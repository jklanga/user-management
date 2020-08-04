<?php

namespace App\Http\Controllers;

use App\Language;
use App\User;
use App\UserInterest;
use Illuminate\Auth\Notifications\VerifyEmail;
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
    public function updateProfile(Request $request)
    {
        if ($request->isMethod('post')) {
            $input = $this->getInput($request);

            $emailUpdated = $request->input('email') != Auth::user()->email;

            if (!$emailUpdated) {
                unset($input['email']);
            } else {
                $input['email_verified_at'] = null;
            }

            $validator = $this->validator($input, Auth::id());
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $user = User::find(Auth::id());
            if (!$user->updateUser($input)) {
                return redirect('home')->withInput()->with('error', 'There was a problem updating the user.');
            }

            if ($emailUpdated) {
                $user->notify(new VerifyEmail);
            }

            Session::flash('success', 'User updated.');
        }

        return redirect('home');
    }

    /**
     * @param array $input
     * @param int|null $userId
     *
     * @return array|\Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $input, $userId = null)
    {
        $rules = [
            'name' => 'required',
            'surname' => 'required',
            //'email' => 'required|email|unique:users,email,' . $userId,
            'mobile_number' => 'required|unique:users,mobile_number,' . $userId,
            'id_number' => 'required|unique:users,id_number,' . $userId,
            'dob' => 'required',
            'language_id' => 'required',
        ];

        if (!empty($input['email'])) {
            $rules['email'] = 'required|email|unique:users,email,' . $userId;
        }

        if (!empty($input['password'])) {
            $rules['password'] = 'same:confirm-password';
        }

        return Validator::make($input, $rules);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function getInput(Request $request): array
    {
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

        if (!empty($request->input('role'))) {
            $input['role'] = $request->input('role');
        }

        if (!empty($request->input('email'))) {
            $input['email'] = $request->input('email');
        }

        return $input;
    }

    public function edit(Request $request)
    {
        $userId = $request->get('userId');

        if ($request->isMethod('post')) {
            $user = new User();

            $input = $this->getInput($request);

            $validator = $this->validator($input, $userId);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            if (empty($userId)) {
                if (!empty($request->input('new-password'))) {
                    $input['password'] = Hash::make($request->input('new-password'));
                } else {
                    $input['password'] = Hash::make($request->input('id_number'));
                }

                $userId = $user->createUser($input);
                if (!$userId) {
                    redirect()->back()->withInput()->with('error', 'There was an error creating new user');
                }

                // Send verification email
                $user = User::find($userId);
                $user->notify(new VerifyEmail);

                Session::flash('success', 'User created, and verification email sent to ' . $user->email);
            } else {
                $user = User::find($userId);
                if (!$user->updateUser($input)) {
                    return Redirect::route('user.edit', 'id='.$userId)->withInput()->with('error', 'There was an error updating the user');
                }
                Session::flash('success', 'Updated.');
            }

            return redirect('user/list');
        }

        $pageData = [];

        if (!empty($userId)) {
            $pageData['user'] = User::find($userId);
        }

        $pageData['languages'] = Language::get();

        return view('user.edit', $pageData);
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

    /**
     * @param array $input
     *
     * @return array|\Illuminate\Contracts\Validation\Validator
     */
    public function passwordValidator(array $input)
    {
        $rules = [
            'password' => 'same:confirm-password',
        ];

        return Validator::make($input, $rules);
    }

    public function resetPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = Auth::user();

            if (!Hash::check($request->post('current-password'), $user->password)) {
                return redirect()->back()->with('error', 'Please enter the valid current password');
            }

            $validator = $this->passwordValidator($request->all());
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator);
            }

            $user->password = Hash::make($request->post('confirm-password'));

            if (!$user->save()) {
                return redirect('home')->withInput()->with('error', 'There was a problem updating the user.');
            }

            Session::flash('success', 'Password updated.');
        }

        return redirect('home');
    }

    public function list()
    {
        return view('user.list')->with(
            [
                'users' => User::where('id', '!=', Auth::id())->get(),
            ]
        );
    }

    public function interests(Request $request)
    {
        return view('user.interests')->with(
            [
                'user' => User::find($request->get('userId')),
            ]
        );
    }
    public function delete(Request $request)
    {
        $userId = $request->post('userId');
        $user = User::find($userId);
        $user->delete();
    }
}
