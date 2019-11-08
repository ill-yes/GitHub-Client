<?php

namespace App\Discourse\Api\Contracts;

interface ApiHandlerRepositoryContract
{
    public function getThreadsByCategory($category, $page = 1);

    public function getTopicsFromToday(string $category);

}
