<?php

namespace App\Kanbanize\Models;

class Task
{
    public $taskid; //String
    public $position; //String
    public $type; //String
    public $assignee; //String
    public $title; //String
    public $description; //String
    public $priority; //String
    public $deadline; //object
    public $extlink; //String
    public $columnid; //String
    public $laneid; //String
    public $leadtime; //int
    public $blocked; //int
    public $blockedreason; //object
    public $columnname; //String
    public $lanename; //String
    public $boardparent; //String
    public $archivedat; //object
    public $reporter; //String
    public $createdat; //String
    public $updatedat; //String
    public $customfields;

    public function fill(array $data)
    {
        $vars = get_object_vars($this);
        foreach ($vars as $name => $oldValue)
        {
            $this->$name = isset($data[$name]) ? $data[$name] : null;
        }
    }
}
