<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmItemCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmItemCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmItemCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $i=0;
    public function definition()
    {
        return [
            'category_name' => $this->faker->colorName . $this->i++ ,
        ];
    }
}
