<?php

namespace BitApps\PiPro;

use BitApps\PiPro\Deps\BitApps\WPKit\Hooks\Hooks;

if (!\defined('ABSPATH')) {
    exit;
}

class PluginDependencyHandler
{
    public static function checkDependencyForProPlugin()
    {
        Hooks::addAction(
            'admin_notices',
            function () {
                $installedPlugins = get_plugins();

                $freePluginFilePath = Config::FREE_PLUGIN_SLUG . '/' . Config::FREE_PLUGIN_SLUG . '.php';

                if (isset($installedPlugins[$freePluginFilePath])) {
                    $activationLink = wp_nonce_url(
                        self_admin_url('plugins.php?action=activate&plugin=' . $freePluginFilePath),
                        'activate-plugin_' . $freePluginFilePath
                    );

                    $actionText = 'Activate the Plugin Here';
                } else {
                    $installationLink = wp_nonce_url(
                        self_admin_url('update.php?action=install-plugin&plugin=' . Config::FREE_PLUGIN_SLUG),
                        'install-plugin_' . Config::FREE_PLUGIN_SLUG
                    );

                    $actionText = __('Install the Plugin Now by Clicking Here', 'bit-pi-pro'); // phpcs:ignore
                }

                $message = Config::TITLE . ' Plugin is dependent on ' . Config::FREE_PLUGIN_TITLE . ' Base Plugin, <b><a href="'
                . ($activationLink ?? $installationLink) . '">' . $actionText . '</a></b>';

                printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr('notice notice-error'), $message);
            }
        );
    }
}
