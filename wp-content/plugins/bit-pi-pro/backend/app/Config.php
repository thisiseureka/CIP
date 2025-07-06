<?php

// phpcs:disable Squiz.NamingConventions.ValidVariableName

namespace BitApps\PiPro;

use BitApps\PiPro\Utils\Services\LicenseService;

if (!\defined('ABSPATH')) {
    exit;
}

/**
 * Provides App configurations.
 */
class Config
{
    public const SLUG = 'bit-pi-pro';

    public const TITLE = 'Bit Flows Pro';

    public const VAR_PREFIX = 'bit_pi_pro_';

    public const FREE_PLUGIN_SLUG = 'bit-pi';

    public const FREE_PLUGIN_PREFIX = 'bit_pi_';

    public const FREE_PLUGIN_TITLE = 'Bit Flows';

    public const VERSION = '1.2.0';

    public const DB_VERSION = '0.1.0';

    public const REQUIRED_PHP_VERSION = '7.4';

    public const REQUIRED_WP_VERSION = '5.0';

    public const API_VERSION = '1.0';

    public const APP_BASE = '../../' . self::SLUG . '.php';

    public const ASSETS_FOLDER = 'assets';

    /**
     * Provides configuration for plugin.
     *
     * @param string $type    Type of conf
     * @param string $default Default value
     *
     * @return null|array|string
     */
    public static function get($type, $default = null)
    {
        switch ($type) {
            case 'MAIN_FILE':
                return realpath(__DIR__ . DIRECTORY_SEPARATOR . self::APP_BASE);

            case 'BASENAME':
                return plugin_basename(trim(self::get('MAIN_FILE')));

            case 'BASEDIR':
                return plugin_dir_path(self::get('MAIN_FILE')) . 'backend';

            case 'ROOT_DIR':
                return plugin_dir_path(self::get('MAIN_FILE'));

            case 'SITE_URL':
                return site_url();

            case 'ADMIN_URL':
                return str_replace(self::get('SITE_URL'), '', get_admin_url());

            case 'API_URL':
                global $wp_rewrite;

                return [
                    'base'      => get_rest_url(null, self::SLUG . '/v1'),
                    'separator' => $wp_rewrite->permalink_structure ? '?' : '&',
                ];

            case 'ROOT_URI':
                return set_url_scheme(plugins_url('', self::get('MAIN_FILE')), wp_parse_url(home_url())['scheme']);

            case 'ASSET_URI':
                return self::get('ROOT_URI') . '/' . self::ASSETS_FOLDER;

            case 'PLUGIN_PAGE_LINKS':
                return self::pluginPageLinks();

            case 'WP_DB_PREFIX':
                global $wpdb;

                return $wpdb->prefix;

            default:
                return $default;
        }
    }

    /**
     * Prefixed variable name with prefix.
     *
     * @param string $option Variable name
     *
     * @return array
     */
    public static function withPrefix($option)
    {
        return self::VAR_PREFIX . $option;
    }

    /**
     * Retrieves options from option table.
     *
     * @param string $option  Option name
     * @param bool   $default default value
     * @param bool   $wp      Whether option is default wp option
     *
     * @return mixed
     */
    public static function getOption($option, $default = false, $wp = false)
    {
        if ($wp) {
            return get_option($option, $default);
        }

        return get_option(self::withPrefix($option), $default);
    }

    /**
     * Check license exists.
     *
     * @return bool
     */
    public static function isPro()
    {
        $licenseData = LicenseService::getLicenseData();

        return self::checkLicenseValidity($licenseData);
    }

    /**
     * Check license validity.
     *
     * @param array $licenseData License data
     *
     * @return bool
     */
    public static function checkLicenseValidity($licenseData)
    {
        return !empty($licenseData) && \is_array($licenseData) && $licenseData['status'] === 'success';
    }

    /**
     * Saves option to option table.
     *
     * @param string $option   Option name
     * @param bool   $autoload Whether option will autoload
     * @param mixed  $value
     *
     * @return bool
     */
    public static function addOption($option, $value, $autoload = false)
    {
        return add_option(self::withPrefix($option), $value, '', $autoload ? 'yes' : 'no');
    }

    /**
     * Save or update option to option table.
     *
     * @param string $option   Option name
     * @param mixed  $value    Option value
     * @param bool   $autoload Whether option will autoload
     *
     * @return bool
     */
    public static function updateOption($option, $value, $autoload = null)
    {
        return update_option(self::withPrefix($option), $value, \is_null($autoload) ? null : 'yes');
    }

    /**
     * Provides links for plugin pages. Those links will bi displayed in
     * all plugin pages under the plugin name.
     *
     * @return array
     */
    private static function pluginPageLinks()
    {
        return [
            'license' => [
                'title' => __('License', 'bit-pi'),
                'url'   => Config::get('ADMIN_URL') . 'admin.php?page=' . Config::FREE_PLUGIN_SLUG . '#/license',
            ],
        ];
    }
}
