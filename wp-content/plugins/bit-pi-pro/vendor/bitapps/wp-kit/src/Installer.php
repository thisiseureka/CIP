<?php

namespace BitApps\PiPro\Deps\BitApps\WPKit;

if (!\defined('ABSPATH')) {
    exit;
}

use BitApps\PiPro\Deps\BitApps\WPKit\Hooks\Hooks;
use BitApps\PiPro\Deps\BitApps\WPKit\Migration\MigrationHelper;

/**
 * Class handling plugin activation, deactivation, uninstall.
 *
 * @since 1.0.0
 */
final class Installer
{
    private $_requirements;

    private $_hooks;

    private $_migration;

    private static $_drop;

    /**
     * Sets necessary elements
     *
     * @param array $requirements
     * @param array $hooks
     * @param array $migration
     */
    public function __construct($requirements, $hooks, $migration)
    {
        $this->_requirements = $requirements;
        $this->_hooks        = $hooks;
        $this->_migration    = $migration['migration'];
        self::$_drop         = $migration['drop'];
    }

    public function register()
    {
        if (isset($this->_hooks['activate'])) {
            Hooks::addAction($this->_hooks['activate'], [$this, 'activate']);
        }

        if (isset($this->_hooks['uninstall'])) {
            // Only a static class method or function can be used in an uninstall hook.
            Hooks::addAction($this->_hooks['uninstall'], [self::class, 'uninstall']);
        }
    }

    public function activate($isNetworkActivation)
    {
        $this->checkRequirements();
        if (
            isset($this->_requirements['multisite']) && $this->_requirements['multisite']
                                                     && $isNetworkActivation
        ) {
            $this->activateOnMultiSite();
        } else {
            $this->activateOnSingleSite();
        }
    }

    public function activateOnSingleSite()
    {
        if (version_compare($this->_requirements['oldVersion'], $this->_requirements['version'], '<')) {
            MigrationHelper::migrate($this->_migration);
        }
    }

    public function activateOnMultiSite()
    {
        $sites = get_sites((['fields' => 'ids', 'network_id' => get_current_network_id()]));
        foreach ($sites as $site) {
            switch_to_blog($site);
            $this->activateOnSingleSite();
            restore_current_blog();
        }
    }

    public static function uninstall()
    {
        if (is_multisite()) {
            self::uninstallFromAllSite();
        } else {
            self::uninstallFromSingleSite();
        }
    }

    public static function uninstallFromSingleSite()
    {
        MigrationHelper::drop(self::$_drop);
    }

    public static function uninstallFromAllSite()
    {
        $sites = get_sites((['fields' => 'ids', 'network_id' => get_current_network_id()]));

        foreach ($sites as $site) {
            switch_to_blog($site);
            self::uninstallFromSingleSite();
            restore_current_blog();
        }
    }

    public function checkRequirements()
    {
        if (version_compare(PHP_VERSION, $this->_requirements['php'], '<')) {
            // Str From WP install script
            wp_die(
                esc_html(
                    sprintf(
                        // translators: 1: Current PHP version, 2: Version required by the uploaded plugin.
                        'The PHP version on your server is %1$s, however the uploaded plugin requires %2$s.',
                        PHP_VERSION,
                        $this->_requirements['php']
                    )
                ),
                esc_html('Requirements Not Met')
            );
        }

        if (version_compare(get_bloginfo('version'), $this->_requirements['wp'], '<')) {
            wp_die(
                esc_html(
                    sprintf(
                        // translators: 1: Current WordPress version, 2: Version required by the uploaded plugin.
                        'Your WordPress version is %1$s, however the uploaded plugin requires %2$s.',
                        get_bloginfo('version'),
                        $this->_requirements['wp']
                    ),
                    esc_html('Requirements Not Met')
                )
            );
        }
    }
}
