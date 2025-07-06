<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Router\Route;
use BitApps\PiPro\src\Integrations\GamiPress\GamiPressHelper;

Route::group(
    function () {
        Route::get('get_all_rank_by_types', [GamiPressHelper::class, 'getAllRankByType']);
        Route::get('get_all_award_by_achievement_type', [GamiPressHelper::class, 'getAllAwardByAchievementType']);
        Route::get('get_all_achievement_type', [GamiPressHelper::class, 'getAllAchievementType']);
        Route::get('get_all_rank_type', [GamiPressHelper::class, 'getAllRankType']);
    }
)->middleware('nonce:admin');
