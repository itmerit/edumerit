<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmHomePageSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmHomePageSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmHomePageSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => 'THE ULTIMATE EDUCATION ERP',
            'long_title' => 'INFIX',
            'short_description' => 'Managing various administrative tasks in one place is now quite easy and time savior with this INFIX and Give your valued time to your institute that will increase next generation productivity for our society.',
            'link_label' => 'Learn More About Us',
            'link_url' => 'http://infixedu.com/about',
            'image' => 'public/backEnd/img/client/home-banner1.jpg',
        ];
    }
}
