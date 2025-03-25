<?php

namespace Database\Seeders;

use App\Models\Task;                                        # 追加
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


# Factory に登録したダミーデータをデータベースへ作成
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
