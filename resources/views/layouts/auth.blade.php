<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '認証画面')</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <main class="auth-container">
        <h1>@yield('title')</h1>

        <form method="POST" action="@yield('route')">
            @csrf
            @yield('method')

            <!-- 入力フィールド（共通構造） -->
            @foreach ($fields ?? [] as $name => $placeholder)
                <div class="form-group">
                    <div class="input-container">
                        <input type="{{ $name === 'email' ? 'email' : ($name === 'password' || $name === 'password_confirmation' ? 'password' : 'text') }}"
                               name="{{ $name }}" 
                               placeholder="{{ $placeholder }}" 
                               value="{{ old($name) }}" 
                               required>
                        @error($name)
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endforeach

            <!-- ボタン部分（共通化） -->
            <div class="form-actions">
                @if (!empty($btnOrange))
                    @if (!empty($btnOrange['href']))
                        <a href="{{ $btnOrange['href'] }}" class="btn btn-orange">
                            {{ $btnOrange['label'] ?? '確定' }}
                        </a>
                    @else
                        <button type="{{ $btnOrange['type'] ?? 'submit' }}" class="btn btn-orange">
                            {{ $btnOrange['label'] ?? '確定' }}
                        </button>
                    @endif
                @endif

                @if (!empty($btnSkyblue))
                    @if (!empty($btnSkyblue['href']))
                        <a href="{{ $btnSkyblue['href'] }}" class="btn btn-skyblue">
                            {{ $btnSkyblue['label'] ?? '戻る' }}
                        </a>
                    @else
                        <button type="{{ $btnSkyblue['type'] ?? 'submit' }}" class="btn btn-skyblue">
                            {{ $btnSkyblue['label'] ?? '戻る' }}
                        </button>
                    @endif
                @endif
            </div>
        </form>
    </main>

    @if (session('success')) <!-- 処理成功通知 -->
        <div id="flashMessage" style="display: none;">{{ session('success') }}</div>
    @endif

    @if (session('error')) <!-- 処理エラー通知 -->
        <div id="errorMessage" style="display: none;">{{ session('error') }}</div>
    @endif

    @if ($errors->any()) <!-- バリデーションエラー通知 -->
        <div id="validationErrorFlag" style="display: none;"></div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/custom.js') }}"></script><!-- 通知設定用JS -->
</body>
</html>
