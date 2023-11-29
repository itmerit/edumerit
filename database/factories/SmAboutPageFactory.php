<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmAboutPage;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmAboutPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmAboutPage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => 'About Infix',
            'description' => 'Lisus consequat sapien metus dis urna, facilisi. Nonummy rutrum eu lacinia platea a, ipsum parturient, orci tristique. Nisi diam natoque.',
            'image' => 'public/uploads/about_page/about.jpg',
            'button_text' => 'Learn More About Us',
            'button_url' => 'about',
            'main_title'=>'Under Graduate Education',
            'main_description'=>'INFIX has all in one place. You’ll find everything what you are looking into education management system software. We care! User will never bothered in our real eye catchy user friendly UI & UX  Interface design. You know! Smart Idea always comes to well planners. And Our INFIX is Smart for its Well Documentation. Explore in new support world! It’s now faster & quicker. You’ll find us on Support Ticket, Email, Skype, WhatsApp.',
            'main_image'=>'public/uploads/about_page/about-img.jpg',
        ];
    }
}
