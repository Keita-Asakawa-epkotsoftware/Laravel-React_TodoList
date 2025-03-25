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
