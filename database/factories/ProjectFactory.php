<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
 /**  @var string 
  * 
 */

  protected $model = \App\Models\Project::class;
   

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'project_name' => $this->faker->name(),
            'project_description' => $this->faker->paragraph(),
            'project_price' => $this->faker->randomNumber(4),
            'included_tasks' => $this->faker->paragraph(),
            'start_date' => $this->faker->dateTimeBetween('2021-01-01', '2021-12-31')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('2022-01-01', '2022-12-31')->format('Y-m-d'),
        ];
    }

}
