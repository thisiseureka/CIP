<?php

namespace BitApps\PiPro;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


/*
 * Main class for the plugin.
 *
 * @since 1.0.0-alpha
 */

use BitApps\Pi\Config as freeConfig;
use BitApps\PiPro\Config as proConfig;
use BitApps\PiPro\Deps\BitApps\WPKit\Hooks\Hooks;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\RequestType;
use BitApps\PiPro\Deps\BitApps\WPKit\Migration\MigrationHelper;
use BitApps\PiPro\Deps\BitApps\WPKit\Utils\Capabilities;
use BitApps\PiPro\HTTP\Middleware\AdminCheckerMiddleware;
use BitApps\PiPro\HTTP\Middleware\NonceCheckerMiddleware;
use BitApps\PiPro\Providers\HookProvider;
use BitApps\PiPro\Providers\InstallerProvider;
use BitApps\PiPro\Utils\PluginCommonConfig;
use BitApps\PiPro\Utils\ProPluginUpdater;
use BitApps\PiPro\Views\Layout;

final class Plugin
{
    private const FREE_PLUGIN_LOADED_HOOK = 'bit_pi_loaded';

    /**
     * Main instance of the plugin.
     *
     * @since 1.0.0-alpha
     *
     * @var Plugin|null
     */
    private static $_instance;

    private $_registeredMiddleware = [];

    /**
     * Initialize the Plugin with hooks.
     */
    public function __construct()
    {
        $this->registerInstaller();

        Hooks::addAction('plugins_loaded', [$this, 'loaded'], 11, 1);
    }

    public function registerInstaller()
    {
        $installerProvider = new InstallerProvider();
        $installerProvider->register();
    }

    /**
     * Load the plugin.
     */
    public function loaded()
    {
        if (!did_action(self::FREE_PLUGIN_LOADED_HOOK)) {
            return;
        }

        Hooks::doAction(Config::withPrefix('loaded'));
        Hooks::addAction('init', [$this, 'registerProviders'], 8);
        Hooks::addFilter('plugin_action_links_' . Config::get('BASENAME'), [$this, 'actionLinks']);
        $this->maybeMigrateDB();
        $this->setPluginCommonConfig();
    }

    public function setPluginCommonConfig()
    {
        PluginCommonConfig::setFreePluginVersion(freeConfig::VERSION);
        PluginCommonConfig::setProPluginVersion(proConfig::VERSION);
        PluginCommonConfig::setFreePluginSlug(freeConfig::SLUG);
        PluginCommonConfig::setProPluginSlug(proConfig::SLUG);
        PluginCommonConfig::setFreePluginPrefix(freeConfig::VAR_PREFIX);
        PluginCommonConfig::setProPluginPrefix(proConfig::VAR_PREFIX);
        PluginCommonConfig::setApiEndPoint('https://wp-api.bitapps.pro');
    }

    public function middlewares()
    {
        return [
            'nonce'   => NonceCheckerMiddleware::class,
            'isAdmin' => AdminCheckerMiddleware::class,
        ];
    }

    public function getMiddleware($name)
    {
        if (isset($this->_registeredMiddleware[$name])) {
            return $this->_registeredMiddleware[$name];
        }

        $middlewares = $this->middlewares();

        if (isset($middlewares[$name]) && class_exists($middlewares[$name]) && method_exists($middlewares[$name], 'handle')) {
            $this->_registeredMiddleware[$name] = new $middlewares[$name]();
        } else {
            return false;
        }

        return $this->_registeredMiddleware[$name];
    }

    /**
     * Instantiate the Provider class.
     */
    public function registerProviders()
    {
        global $wp_version;

        if (version_compare($wp_version, '6.5', '<') && !did_action(Config::FREE_PLUGIN_PREFIX . 'loaded')) {
            return PluginDependencyHandler::checkDependencyForProPlugin();
        }

        if (RequestType::is('admin')) {
            new Layout();
            new ProPluginUpdater();
        }

        new HookProvider();
    }

    /**
     * Plugin action links.
     *
     * @param array $links Array of links
     *
     * @return array
     */
    public function actionLinks($links)
    {
        $linksToAdd = Config::get('PLUGIN_PAGE_LINKS');
        foreach ($linksToAdd as $link) {
            $links[] = '<a href="' . $link['url'] . '">' . $link['title'] . '</a>';
        }

        return $links;
    }

    public static function maybeMigrateDB()
    {
        if (!Capabilities::check('manage_options')) {
            return;
        }

        if (version_compare(Config::getOption('db_version'), Config::DB_VERSION, '<')) {
            MigrationHelper::migrate(InstallerProvider::migration());
        }
    }

    /**
     * Retrieves the main instance of the plugin.
     *
     * @since 1.0.0-alpha
     *
     * @return Plugin plugin main instance
     */
    public static function instance()
    {
        return static::$_instance;
    }

    /**
     * Loads the plugin main instance and initializes it.
     *
     * @return bool True if the plugin main instance could be loaded, false otherwise
     *
     * @since 1.0.0-alpha
     */
    public static function load()
    {
        if (static::$_instance !== null) {
            return false;
        }

        static::$_instance = new self();

        return true;
    }
}
