<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * ユーザー登録画面を表示
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * ユーザー登録処理
     */
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            User::createUser($request->only(['name', 'email', 'password']));

            DB::commit();
            Auth::logout();

            return redirect()->route('login')->with('success', config('message.register_success'));

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => config('message.register_error')]);
        }
    }
}
