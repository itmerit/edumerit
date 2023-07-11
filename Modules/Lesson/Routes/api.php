<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Lesson\Http\Controllers\api\StudentLessonApiController;

Route::middleware('auth:api')->group(function () {
    Route::get('student-lesson-plan/{user_id}/{record_id}', [StudentLessonApiController::class, 'index']);
    Route::get('student-lesson-plan-by-date/{user_id}/{record_id}/{date}/{day_id}', [StudentLessonApiController::class, 'getLessonByDate']);
    
    Route::get('student-lesson-plan-previous-week/{user_id}/{record_id}/{start_date}', [StudentLessonApiController::class, 'previousWeek']);
    
    Route::get('student-lesson-plan-next-week/{user_id}/{record_id}/{end_date}', [StudentLessonApiController::class, 'nextWeek']);
    
});