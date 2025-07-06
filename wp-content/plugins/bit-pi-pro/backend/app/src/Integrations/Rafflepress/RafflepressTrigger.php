<?php

namespace BitApps\PiPro\src\Integrations\Rafflepress;

// Prevent direct script access
if (!defined('ABSPATH')) {
    exit;
}


use BitApps\Pi\Services\FlowService;
use BitApps\PiPro\src\Integrations\IntegrationHelper;

final class RafflepressTrigger
{
    public static function handleNewPersonEntry($giveawayDetails)
    {
        $giveawayId = $giveawayDetails['giveaway_id'];

        $giveawayName = $giveawayDetails['giveaway']->name;

        $starts = $giveawayDetails['giveaway']->starts;

        $ends = $giveawayDetails['giveaway']->ends;

        $active = $giveawayDetails['giveaway']->active;

        $name = $giveawayDetails['name'];

        $firstName = $giveawayDetails['first_name'];

        $lastName = $giveawayDetails['last_name'];

        $email = $giveawayDetails['email'];

        $prizeName = $giveawayDetails['settings']->prizes[0]->name;

        $prizeDescription = $giveawayDetails['settings']->prizes[0]->description;

        $prizeImage = $giveawayDetails['settings']->prizes[0]->image;

        $finalData = [
            'giveaway_id'       => $giveawayId,
            'giveaway_name'     => $giveawayName,
            'starts'            => $starts,
            'ends'              => $ends,
            'active'            => $active,
            'name'              => $name,
            'first_name'        => $firstName,
            'last_name'         => $lastName,
            'email'             => $email,
            'prize_name'        => $prizeName,
            'prize_description' => $prizeDescription,
            'prize_image'       => $prizeImage,
        ];

        $flows = FlowService::exists('rafflepress', 'newRaffleEntry');

        if (!$flows) {
            return;
        }

        IntegrationHelper::handleFlowForForm($flows, $finalData);
    }
}
