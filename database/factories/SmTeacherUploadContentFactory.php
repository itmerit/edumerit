<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmTeacherUploadContent;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmTeacherUploadContentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmTeacherUploadContent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public $contents = ['as', 'st', 'ot'];
    public function definition()
    {
        return [
            'content_title' => $this->faker->text(100),
            'content_type' => $this->faker->randomElement($this->contents),
            'available_for_admin' => 1,
            'available_for_all_classes' => 1,
            'upload_date' => $this->faker->dateTime()->format('Y-m-d'),
            'description' => $this->faker->text(500),
            'source_url' => 'google.com',
            'upload_file' => 'public/uploads/upload_contents/sample.pdf',
            'created_by' => 1,
            'created_at' => date('Y-m-d h:i:s'),
        ];
    }
}
