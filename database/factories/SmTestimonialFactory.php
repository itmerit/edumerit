<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmTestimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmTestimonialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmTestimonial::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'designation' => 'CEO',
            'institution_name' => 'Google',
            'image' => 'public/uploads/testimonial/testimonial_1.jpg',
            'description' => 'its vast! Infix has more additional feature that will expect in a complete solution.',
            'created_at' => date('Y-m-d h:i:s')
        ];
    }
}
