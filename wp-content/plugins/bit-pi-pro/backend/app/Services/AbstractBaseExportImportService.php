<?php

namespace BitApps\PiPro\Services;

use BitApps\PiPro\Deps\BitApps\WPKit\Http\Request\Request;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}

\define('strict_types', 1); // phpcs:ignore

/**
 * AbstractBaseExportImportService.
 *
 * This abstract class provides a base structure for implementing export and import functionality.
 * It enforces the implementation of `import` and `export` methods in derived classes and provides
 * a utility method for downloading data as a file.
 */
abstract class AbstractBaseExportImportService
{
    /**
     * Import data from a request.
     *
     * This abstract method must be implemented by any class extending this abstract class.
     * It handles the logic for importing data.
     *
     * @param Request $request the HTTP request object containing the data to be imported
     *
     * @return mixed the return type depends on the implementation in the derived class
     */
    abstract public function import(Request $request);

    /**
     * Export data to a request.
     *
     * This abstract method must be implemented by any class extending this abstract class.
     * It handles the logic for exporting data.
     *
     * @param Request $request the HTTP request object containing the data to be exported
     *
     * @return mixed the return type depends on the implementation in the derived class
     */
    abstract public function export(Request $request);

    /**
     * Download data as a file.
     *
     * This method sets the appropriate headers for file download and outputs the data in JSON format.
     * It is a utility method that can be used by derived classes to handle file downloads.
     *
     * @param mixed  $data     The data to be downloaded. It will be encoded as JSON.
     * @param string $fileName The name of the file to be downloaded. Defaults to 'blueprint.json'.
     */
    protected function downloadAsFile($data, $fileName = 'blueprint.json')
    {
        header('Content-Type: application/force-download');
        header('Content-Type: application/octet-stream');
        header('Content-Type: application/download');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Description: File Transfer');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Transfer-Encoding: binary ');
        flush();
        echo wp_json_encode($data); // phpcs:ignore Generic.PHP.ForbiddenFunctions.FoundWithAlternative

        exit;
    }
}
