<?php

namespace App\Discourse\Api\Repositories;

use App\Discourse\Api\Contracts\ApiHandlerRepositoryContract;
use App\Discourse\Client\DiscourseClient;
use App\Discourse\Models\API\Topic;
use DateTime;

class ApiHandlerRepository implements ApiHandlerRepositoryContract
{

    /** @var DiscourseClient */
    private $client;

    public function __construct(DiscourseClient $client)
    {
        $this->client = $client;
    }

    public function getThreadsByCategory(string $category, int $page = 0) : array
    {
        $method = "GET";
        $endpoint = "/c/" . $category;
        $additionalParams = "&order=created";

        $response = $this->client->sendRequest($method, $endpoint, $page, $additionalParams);

        $topicsUnformatted = $response['topic_list']['topics'];

        $topics = [];
        foreach ($topicsUnformatted as $topic)
        {
            $topics[] = new Topic($topic);
        }

        return $topics;
    }

    public function getTopicsFromToday(string $category) : array
    {
        $todayDate = date_format(new DateTime(), 'Y-m-d');

        $allTopics = [];
        $today = true;
        $page = 0;
        do {
            $topics = $this->getThreadsByCategory($category, $page);

            /** @var Topic $lastTopicOfList */
            $lastTopicOfList = end($topics);
            $topicCreatedAt = new DateTime($lastTopicOfList->created_at);

            $allTopics = array_merge($allTopics, $topics);

            if ($topicCreatedAt != $todayDate)
            {
                $today = false;
            }
            else {
                $page++;
            }
        } while ($today);//($today);

        foreach ($allTopics as $key => $topic)
        {
            // Unset topics, which were not from today
            $topicCreatedAt = date_format(new DateTime($topic->created_at), 'Y-m-d');
            if ($topicCreatedAt != $todayDate)
            {
                unset($allTopics[$key]);
            }
        }

        return $allTopics;
    }

}
