@extends('front_blog.app')
@section('title', '私のブログ')

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h2><a href="{{ route('front_index') }}">私のブログ</a></h2>
            </div>

            <div class="col-md-8 col-md-offset-1">
                {{--forelse ディレクティブを使うと、データがあるときはループし、無いときは @empty 以下を実行する--}}
                @forelse($list as $article)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            {{--post_date は日付ミューテタに設定してあるので、自動的に Carbon インスタンスにキャストされる--}}
                            <h3 class="panel-title">{{ $article->post_date->format('Y/m/d(D)') }}　{{ $article->title }}</h3>
                        </div>
                        <div class="panel-body">
                            {{--nl2br 関数で改行文字を <br> に変換する。これをエスケープせずに表示させるため {!! !!} で囲む--}}
                            {{--ただし、このまま出力するととても危険なので e 関数で htmlspecialchars 関数を通しておく--}}
                            {!! nl2br(e($article->body)) !!}
                        </div>
                        <div class="panel-footer text-right">
                            {{--updated_at も日付ミューテタに設定してあるので Carbon インスタンスにキャストされる--}}
                            {{ $article->updated_at->format('Y/m/d H:i:s') }}
                        </div>
                    </div>
                @empty
                    <p>記事がありません</p>
                @endforelse

                {{ $list->links() }}
            </div>
        </div>
    </div>
@endsection