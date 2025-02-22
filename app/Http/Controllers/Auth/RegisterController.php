<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * ユーザー登録画面を表示
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * ユーザー登録処理
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction(); // 🔹 トランザクション開始

        try {
            // ユーザーの作成
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            DB::commit(); // 🔹 成功したらコミット

            // ログアウトしてログイン画面にリダイレクト
            Auth::logout();
            return redirect()->route('login')->with('success', '新規登録が完了しました。');

        } catch (Exception $e) {
            DB::rollBack(); // 🔹 失敗時はロールバック

            return back()->withErrors(['error' => 'ユーザー登録中にエラーが発生しました。']);
        }
    }
}
