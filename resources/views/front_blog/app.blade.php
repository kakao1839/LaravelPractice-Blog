<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    {{--@yield ディレクティブは指定したセクションの内容を表示するために使用する--}}
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"><!-- Bootstrap本体 -->
    <link rel="stylesheet" href="{{ asset('/css/blog.css') }}">

    app.js を読み込めば jQuery や bootstrap.js が読み込まれる
    <script src="{{ asset('/js/app.js') }}"></script>
    {{--API を叩くための準備として CSRF 用トークンを設定しておく--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--各ページで <head> タグ内に追加できるようにしておく--}}
    @yield('head')
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h2><a href="{{ route('front_index') }}">私のブログ</a></h2>

            {{--何らかのエラー表示用--}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{--メインカラム--}}
        @yield('main')
        {{--右サブカラム--}}
        {{--@include ディレクティブで他のテンプレートを読み込むこともできる--}}
        @include('front_blog.right_column')
    </div>
</div>
</body>
</html>