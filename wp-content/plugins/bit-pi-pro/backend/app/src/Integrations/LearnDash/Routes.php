<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\LearnDash\LearnDashHelper;

Route::group(
    function () {
        Route::get('get_all_lessons_by_course', [LearnDashHelper::class, 'getLessonsByCourse']);
        Route::get('get_all_topic_by_lesson', [LearnDashHelper::class, 'getTopicsByLesson']);
        Route::get('get_all_courses', [LearnDashHelper::class, 'getCourses']);
        Route::get('get_all_quizes', [LearnDashHelper::class, 'getQuizes']);
        Route::get('get_all_groups', [LearnDashHelper::class, 'getGroups']);
    }
)->middleware('nonce:admin');
