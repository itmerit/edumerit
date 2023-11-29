<?php

namespace Database\Factories;

use App\Models\Model;
use App\SmStudentCertificate;
use Illuminate\Database\Eloquent\Factories\Factory;

class SmStudentCertificateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SmStudentCertificate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'              =>'Certificate in Technical Communication (PCTC)',
            'role'               => 2,
            'background_image'   => 'public/uploads/certificate/bg.jpg',
            'body'               => <<<'EOD'
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>CERTIFICATE</title>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link
                    href="https://fonts.googleapis.com/css2?family=Luxurious+Script&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400&display=swap"
                    rel="stylesheet">
                <style>
                    .certificate_box_wrapper {
                        margin: 0;
                        padding: 0;
                        font-family: 'Open Sans', sans-serif;
                        color: #111024;
                        
                        background-image: url(./img/bg.jpg);
                        width: 842px;
                        display: flex;
                        justify-content: center;
                        margin: auto;
                        padding: 70px 0 70px 0;
                        background-repeat: no-repeat;
                        background-size: cover;
                    }

                    .certificate_text {
                        width: 480px;
                        text-align: center;
                    }

                    .Signature_single h5 {
                        font-family: 'Luxurious Script', cursive;
                    }

                    .certificate_text h3 {
                        font-size: 58px;
                        font-weight: 700;
                        text-transform: uppercase;
                        margin-bottom: 0;
                        line-height: 1;
                    }

                    .certificate_text h4 {
                        font-size: 22px;
                        font-weight: 400;
                        text-transform: uppercase;
                        margin: 11px 0 10px 0;
                        position: relative;
                    }

                    .certificate_text h4::before {
                        content: '';
                        position: absolute;
                        right: -4px;
                        width: 145px;
                        height: 2.45px;
                        background: #121124;
                        border-radius: 30px;
                        top: 50%;
                        transform: translateY(-50%);
                    }

                    .certificate_text h4::after {
                        content: '';
                        position: absolute;
                        left: -4px;
                        width: 145px;
                        height: 2.45px;
                        background: #121124;
                        border-radius: 30px;
                        top: 50%;
                        transform: translateY(-50%);
                    }

                    .certificate_text h2 {
                        font-size: 54px;
                        font-weight: 400;
                        font-family: 'Luxurious Script', cursive;
                        margin: 40px 0 20px 0;
                    }

                    .certificate_text .certificate_desc {
                        font-size: 16px;
                        font-weight: 400;
                        text-transform: uppercase;
                    }

                    .certificate_text .certificate_desc2 {
                        font-size: 10px;
                        font-weight: 400;
                        line-height: 12px;
                        max-width: 420px;
                        margin: auto;
                    }

                    .Signature_text {
                        margin-top: 60px;
                        display: flex;
                        justify-content: space-between;
                    }

                    .Signature_single h5 {
                        font-size: 19px;
                        color: #111024;
                        position: relative;
                        min-width: 120px;
                        text-align: center;
                        padding-top: 5px;
                    }

                    .Signature_single h5::before {
                        width: 120px;
                        height: 2.45px;
                        background: #111024;
                        content: '';
                        position: absolute;
                        left: 0;
                        right: 0;
                        top: 0;
                        border-radius: 30px;
                    }

                    .user_img {
                        margin: 0 auto 20px auto;
                        max-width: 345px;
                    }

                    .user_img img {
                        max-width: 100px;
                    }

                    .text-right {
                        text-align: right;
                    }

                    .text-center {
                        text-align: center;
                    }

                    .round {
                        border-radius: 50%;
                    }
                </style>
            </head>
            <body>

                <div class="certificate_box_wrapper">
                    <div class="certificate_text">
                        <div class="user_img round">
                            <img src="img/student.png" alt="">
                        </div>
                        <h3>CERTIFICATE</h3>
                        <h4>OF ACHIEVEMENT</h4>
                        <p class="certificate_desc">THE CERTIFICATE IS PROUDLY PRESENTED TO</p>
                        <h2>Name Surname</h2>
                        <p class="certificate_desc2">This Is a Demo Certificate.</p>
                        <div class="Signature_text">
                            <div class="Signature_single">
                                <h5>Signature</h5>
                            </div>
                            <div class="Signature_single">
                                <h5>Signature</h5>
                            </div>
                        </div>
                    </div>
                </div>


            </body>
            </html>
            EOD,

        ];
    }
}
