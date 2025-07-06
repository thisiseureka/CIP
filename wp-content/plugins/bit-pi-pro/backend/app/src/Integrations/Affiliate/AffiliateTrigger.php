<?php

namespace BitApps\PiPro\src\Integrations\Affiliate;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class AffiliateTrigger
{
    public static function pluginActive($option = null)
    {
        if (is_plugin_active('affiliate-wp/affiliate-wp.php')) {
            return $option === 'get_name' ? 'affiliate-wp/affiliate-wp.php' : true;
        }

        return false;
    }

    public static function affiliateGetAllType()
    {
        $organizeType[] = [
            'value' => 'any',
            'label' => __('Any', 'bit-pi')
        ];
        $typeId = 1;
        foreach (affiliate_wp()->referrals->types_registry->get_types() as $type) {
            $organizeType[] = [
                'value' => $typeId,
                'label' => $type['label']
            ];
            $typeId++;
        }

        return $organizeType;
    }

    public static function newAffiliateApproved($affiliateId, $status, $oldStatus)
    {
        $flows = FlowService::exists('affiliate', 'newAffiliateApproval');
        if (!$flows) {
            return;
        }

        $userId = affwp_get_affiliate_user_id($affiliateId);

        if (!$userId) {
            return;
        }

        if ('pending' === $status) {
            return;
        }

        $affiliate = affwp_get_affiliate($affiliateId);

        get_user_by('id', $userId);

        $data = [
            'status'          => $status,
            'flat_rate_basis' => $affiliate->flat_rate_basis,
            'payment_email'   => $affiliate->payment_email,
            'rate_type'       => $affiliate->rate_type,
            'old_status'      => $oldStatus,

        ];

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    public static function userBecomesAffiliate($affiliateId, $status, $oldStatus)
    {
        if ('active' !== $status) {
            return $status;
        }

        $flows = FlowService::exists('affiliate', 'userAffiliate');
        if (!$flows) {
            return;
        }

        $userId = affwp_get_affiliate_user_id($affiliateId);

        if (!$userId) {
            return;
        }

        $affiliate = affwp_get_affiliate($affiliateId);

        get_user_by('id', $userId);

        $data = [
            'status'          => $status,
            'flat_rate_basis' => $affiliate->flat_rate_basis,
            'payment_email'   => $affiliate->payment_email,
            'rate_type'       => $affiliate->rate_type,
            'old_status'      => $oldStatus,

        ];

        IntegrationHelper::handleFlowForForm($flows, $data);
    }

    public static function affiliateMakesReferral($referralId)
    {
        $flows = FlowService::exists('affiliate', 'affiliateReferral');

        if (!$flows) {
            return;
        }

        $referral = affwp_get_referral($referralId);

        $affiliate = affwp_get_affiliate($referral->affiliate_id);

        $userId = affwp_get_affiliate_user_id($referral->affiliate_id);

        $affiliateNote = maybe_serialize(affwp_get_affiliate_meta($affiliate->affiliate_id, 'notes', true));

        $user = get_user_by('id', $userId);

        $data = [
            'affiliate_id'         => $referral->affiliate_id,
            'affiliate_url'        => maybe_serialize(affwp_get_affiliate_referral_url(['affiliate_id' => $referral->affiliate_id])),
            'referral_description' => $referral->description,
            'amount'               => $referral->amount,
            'context'              => $referral->context,
            'campaign'             => $referral->campaign,
            'reference'            => $referral->reference,
            'flat_rate_basis'      => $affiliate->flat_rate_basis,
            'account_email'        => $user->user_email,
            'payment_email'        => $affiliate->payment_email,
            'rate_type'            => $affiliate->rate_type,
            'affiliate_note'       => $affiliateNote,

        ];

        IntegrationHelper::handleFlowForForm($flows, $data, $referral->type, 'type');
    }

    public static function affiliatesReferralSpecificTypeRejected($referralId, $newStatus, $oldStatus)
    {
        $flows = FlowService::exists('affiliate', 'referralRejection');
        if (!$flows) {
            return;
        }

        if ((string) $newStatus === (string) $oldStatus || 'rejected' !== (string) $newStatus) {
            return $newStatus;
        }

        $referral = affwp_get_referral($referralId);
        $type = $referral->type;
        $userId = affwp_get_affiliate_user_id($referral->affiliate_id);
        $user = get_user_by('id', $userId);
        $affiliate = affwp_get_affiliate($referral->affiliate_id);
        $affiliateNote = maybe_serialize(affwp_get_affiliate_meta($affiliate->affiliate_id, 'notes', true));

        foreach ($flows as $flow) {
            if (\is_string($flow->flow_details)) {
                $flow->flow_details = json_decode($flow->flow_details);
                $flowDetails = $flow->flow_details;
            }
        }

        $allTypes = $flowDetails->allType;

        $selectedTypeID = $flowDetails->selectedType;

        $data = [
            'affiliate_id'         => $referral->affiliate_id,
            'affiliate_url'        => maybe_serialize(affwp_get_affiliate_referral_url(['affiliate_id' => $referral->affiliate_id])),
            'referral_description' => $referral->description,
            'amount'               => $referral->amount,
            'context'              => $referral->context,
            'campaign'             => $referral->campaign,
            'reference'            => $referral->reference,
            'status'               => $newStatus,
            'flat_rate_basis'      => $affiliate->flat_rate_basis,
            'account_email'        => $user->user_email,
            'payment_email'        => $affiliate->payment_email,
            'rate_type'            => $affiliate->rate_type,
            'affiliate_note'       => $affiliateNote,
            'old_status'           => $oldStatus,

        ];

        foreach ($allTypes as $type) {
            if ($referral->type == $type->type_key && $type->type_id == $selectedTypeID) {
                IntegrationHelper::handleFlowForForm($flows, $data);
            }
        }

        if ($selectedTypeID == 'any') {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }

    public static function affiliatesReferralSpecificTypePaid($referralId, $newStatus, $oldStatus)
    {
        $flows = FlowService::exists('affiliate', 'referralPayment');
        if (!$flows) {
            return;
        }

        if ((string) $newStatus === (string) $oldStatus || 'paid' !== (string) $newStatus) {
            return $newStatus;
        }

        $referral = affwp_get_referral($referralId);
        $type = $referral->type;
        $userId = affwp_get_affiliate_user_id($referral->affiliate_id);
        $user = get_user_by('id', $userId);
        $affiliate = affwp_get_affiliate($referral->affiliate_id);
        $affiliateNote = maybe_serialize(affwp_get_affiliate_meta($affiliate->affiliate_id, 'notes', true));

        foreach ($flows as $flow) {
            if (\is_string($flow->flow_details)) {
                $flow->flow_details = json_decode($flow->flow_details);
                $flowDetails = $flow->flow_details;
            }
        }

        $allTypes = $flowDetails->allType;

        $selectedTypeID = $flowDetails->selectedType;

        $data = [
            'affiliate_id'         => $referral->affiliate_id,
            'affiliate_url'        => maybe_serialize(affwp_get_affiliate_referral_url(['affiliate_id' => $referral->affiliate_id])),
            'referral_description' => $referral->description,
            'amount'               => $referral->amount,
            'context'              => $referral->context,
            'campaign'             => $referral->campaign,
            'reference'            => $referral->reference,
            'status'               => $newStatus,
            'flat_rate_basis'      => $affiliate->flat_rate_basis,
            'account_email'        => $user->user_email,
            'payment_email'        => $affiliate->payment_email,
            'rate_type'            => $affiliate->rate_type,
            'affiliate_note'       => $affiliateNote,
            'old_status'           => $oldStatus,

        ];

        foreach ($allTypes as $type) {
            if ($referral->type == $type->type_key && $type->type_id == $selectedTypeID) {
                IntegrationHelper::handleFlowForForm($flows, $data);
            }
        }

        if ($selectedTypeID == 'any') {
            IntegrationHelper::handleFlowForForm($flows, $data);
        }
    }
}
