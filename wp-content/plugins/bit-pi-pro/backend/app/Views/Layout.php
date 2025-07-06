<?php

namespace BitApps\PiPro\Views;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\Pi\Config as bitPiConfig;
use BitApps\Pi\Helpers\Hash;
use BitApps\PiPro\Config;
use BitApps\PiPro\Deps\BitApps\WPKit\Hooks\Hooks;
use BitApps\PiPro\Utils\Services\LicenseService;

/**
 * The admin Layout and page handler class.
 */
final class Layout
{
    public const FONT_URL = 'https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap';

    public function __construct()
    {
        Hooks::addAction('in_admin_header', [$this, 'removeAdminNotices']);

        if (class_exists(bitPiConfig::class)) {
            Hooks::addFilter(bitPiConfig::withPrefix('localized_script'), [$this, 'createConfigVariable']);
            Hooks::addFilter(bitPiConfig::withPrefix('admin_sidebar_menu'), [$this, 'sideBarMenuItem']);
        }
    }

    public function removeAdminNotices()
    {
        global $plugin_page;
        if (empty($plugin_page) || strpos($plugin_page, bitPiConfig::SLUG) === false) {
            return;
        }

        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }

    /**
     * Register the admin left sidebar menu item.
     *
     * @param mixed $data
     */
    public function sideBarMenuItem($data)
    {
        $data['Home']['title'] = __('Bit Flows Pro - Your flow of automation\'s', 'bit-pi');
        $data['Home']['name'] = 'Bit Flows Pro';

        $data['Support']['name'] = 'License & Support';

        return $data;
    }

    public function createConfigVariable($data)
    {
        $licenseData = LicenseService::getLicenseData();

        $data['proPluginVersion'] = Config::VERSION;
        $data['proSlug'] = Config::SLUG;
        $data['isPro'] = Config::checkLicenseValidity($licenseData);
        $data['proModuleUrl'] = Config::get('ASSET_URI') . '/';
        $data['isProExist'] = true;

        if ($data['isPro']) {
            $data['key'] = isset($licenseData['key']) ? Hash::encrypt($licenseData['key']) : null;
        }

        return $data;
    }
}
