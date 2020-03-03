<?php

namespace App\Http\Controllers;
use App\Http\Requests\AdminBlogRequest;
use App\Models\Article;


class AdminBlogController extends Controller

{
    /** @var Article */
    protected $article;

    function __construct(Article $article)
    {
        // Article モデルクラスのインスタンスを作成
        // 「依存注入」により、コンストラクタの引数にタイプヒントを指定するだけで、
        // インスタンスが生成される（コンストラクターインジェクション）
        $this->article = $article;
    }

    /**
     * ブログ記事入力フォーム
     *
     * @param  int $article_id 記事ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function form(int $article_id = null)
    {
        $article = $this->article->find($article_id);

        // 記事データがあれば toArray メソッドで配列にしておき、フォーマットした post_date を入れる
        $input = [];
        if ($article) {
            $input = $article->toArray();
            $input['post_date'] = $article->post_date_text;
        } else {
            $article_id = null;
        }

        $input = array_merge($input, old());

        // カテゴリーの取得
        // pluck メソッドを使って引数に指定した項目で配列を生成する
        $category_list = $this->category->getCategoryList()->pluck('name', 'category_id');

        return view('admin_blog.form', compact('input', 'article_id', 'category_list'));
    }

    /**
     * ブログ記事保存処理
     *
     * @param AdminBlogRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function post(AdminBlogRequest $request)
    {
        // こちらも引数にタイプヒントを指定すると、
        // AdminBlogRequest のインスタンスが生成される（メソッドインジェクション）
        // そして、AdminBlogRequest で設定したバリデートも実行される（フォームリクエストバリデーション）

        // 入力値の取得
        $input = $request->input();

        // create メソッドで複数代入を実行する。
        // 対象テーブルのカラム名と配列のキー名が一致する場合、一致するカラムに一致するデータが入る
        $article = $this->article->create($input);

        // リダイレクトでフォーム画面に戻る
        // route ヘルパーでリダイレクト先を指定。ルートのエイリアスを使う場合は route ヘルパーを使う
        // with メソッドで、セッションに次のリクエスト限りのデータを保存する
        return redirect()->route('admin_form')->with('message', '記事を保存しました');
    }

    /**
     * ブログ記事削除処理
     *
     * @param AdminBlogRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(AdminBlogRequest $request)
    {
        // 記事IDの取得
        $article_id = $request->input('article_id');

        // Article モデルを取得して delete メソッドを実行することで削除できる
        // このとき万が一 $article が null になる場合も想定して実装するのが良い（今回は紹介のみで使わないので割愛）
//        $article = $this->article->find($article_id);
//        $article->delete();

        // 主キーの値があるなら destroy メソッドで削除することができる
        // 引数は配列でも可。返り値は削除したレコード数
        $result = $this->article->destroy($article_id);
        $message = ($result) ? '記事を削除しました' : '記事の削除に失敗しました。';

        // フォーム画面へリダイレクト
        return redirect()->route('admin_list')->with('message', $message);
    }

    // 1ページ当たりの表示件数
    const NUM_PER_PAGE = 10;

    /**
     * ブログ記事一覧画面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list()
    {
        $list = $this->article->getArticleList(self::NUM_PER_PAGE);
        return view('admin_blog.list', compact('list'));
    }

    /**
     * カテゴリ一覧画面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category()
    {
        $list = $this->category->getCategoryList(self::NUM_PER_PAGE);
        return view('admin_blog.category', compact('list'));
    }

    /**
     * カテゴリ編集・新規作成API
     *
     * @param AdminBlogRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCategory(AdminBlogRequest $request)
    {
        $input = $request->input();
        $category_id = $request->input('category_id');

        $category = $this->category->updateOrCreate(compact('category_id'), $input);

        // APIなので json のレスポンスを返す
        return response()->json($category);
    }

    /**
     * カテゴリ削除API
     *
     * @param AdminBlogRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCategory(AdminBlogRequest $request)
    {
        $category_id = $request->input('category_id');
        $this->category->destroy($category_id);

        // APIなので json のレスポンスを返す
        return response()->json();
    }


}