<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // ユーザーがこのリクエストの権限を持っているかどうかを判断する
        // ここではひとまず true にしておく
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // バリデーションルールはここに追加する
        // 項目名 => ルールという形式で、ルールが複数ある場合は '|' で区切る
        return [
            'article_id' => 'integer|nullable',              // 整数・null でもOK
            'post_date' => 'required|date',                 // 必須・日付
            'title'     => 'required|string|max:255',       // 必須・文字列・最大値（255文字まで）
            'body'      => 'required|string|max:10000',     // 必須・文字列・最大値（10000文字まで）
        ];

        $rules['delete'] = [
            'article_id' => 'required|integer'     // 必須・整数
        ];

        return array_get($rules, $action, []);
    }


    public function messages()
    {
        // 表示されるバリデートエラーメッセージを個別に編集したい場合は、ここに追加する
        // 項目名.ルール => メッセージという形式で書く
        // プレースホルダーを使うこともできる
        // 下記の例では :max の部分にそれぞれ設定した値（255, 10000）が入る
        return [
            'post_date.required' => '日付は必須です',
            'post_date.date'     => '日付は YYYY/MM/DD の形式で入力してください',
            'title.required'     => 'タイトルは必須です',
            'title.string'       => 'タイトルは文字列を入力してください',
            'title.max'          => 'タイトルは:max文字以内で入力してください',
            'body.required'      => '本文は必須です',
            'body.string'        => '本文は文字列を入力してください',
            'body.max'           => '本文は:max文字以内で入力してください',
        ];
    }

    public function someMethod(AdminBlogRequest $request)
    {
        // バリデートOKなら、このメソッドが実行される
        // バリデートNGなら、このメソッドは実行されずに、元のアドレスにリダイレクトされる
        // その際、エラー内容が自動的にフラッシュデータとしてセッションに保存される
    }
    /**
     * 現在実行中のアクション名を返す
     *
     * @return mixed
     */
    public function getCurrentAction()
    {
        // 実行中のアクション名を取得
        // App\Http\Controllers\AdminBlogController@post のような返り値が返ってくるので @ で分割
        $route_action = Route::currentRouteAction();
        list(, $action) = explode('@', $route_action);
        return $action;
    }
}