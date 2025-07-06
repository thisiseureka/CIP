<?php

namespace BitApps\PiPro\src\Integrations\HappyForm;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Config as FreePluginConfig;

class HappyFormHelper
{
    public static function getAttachementUrlLinks($val)
    {
        $img = maybe_unserialize($val);

        $hashIds = array_filter(array_values($img));

        $attachments = happyforms_get_attachment_Trigger()->get(
            ['hash_id' => $hashIds]
        );

        $attachmentIds = wp_list_pluck($attachments, 'ID');

        $links = array_map('wp_get_attachment_url', $attachmentIds);

        return implode(', ', $links);
    }

    public static function saveImageToHappyFormDir($base64Img, $title)
    {
        $uploadDirPath = FreePluginConfig::get('UPLOAD_BASE_DIR') . '/bihappy';

        if (!is_dir($uploadDirPath)) {
            mkdir($uploadDirPath, 0700);
        }

        $img = str_replace('data:image/png;base64,', '', $base64Img);

        $img = str_replace(' ', '+', $img);

        $decoded = base64_decode($img);

        $filename = $title . '.png';

        $hashedFilename = md5($filename . microtime()) . '_' . $filename;

        // Save the image in the uploads directory.
        $uploadFile = file_put_contents($uploadDirPath . '/' . $hashedFilename, $decoded);

        if ($uploadFile) {
            return $uploadDirPath . '/' . $hashedFilename;
        }

        return $base64Img;
    }
}
