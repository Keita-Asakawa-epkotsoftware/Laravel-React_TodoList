<?php
// ExampleTest.php -> TaskTest.php ファイル名変更


namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;  # 有効化
use Tests\TestCase;  # 追加

class TaskTest extends TestCase  # ExampleTest -> TaskTest クラス名変更
{
    use RefreshDatabase;  # 追加、テスト実行時にDBをリセット
    /**
     * @test  # 追加、アノテーションに @test を付けることでメソッド名に日本語が使用可能になる。
     */
    public function 一覧を取得(): void
    {
        $tasks = Task::factory()->count(10)->create();
        // $response = $this->get('/');
        $response = $this->getJson('/api/tasks/');  # ダミーデータを全て(10件)取得
        // $response = $this->get('/hoge');  # テスト失敗確認用

        // $response->assertStatus(200);
        // $response->assertOk();  # 上記と同じ ステータスコード 200 を返す
        $response->assertOk()->assertJsonCount($tasks->count());  # 取得したダミーデータの件数が同じかテスト
    }
}
// ./vendor/bin/phpunit tests/Feature/ExampleTest.php  テスト確認コード
// ./vendor/bin/phpunit tests/Feature/TaskTest.php  テスト確認コード
