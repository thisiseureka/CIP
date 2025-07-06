<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\MasterStudyLms\MasterStudyLmsTrigger;

Route::group(
    function () {
        Route::get('get_mslms_all_quiz_by_course', [MasterStudyLmsTrigger::class, 'getAllQuizByCourse']);
        Route::get('get_masterStudyLms_all_course', [MasterStudyLmsTrigger::class, 'getAllCourseEdit']);
        Route::get('get_masterStudyLms_all_lesson', [MasterStudyLmsTrigger::class, 'getAllLessonEdit']);
        Route::get('get_masterStudyLms_all_distribution', [MasterStudyLmsTrigger::class, 'getAllDistributionEdit']);
    }
)->middleware('nonce:admin');
