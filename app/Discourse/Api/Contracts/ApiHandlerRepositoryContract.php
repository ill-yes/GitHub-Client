<?php

namespace App\Discourse\Api\Contracts;

interface ApiHandlerRepositoryContract
{
    public function getThreadsByCategory(string $category, int $page = 1) : array;

    public function getTopicsFromToday(string $category) : array;

}
