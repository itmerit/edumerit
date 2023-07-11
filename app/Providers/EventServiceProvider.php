<?php

namespace App\Providers;

use App\Events\ClassTeacherGetAllStudent;
use App\Events\CreateClassGroupChat;
use App\Events\StudentPromotion;
use App\Events\StudentPromotionGroupDisable;
use App\Listeners\InstituteRegisteredListener;
use App\Listeners\ListenClassTeacherGetAllStudent;
use App\Listeners\ListenCreateClassGroupChat;
use App\Listeners\ListenStudentPromotion;
use App\Listeners\ListenStudentPromotionGroupDisable;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Saas\Events\InstituteRegistration;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

        parent::boot();

        Event::listen(
            CreateClassGroupChat::class,
            [ListenCreateClassGroupChat::class, 'handle']
        );

        Event::listen(
            ClassTeacherGetAllStudent::class,
            [ListenClassTeacherGetAllStudent::class, 'handle']
        );
        Event::listen(
            StudentPromotion::class,
            [ListenStudentPromotion::class, 'handle']
        );
        Event::listen(
            StudentPromotionGroupDisable::class,
            [ListenStudentPromotionGroupDisable::class, 'handle']
        );

    }

}
