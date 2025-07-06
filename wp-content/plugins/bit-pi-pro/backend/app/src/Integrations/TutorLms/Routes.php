<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\TutorLms\TutorLmsHelper;

Route::group(
    function () {
        Route::get('get_tutorlms_all_quiz', [TutorLmsHelper::class, 'getAllQuiz']);
        Route::get('get_tutorlms_all_lesson', [TutorLmsHelper::class, 'getAllLesson']);
        Route::get('get_tutorlms_all_course', [TutorLmsHelper::class, 'getAllCourse']);
    }
)->middleware('nonce:admin');
