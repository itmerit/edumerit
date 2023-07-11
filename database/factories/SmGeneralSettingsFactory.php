<?php

namespace Database\Factories;


use App\SmGeneralSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmGeneralSettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmGeneralSettings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
             
                'time_zone_id' => 51,
                'system_domain' => url('/'),              
                'income_head_id'=>"1",
                'logo' => 'public/uploads/settings/logo.png',
                'favicon' => 'public/uploads/settings/favicon.png',
        ];
    }
}
