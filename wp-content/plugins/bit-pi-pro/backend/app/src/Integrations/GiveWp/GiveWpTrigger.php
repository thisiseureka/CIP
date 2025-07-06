<?php

namespace BitApps\PiPro\src\Integrations\GiveWp;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\Deps\BitApps\WPKit\Http\Response;
use BitApps\PiPro\src\Integrations\IntegrationHelper;
use Give_Payment;

final class GiveWpTrigger
{
    public static function isPluginActive()
    {
        return (bool) (is_plugin_active('give/give.php'));
    }

    public static function getDonationForms()
    {
        if (!self::isPluginActive()) {
            return Response::error('GiveWP plugin is not active');
        }

        global $wpdb;

        $tableName = $wpdb->prefix . 'posts';

        $donationForms = $wpdb->get_results("SELECT ID, post_title FROM {$tableName} WHERE post_type = 'give_forms' AND post_status = 'publish'");

        $donationForms = array_map(
            fn ($form) => [
                'label' => $form->post_title,
                'value' => $form->ID,
            ],
            $donationForms
        );

        return Response::success($donationForms);
    }

    // public static function getRecurringDonationForms()
    // {
    //     global $wpdb;

    //     $recurringForms = $wpdb->get_results(
    //         $wpdb->prepare(
    //             "SELECT ID, post_title FROM {$wpdb->posts}
    //     LEFT JOIN {$wpdb->prefix}give_formmeta ON ({$wpdb->posts}.ID = {$wpdb->prefix}give_formmeta.form_id)
    //     WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'give_forms' AND {$wpdb->prefix}give_formmeta.meta_key = '_give_recurring'"
    //         )
    //     );

    //     $recurringDonationForms = array_map(fn ($form) => [
    //         'label' => $form->post_title,
    //         'value' => $form->ID,
    //     ], $recurringForms);

    //     return Response::success($recurringDonationForms);
    // }

    public static function handleUserDonation($paymentId, $status)
    {
        $flows = FlowService::exists('giveWp', 'userDonation');
        if (!$flows) {
            return;
        }

        if ('publish' !== $status) {
            return;
        }

        $payment = new Give_Payment($paymentId);

        if (empty($payment)) {
            return;
        }

        $paymentExists = $payment->ID;
        if (empty($paymentExists)) {
            return;
        }

        $giveFormId = $payment->form_id;
        $userId = $payment->user_id;

        if (0 === $userId) {
            return;
        }

        $finalData = json_decode(wp_json_encode($payment), true);

        $donarUserInfo = give_get_payment_meta_user_info($paymentId);
        if ($donarUserInfo) {
            $finalData['title'] = $donarUserInfo['title'];
            $finalData['first_name'] = $donarUserInfo['first_name'];
            $finalData['last_name'] = $donarUserInfo['last_name'];
            $finalData['email'] = $donarUserInfo['email'];
            $finalData['address1'] = $donarUserInfo['address']['line1'];
            $finalData['address2'] = $donarUserInfo['address']['line2'];
            $finalData['city'] = $donarUserInfo['address']['city'];
            $finalData['state'] = $donarUserInfo['address']['state'];
            $finalData['zip'] = $donarUserInfo['address']['zip'];
            $finalData['country'] = $donarUserInfo['address']['country'];
            $finalData['donar_id'] = $donarUserInfo['donor_id'];
        }

        $finalData['give_form_id'] = $giveFormId;
        $finalData['give_form_title'] = $payment->form_title;
        $finalData['currency'] = $payment->currency;
        $finalData['give_price_id'] = $payment->price_id;
        $finalData['price'] = $payment->total;

        IntegrationHelper::handleFlowForForm($flows, $finalData, $giveFormId, 'donation-form-id');
    }
}
