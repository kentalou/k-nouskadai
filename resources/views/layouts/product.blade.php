<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- カスタムCSS -->
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
    @yield('styles') <!-- 子テンプレートで追加するCSSを挿入する -->
</head>
<body>
    <header class="bg-dark text-white text-center py-3">
        <h1> 📊商品管理システム</h1>
    </header>
    <main class="container py-4">
        <h1 class="page-title mb-4 text-start">@yield('title')</h1>
        @if (session('success')) <!-- 処理成功通知 -->
            <div id="flashMessage" style="display: none;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error')) <!-- 処理エラー通知 -->
            <div id="errorMessage" style="display: none;">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any()) <!-- バリデーションエラー通知 -->
            <div id="validationErrorFlag" style="display: none;"></div>
        @endif

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('js/custom.js') }}"></script><!-- 通知設定用JS -->
        @yield('content')
    </main>
    <footer class="text-center py-3">
        © 2025 商品管理システム
    </footer>
    <!-- 各ブラウザ表示カスタム用JS -->
    <script src="{{ asset('js/product.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
