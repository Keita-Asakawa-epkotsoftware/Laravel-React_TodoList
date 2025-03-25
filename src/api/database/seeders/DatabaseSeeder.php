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
