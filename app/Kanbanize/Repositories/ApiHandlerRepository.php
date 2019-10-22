<?php

namespace App\Kanbanize\Repositories;

use App\Kanbanize\Client\KanbanizeClient;
use App\Kanbanize\Constants\ApiEndpoint;
use App\Kanbanize\Contracts\ApiHandlerRepositoryContract;

class ApiHandlerRepository implements ApiHandlerRepositoryContract
{
    private $client;

    public function __construct()
    {
        $this->client = new KanbanizeClient();
    }

    public function getAllTasks(int $boardId)
    {
        $body = ['boardId' => $boardId];
        $response = $this->client->sendRequest(ApiEndpoint::GET_ALL_TASK, $body);

        //TODO ilyestascou, 2019-10-21: Map response to model
        return $response;

    }
}
