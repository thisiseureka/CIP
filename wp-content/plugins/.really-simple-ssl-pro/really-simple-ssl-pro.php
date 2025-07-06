<?php
/**
 * Plugin Name: Really Simple Security Pro
 * Plugin URI: https://really-simple-ssl.com
 * Description: Simple and performant security
 * Version: 9.3.4
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Author: Really Simple Plugins
 * Author URI: https://really-simple-plugins.com
 * License: GPL2
 * Text Domain: really-simple-ssl
 * Domain Path: /languages
 */

/*  Copyright 2023  Really Simple Plugins BV  (email : support@really-simple-ssl.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( "you do not have access to this page!" );
update_site_option( 'rsssl_pro_license_key', 'activated' );
update_site_option( 'rsssl_pro_license_status', 'valid' );
update_site_option( 'rsssl_pro_license_activation_limit', '999' );
update_site_option( 'rsssl_pro_license_activations_left', '999' );
update_site_option( 'rsssl_pro_license_expires', 'lifetime' );
define( 'rsssl_pro_ms_version', true );
if ( ! function_exists( 'rsssl_pro_activation_check' ) ) {
    function rsssl_pro_activation_check() {
        update_option( 'rsssl_activation', true, false );
        update_option( 'rsssl_run_activation', true, false );
        update_option( 'rsssl_show_onboarding', true, false );
        set_transient( 'rsssl_redirect_to_settings_page', true, HOUR_IN_SECONDS );
        do_action('rsssl_update_rules');
    }

    function rsssl_check_reactivation() {
        if ( ! get_option( 'rsssl_activation_handled' ) ) {
            rsssl_pro_activation_check();
            update_option( 'rsssl_activation_handled', true, false );
        }
    }

    register_activation_hook( __FILE__, function () {
        delete_option( 'rsssl_activation_handled' );
    });

    // Check for reactivation logic during initialization
    add_action( 'plugins_loaded', 'rsssl_check_reactivation' );
}

if ( class_exists('REALLY_SIMPLE_SSL') ) {
    // Normally we can assume the function exists as class REALLY_SIMPLE_SSL
    // also exists. But as this function is new we should be extra sure.
    if (!function_exists('rsssl_deactivate_alternate')) {
        $rsssl_path = trailingslashit( plugin_dir_path( __FILE__ ) );
        require_once $rsssl_path . 'functions.php';
    }

    rsssl_deactivate_alternate('free');
} else {
    class REALLY_SIMPLE_SSL {
        private static $instance;
        public $front_end;
        public $mixed_content_fixer;
        public $multisite;
        public $cache;
        public $server;
        public $admin;
        public $progress;
        public $onboarding;
        public $placeholder;
        public $certificate;
        public $wp_cli;
        public $mailer_admin;
        public $site_health;
        public $vulnerabilities;

        # Pro
        public $pro_admin;
        public $support;
        public $licensing;
        public $csp_backend;
        public $headers;
        public $scan;
        public $importer;

        private function __construct() {
            if ( isset( $_GET['rsssl_apitoken'] ) && $_GET['rsssl_apitoken'] == get_option( 'rsssl_csp_report_token' ) ) {
                if ( ! defined( 'RSSSL_LEARNING_MODE' ) ) {
                    define( 'RSSSL_LEARNING_MODE', true );
                }
            }
        }

        public static function instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof REALLY_SIMPLE_SSL ) ) {
                self::$instance = new REALLY_SIMPLE_SSL;
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->front_end           = new rsssl_front_end();
                self::$instance->mixed_content_fixer = new rsssl_mixed_content_fixer();

                if ( is_multisite() ) {
                    self::$instance->multisite = new rsssl_multisite();
                }
                if ( rsssl_admin_logged_in() ) {
                    self::$instance->cache        = new rsssl_cache();
                    self::$instance->placeholder  = new rsssl_placeholder();
                    self::$instance->server       = new rsssl_server();
                    self::$instance->admin        = new rsssl_admin();
                    self::$instance->mailer_admin = new rsssl_mailer_admin();
                    self::$instance->onboarding   = new rsssl_onboarding();
                    self::$instance->progress     = new rsssl_progress();
                    self::$instance->certificate  = new rsssl_certificate();
                    self::$instance->site_health  = new rsssl_site_health();

                    if ( defined( 'WP_CLI' ) && WP_CLI ) {
                        self::$instance->wp_cli = new rsssl_wp_cli();
                    }

                    # Pro
                    self::$instance->licensing   = new rsssl_licensing();
                    self::$instance->pro_admin   = new rsssl_pro_admin();
                    self::$instance->headers     = new rsssl_headers();
                    self::$instance->scan        = new rsssl_scan();
                    self::$instance->importer    = new rsssl_importer();
                    self::$instance->support     = new rsssl_support();
                    self::$instance->csp_backend = new rsssl_csp_backend();
                }
                self::$instance->hooks();
                self::$instance->load_translation();
            }

            return self::$instance;
        }

        private function setup_constants() {

            define( 'rsssl_url', plugin_dir_url( __FILE__ ) );
            define( 'rsssl_path', trailingslashit( plugin_dir_path( __FILE__ ) ) );
            define( 'rsssl_template_path', trailingslashit( plugin_dir_path( __FILE__ ) ) . 'grid/templates/' );
            define( 'rsssl_plugin', plugin_basename( __FILE__ ) );

            if ( ! defined( 'rsssl_file' ) ) {
                define( 'rsssl_file', __FILE__ );
            }

			define( 'rsssl_version', '9.3.4' );

            define( 'rsssl_pro', true );

            define( 'rsssl_le_cron_generation_renewal_check', 20 );
            define( 'rsssl_le_manual_generation_renewal_check', 15 );

            if ( ! defined( 'REALLY_SIMPLE_SSL_URL' ) ) {
                define( 'REALLY_SIMPLE_SSL_URL', 'https://really-simple-ssl.com' );
            }

            define( 'RSSSL_ITEM_ID', 860 );
            define( 'RSSSL_ITEM_NAME', 'Really Simple Security Pro' );
            define( 'RSSSL_ITEM_VERSION', rsssl_version );
        }

        private function includes() {

            require_once( rsssl_path . 'class-front-end.php' );
            require_once( rsssl_path . 'functions.php' );
            require_once( rsssl_path . 'class-mixed-content-fixer.php' );
            if ( defined( 'WP_CLI' ) && WP_CLI ) {
                require_once( rsssl_path . 'class-wp-cli.php' );
            }

            if ( is_multisite() ) {
                require_once( rsssl_path . 'class-multisite.php' );
            }

            require_once( rsssl_path . 'pro/includes.php' );

            require_once( rsssl_path . 'lets-encrypt/cron.php' );
            require_once( rsssl_path . 'security/security.php' );

            if ( rsssl_admin_logged_in() ) {
                require_once( rsssl_path . 'compatibility.php' );
                require_once( rsssl_path . 'upgrade.php' );
                require_once( rsssl_path . 'settings/settings.php' );
                require_once( rsssl_path . 'modal/modal.php' );
                require_once( rsssl_path . 'onboarding/class-onboarding.php' );
                require_once( rsssl_path . 'placeholders/class-placeholder.php' );
                require_once( rsssl_path . 'class-admin.php' );
                require_once( rsssl_path . 'mailer/class-mail-admin.php' );
                require_once( rsssl_path . 'class-cache.php' );
                require_once( rsssl_path . 'class-server.php' );
                require_once( rsssl_path . 'progress/class-progress.php' );
                require_once( rsssl_path . 'class-certificate.php' );
                require_once( rsssl_path . 'class-site-health.php' );
                require_once( rsssl_path . 'mailer/class-mail.php' );
                require_once( rsssl_path . 'lets-encrypt/letsencrypt.php' );
                if ( isset( $_GET['install_pro'] ) ) {
                    require_once( rsssl_path . 'upgrade/upgrade-to-pro.php' );
                }
            }
            require_once( rsssl_path . '/rsssl-auto-loader.php' );
        }
        private function hooks() {
            add_action( 'wp_loaded', array( self::$instance->front_end, 'force_ssl' ), 20 );
            if ( rsssl_admin_logged_in() ) {
                add_action( 'plugins_loaded', array( self::$instance->admin, 'init' ), 10 );
            }

        }

        /**
         * Load plugin translations.
         *
         * @since 1.0.0
         *
         * @return void
         */
        private function load_translation(): void {
            add_action('init', function() {
                load_plugin_textdomain('really-simple-ssl', false, dirname(plugin_basename(__FILE__)) . '/languages/');
            });
        }
    }
}

if ( !defined('RSSSL_DEACTIVATING_ALTERNATE')
    && !function_exists('RSSSL')
) {
    function RSSSL() {
        return REALLY_SIMPLE_SSL::instance();
    }

    add_action( 'plugins_loaded', 'RSSSL', 8 );
}

if ( ! function_exists( 'rsssl_add_manage_security_capability' ) ) {
    /**
     * Add a user capability to WordPress and add to admin and editor role
     */
    function rsssl_add_manage_security_capability() {
        $role = get_role( 'administrator' );
        if ( $role && ! $role->has_cap( 'manage_security' ) ) {
            $role->add_cap( 'manage_security' );
        }
    }

    register_activation_hook( __FILE__, 'rsssl_add_manage_security_capability' );
}

if ( ! function_exists( 'rsssl_user_can_manage' ) ) {
    /**
     * Check if user has required capability
     * @return bool
     */
    function rsssl_user_can_manage() {
        if ( current_user_can( 'manage_security' ) ) {
            return true;
        }

        #allow wp-cli access to activate ssl
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            return true;
        }

        return false;
    }
}

if ( ! function_exists( 'rsssl_admin_logged_in' ) ) {
    function rsssl_admin_logged_in() {
        $wpcli = defined( 'WP_CLI' ) && WP_CLI;

        return ( is_admin() && rsssl_user_can_manage() ) || rsssl_is_logged_in_rest() || wp_doing_cron() || $wpcli || defined( 'RSSSL_DOING_SYSTEM_STATUS' ) || defined( 'RSSSL_LEARNING_MODE' );
    }
}

if ( ! function_exists( 'rsssl_is_logged_in_rest' ) ) {
    function rsssl_is_logged_in_rest() {
        $valid_request = isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '/reallysimplessl/v1/' ) !== false;
        if ( ! $valid_request ) {
            return false;
        }

        return is_user_logged_in();
    }
}