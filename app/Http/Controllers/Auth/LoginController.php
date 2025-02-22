<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/products'; // 商品一覧ページにリダイレクト

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * ログインフォームを表示
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // ✅ ログイン成功時
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('products.index')->with('success', 'ログインしました！');
        }

        // ❌ ログイン失敗時
        return back()->withInput($request->only('email'))
                     ->with('error', 'ログインに失敗しました。メールアドレスまたはパスワードが間違っています。');
    }

    // /**
    //  * ログアウト処理
    //  */
    // public function logout(Request $request)
    // {
    //     Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    //     return redirect()->route('login')->with('success', 'ログアウトしました。');
    // }
}
