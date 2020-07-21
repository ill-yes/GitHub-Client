<?php

namespace App\Kanbanize\Repositories;

use App\Kanbanize\Client\KanbanizeClient;
use App\Kanbanize\Constants\ApiEndpoint;
use App\Kanbanize\Contracts\KanbanizeHandlerContract;
use App\Kanbanize\Models\Task;
use Illuminate\Support\Collection;

class KanbanizeHandlerRepository implements KanbanizeHandlerContract
{
    /** @var KanbanizeClient */
    private $client;

    public function __construct()
    {
        $this->client = new KanbanizeClient();
    }

    /**
     * @param int $boardId
     * @return Collection
     */
    public function getAllTasks(int $boardId)
    {
        $body = ['boardid' => $boardId];
        $response = $this->client->sendRequest("POST",ApiEndpoint::GET_ALL_TASK, $body);

        $taskCollection = new Collection();
        foreach ($response as $responseItem)
        {
            $task = new Task();
            $task->fill($responseItem);

            $taskCollection->push($task);
        }

        return $taskCollection;
    }

    /**
     * @param int $boardId
     * @param int $taskId
     * @return Task
     */
    public function getTaskDetails(int $boardId, int $taskId)
    {
        $body = [
            'boardid' => "$boardId",
            'taskid' => "$taskId"
        ];

        $response = $this->client->sendRequest("POST", ApiEndpoint::GET_TASK_DETAILS, $body);

        $task = new Task();
        $this->set_object_vars($task, $response);

        //return new Task($response);
    }

    public function set_object_vars($object, array $vars) {
        $has = get_object_vars($object);
        foreach ($has as $name => $oldValue) {
            $object->$name = isset($vars[$name]) ? $vars[$name] : NULL;
        }
    }
}
