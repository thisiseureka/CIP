<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\AcademyLms\AcademyLmsHelper;

Route::group(
    function () {
        Route::get('get_academylms_all_quiz', [AcademyLmsHelper::class, 'getAllQuiz']);
        Route::get('get_academylms_all_lesson', [AcademyLmsHelper::class, 'getAllLesson']);
        Route::get('get_academylms_all_course', [AcademyLmsHelper::class, 'getAllCourse']);
    }
)->middleware('nonce:admin');
