# TodoリストAPI と SPA の作成
Laravel + React

<!-- 目次 -->
## 目次
- [**開発環境**](#開発環境)
- [**手順の流れ**](#手順の流れ)
<!-- 目次 -->

<!-- 開発環境 -->
## 開発環境

**開発環境の構築は[**こちら**](https://github.com/93k-t/Laravel-Next)の環境を使用する。**

- TodoリストAPI: Laravel (バックエンド)
- SPA: React (フロントエンド)

---
<!-- 開発環境 -->

<!-- 手順 -->
## 手順の流れ

1. [**データベースとダミーデータの作成**](#1-データベースとダミーデータの作成)
2. [**簡易APIの作成とテスト**](#2-簡易apiの作成とテスト)
3. 
<!-- 手順 -->

<!-- 1. データベースとダミーデータの作成 -->
### 1. データベースとダミーデータの作成

1. まずは、**データテーブルの作成**とテスト用の**ダミーデータの作成**に必要な各種ファイルを生成する。<br>
`Docker`コンテナに入り、次のコマンドを実行。
    ```sh
    docker exec -it api bash
    php artisan make:model -a Task

    # --- 上記コマンドの実行例 ---
    INFO  Model [app/Models/Task.php] created successfully.
    INFO  Factory [database/factories/TaskFactory.php] created successfully.
    INFO  Migration [database/migrations/yyyy_MM_dd_hhmmss_create_tasks_table.php] created successfully.
    INFO  Seeder [database/seeders/TaskSeeder.php] created successfully.
    INFO  Request [app/Http/Requests/StoreTaskRequest.php] created successfully.
    INFO  Request [app/Http/Requests/UpdateTaskRequest.php] created successfully.
    INFO  Controller [app/Http/Controllers/TaskController.php] created successfully.
    INFO  Policy [app/Policies/TaskPolicy.php] created successfully.
    ```

2. 作成した`マイグレーションファイル`に、今回使用するテーブル構造(スキーマ)を定義。<br>
`マイグレーションファイル`の`up()`メソッドに`title`カラムと`is_done`カラムを追加。

    <details>
    <summary>[ <b>編集するファイル</b> ]</summary>

    ```sh
    # 以下のファイルを編集する
    TodoApp/
      ├── src/
          ├── api/
              ├── database/
                  └── migrations/
                      └── yyyy_MM_dd_hhmmss_create_tasks_table.php  # <= 編集
    ```
    ---
    </details>

    ```php
    # yyyy_MM_dd_hhmmss_create_tasks_table.php

    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->string("title");                     # 追加
                $table->boolean("is_done")->default(false);  # 追加
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('tasks');
        }
    };
    ```

3. 上記の`マイグレーションファイル`を基にデータベースにテーブル構造(スキーマ)を反映させる。<br>
次のコマンドを実行し、[**http://localhost:8080/**](http://localhost:8080/) にアクセス。`tasksテーブル`の存在とテーブル内に`title`カラムと`is_done`カラムが追加されているか確認する。
    ```sh
    php artisan migrate

    # --- 上記コマンドの実行例 ---
     INFO  Running migrations.
    yyyy_MM_dd_hhmmss_create_tasks_table .... 28ms DONE
    ```

4. データベースに挿入する`ダミーデータ`のテンプレートを定義する。<br>
`TaskFactory.php`の`definition()`メソッドに作成する`ダミーデータ`を**連想配列**で定義する。

    <details>
    <summary>[ <b>編集するファイル</b> ]</summary>

    ```sh
    # 以下のファイルを編集する
    TodoApp/
      ├── src/
          ├── api/
              ├── database/
                  └── factories/
                      └── TaskFactory.php  # <= 編集
    ```
    ---
    </details>

    ```php
    # TaskFactory.php
    <?php

    namespace Database\Factories;

    use App\Models\Task;                                          # 追加
    use Illuminate\Database\Eloquent\Factories\Factory;

    /**
     * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
    */

    # ダミーデータのテンプレートを定義
    class TaskFactory extends Factory
    {
        /**
         * Define the model's default state.
         *
         * @return array<string, mixed>
         */
        public function definition(): array
        {
            return [
                "title" => $this->faker->realText(rand(15, 40)),  # 追加
                "is_done" => $this->faker->boolean(10),           # 追加
                "created_at" => now(),                            # 追加
                "updated_at" => now(),                            # 追加
            ];
        }
    }
    ```

1. 次に`ダミーデータ`をデータベースへ挿入する際の件数を定義する。<br>
`TaskSeeder.php`の`run()`メソッドに挿入するデータ数を定義。今回は10件作成する。

    <details>
    <summary>[ <b>編集するファイル</b> ]</summary>

    ```sh
    # 以下のファイルを編集する
    TodoApp/
      ├── src/
          ├── api/
              ├── database/
                  └── seeders/
                      └── TaskSeeder.php  # <= 編集
    ```
    ---
    </details>


    ```php
    <?php

    namespace Database\Seeders;

    use App\Models\Task;                                        # 追加
    use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;


    # Factory ファイルに定義したダミーデータをデータベースへ挿入する際の詳細処理を定義
    class TaskSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            Task::factory()->count(10)->create();             # 追加
        }
    }
    ```

6. `php artisan db:seed`コマンドを実行することでデータベースに`ダミーデータ`が作成される。<br>
上記コマンド実行時に作成した`TaskSeeder.php`の`run()`メソッドを参照するように、`DatabaseSeeder.php`の`run()`メソッド内の`call()`メソッドに`TaskSeeder`クラスを**配列**で追加する。

    <details>
    <summary>[ <b>編集するファイル</b> ]</summary>

    ```sh
    # 以下のファイルを編集する
    TodoApp/
      ├── src/
          ├── api/
              ├── database/
                  └── seeders/
                      └── DatabaseSeeder.php  # <= 編集
    ```
    ---
    </details>

    ```php
    # DatabaseSeeder.php
    <?php

    namespace Database\Seeders;

    // use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;


    # "php artisan db:seed" コマンド実行時にどの Seeder ファイルを参照するか定義する
    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         */
        public function run(): void
        {
            $this->call([             # 追加(配列に変更)
                TaskSeeder::class,
            ]);
        }
    }
    ```

7. 各種ファイルの編集・追記が完了したら次のコマンドを実行し、実際に`ダミーデータ`の作成を行う。<br>
その後、[**http://localhost:8080/**](http://localhost:8080/) にアクセスし`tasksテーブル`内に10件の`ダミーデータ`が挿入されていることを確認する。
    ```sh
    php artisan db:seed

    # --- 上記コマンドの実行例 ---
     INFO  Seeding database.

    Database\Seeders\TaskSeeder .... RUNNING
    Database\Seeders\TaskSeeder .... 111 ms DONE
    ```
    - 作成された`ダミーデータ`の詳細

    | カラム名 | データ内容 | データ型 |
    | --- | --- | --- |
    | `id` | 1 ~ 10のユニークID | int型 |
    | `title` | 15 ~ 40文字のランダムな文字列 | string型 |
    | `is_done` | 完了 or 未完了を判定する真偽値 (初期値は`0`=`False`, 10%の確率で`1`=`True`が含まれるように設定してある) | boolean型 |
    | `created_at` | 作成日時 | timestamp型 |
    | `updated_at` | 更新日時 | timestamp型 |

    [Factory, Seeder, DatabaseSeederの詳細](https://chatgpt.com/c/67e20436-3334-8011-8f35-7e640c26989d)
<!-- 1. データベースとダミーデータの作成 -->

<!-- 2. 簡易APIの作成とテスト -->
## 2. 簡易APIの作成とテスト

1. 作成したダミーデータを取得する簡易的な`API`を作成。<br>
`TaskController.php`の中身を編集。

    <details>
    <summary>[ <b>編集するファイル</b> ]</summary>

    ```sh
    # 以下のファイルを編集する
    TodoApp/
      ├── src/
          ├── api/
              ├── app/
                  └── Http/
                      └── Contorollers/
                          └── TaskController.php  # <= 編集
    ```
    ---
    </details>

    ```php
    # TaskController.php
    <?php

    namespace App\Http\Controllers;

    use App\Http\Requests\StoreTaskRequest;
    use App\Http\Requests\UpdateTaskRequest;
    use App\Models\Task;

    class TaskController extends Controller
    {
        public function index()
        {
            return Task::all();  # 追加
        }
        ...(略)
    }
    ```

2. [**http://localhost/api/tasks**](http://localhost/api/tasks) にアクセスして、ダミーデータが全て(10件)取得できているか確認。

3. 
<!-- 2. 簡易APIの作成とテスト -->
