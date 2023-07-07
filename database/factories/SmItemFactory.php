<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $i=0;
    public function definition()
    {
        return [
            'item_name' => $this->faker->colorName.$this->i++,
        ];
    }
}
