<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\LifterLms\LifterLmsHelper;

Route::group(
    function () {
        Route::get('get_lifterLms_all_quiz', [LifterLmsHelper::class, 'getLifterLmsAllQuiz']);
        Route::get('get_lifterLms_all_lesson', [LifterLmsHelper::class, 'getLifterLmsAllLesson']);
        Route::get('get_lifterLms_all_course', [LifterLmsHelper::class, 'getLifterLmsAllCourse']);
        Route::get('get_lifterLms_all_membership', [LifterLmsHelper::class, 'getLifterLmsAllMembership']);
    }
)->middleware('nonce:admin');
