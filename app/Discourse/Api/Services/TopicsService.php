<?php

namespace App\Discourse\Api\Services;



use App\Discourse\Api\Contracts\ApiHandlerRepositoryContract;

class TopicsService
{
    const teamCategories = [
        9 => "order",
        61 => "payment",
        97 => "intern/order-intern",
        241 => "benutzeroberflaeche/auftraege-payment",
        153 => "rest-api/document",
        163 => "rest-api/order",
        165 => "rest-api/payment"
    ];

    public function getTeamStatsForAllCategories()
    {
        /** @var ApiHandlerRepositoryContract $apiHandler */
        $apiHandler = app(ApiHandlerRepositoryContract::class);

        $todayTopics = [];
        foreach (self::teamCategories as $id => $categoryName)
        {
            $todayTopics[$categoryName] = $apiHandler->getTopicsFromToday($categoryName);
        }

        return $todayTopics;
    }

}
