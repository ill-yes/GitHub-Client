<?php

namespace App\Kanbanize\Contracts;

interface ApiHandlerRepositoryContract
{
    public function getAllTasks (int $boardId);
}
