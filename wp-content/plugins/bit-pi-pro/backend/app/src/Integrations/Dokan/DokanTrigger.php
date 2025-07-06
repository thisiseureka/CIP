<?php

namespace BitApps\PiPro\src\Integrations\Dokan;

// Prevent direct script access
if (!\defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

// use WeDevs\Dokan\Vendor\Vendor;

final class DokanTrigger
{
    public static function handleVendorAdd($vendorId, $data)
    {
        if (empty($vendorId) || empty($data)) {
            return false;
        }

        $flows = FlowService::exists('dokan', 'vendorAdd');

        if (empty($flows) || !$flows) {
            return;
        }

        $vendorAddData = DokanHelper::formatVendorData($vendorId, $data);

        IntegrationHelper::handleFlowForForm($flows, $vendorAddData);
    }

    public static function handleVendorUpdate($vendorId, $data)
    {
        if (empty($vendorId) || empty($data)) {
            return false;
        }

        $flows = FlowService::exists('dokan', 'vendorUpdate');

        if (empty($flows) || !$flows) {
            return;
        }

        $vendorUpdateData = DokanHelper::formatVendorData($vendorId, $data);

        IntegrationHelper::handleFlowForForm($flows, $vendorUpdateData);
    }

    public static function handleVendorDelete($vendorId)
    {
        if (is_plugin_active('dokan-lite/dokan.php')) {
            $userData = get_userdata($vendorId);

            if (!empty($userData) && \in_array('seller', $userData->roles)) {
                $vendor = dokan()->vendor->get($vendorId)->to_array();

                if (empty($vendor) || is_wp_error($vendor)) {
                    return;
                }

                $flows = FlowService::exists('dokan', 'vendorDelete');

                if (empty($flows) || !$flows) {
                    return;
                }

                $vendorDeleteData = DokanHelper::formatVendorData($vendorId, $vendor);

                IntegrationHelper::handleFlowForForm($flows, $vendorDeleteData);
            }
        }
    }

    public static function dokanRefundRequest($refund)
    {
        if (!$refund) {
            return;
        }

        $flows = FlowService::exists('dokan', 'refundRequest');

        if (empty($flows) || !$flows) {
            return;
        }

        $refundRequestData = DokanHelper::formatRefundData($refund);

        if (!empty($refundRequestData)) {
            IntegrationHelper::handleFlowForForm($flows, $refundRequestData);
        }
    }

    public static function dokanRefundApproved($refundData)
    {
        if (!$refundData) {
            return;
        }

        $flows = FlowService::exists('dokan', 'refundApproved');

        if (empty($flows) || !$flows) {
            return;
        }

        $refundApprovedData = DokanHelper::formatRefundData($refundData);

        if (!empty($refundApprovedData)) {
            IntegrationHelper::handleFlowForForm($flows, $refundApprovedData);
        }
    }

    public static function dokanRefundCancelled($refundData)
    {
        if (!$refundData) {
            return;
        }

        $flows = FlowService::exists('dokan', 'refundCancelled');

        if (empty($flows) || !$flows) {
            return;
        }

        $refundCancelledData = DokanHelper::formatRefundData($refundData);

        if (!empty($refundCancelledData)) {
            IntegrationHelper::handleFlowForForm($flows, $refundCancelledData);
        }
    }

    public static function dokanUserToVendor($userId)
    {
        if (empty($userId)) {
            return;
        }

        $flows = FlowService::exists('dokan', 'newSellerToVendor');

        if (empty($flows) || !$flows) {
            return;
        }

        $userToVendorData = DokanHelper::formatUserToVendorData($userId);

        if (!empty($userToVendorData)) {
            IntegrationHelper::handleFlowForForm($flows, $userToVendorData);
        }
    }

    public static function dokanWithdrawRequest($userId, $amount, $method)
    {
        if (empty($userId) || empty($amount) || empty($method)) {
            return;
        }

        $flows = FlowService::exists('dokan', 'withdrawRequest');

        if (empty($flows) || !$flows) {
            return;
        }

        $withdrawRequestData = DokanHelper::formatWithdrawRequestData($userId, $amount, $method);

        if (!empty($withdrawRequestData)) {
            IntegrationHelper::handleFlowForForm($flows, $withdrawRequestData);
        }
    }
}
