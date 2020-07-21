<?php

namespace App\Kanbanize\Contracts;

use App\Kanbanize\Models\Task;
use Illuminate\Support\Collection;

interface KanbanizeHandlerContract
{
    /**
     * @param int $boardId
     * @return Collection
     */
    public function getAllTasks (int $boardId);

    /**
     * @param int $boardId
     * @param int $taskId
     * @return Task
     */
    public function getTaskDetails (int $boardId, int $taskId);
}
