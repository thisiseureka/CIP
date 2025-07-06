<?php

namespace BitApps\PiPro\Services;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


\define('strict_types', 1); // phpcs:ignore

use BitApps\PiPro\Config;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Client\HttpClient;
use Exception;

class CronService
{
    public const BASE_URL = 'https://wp-api.bitapps.pro/public/';

    public const WP_CRON_PATH = '/wp-cron.php';

    public static function createOrDeleteCloudCron(bool $useCloudCron)
    {
        if ($useCloudCron) {
            return self::createCloudCron();
        }

        return self::deleteCloudCron();
    }

    public static function createCloudCron()
    {
        return self::cloudCronRequest('wp-cron-activate');
    }

    public static function getCloudCronStatus()
    {
        return self::cloudCronRequest('get-cron-status');
    }

    public static function deleteCloudCron()
    {
        return self::cloudCronRequest('wp-cron-delete');
    }

    public static function cloudCronRequest(string $slug)
    {
        try {
            $url = self::BASE_URL . $slug;

            $body = [
                'link'       => Config::get('SITE_URL') . static::WP_CRON_PATH,
                'licenseKey' => base64_encode(Config::getOption('license_data')['key']),
            ];

            $httpClient = new HttpClient();

            return $httpClient->request($url, 'POST', $body);
        } catch (Exception $e) {
            return (object) [
                'success'  => false,
                'response' => 'Error making external cron request',
            ];
        }
    }
}
