<?php

namespace App\Kanbanize\Services;

use App\Chatbot\Contracts\ChatInteractionContract;
use App\Kanbanize\Contracts\KanbanizeHandlerContract;
use App\Kanbanize\Models\Task;
use DateTime;
use Illuminate\Support\Collection;

class KanbanizeToChatService
{
    public function sendLatestTasksToChat(int $mins = 10)
    {
        /** @var KanbanizeHandlerContract $kanbanizeHandler */
        $kanbanizeHandler = app(KanbanizeHandlerContract::class);
        /** @var Collection $tasks */
        $tasks = $kanbanizeHandler->getAllTasks(50);

        $latestTasks = new Collection();

        /** @var Task $value */
        foreach ($tasks as $key => $value)
        {
            $createDateTask = DateTime::createFromFormat("Y-m-d H:i:s", $value->createdat);
            $currentDate = new DateTime();
            $dif = date_diff($createDateTask, $currentDate);

            // Last tasks (10 mins)
            if($dif->days <= 4 ||  $dif->i <= $mins)
            {
                $latestTasks->push($value);
            }
        }

        /** @var ChatInteractionContract $chatInteraction */
        $chatInteraction = app(ChatInteractionContract::class);

        /** @var Task $latestTask */
        foreach ($latestTasks as $latestTask)
        {
            $msg = "Neuer Bug:\t*" . $latestTask->title . "*\n";
            $msg .= "\n";
            $msg .= "Aufgenommen von:\t" . $latestTask->reporter . "\n";
            $msg .= "Aufgenommen am:\t" .  date("H:i d.m.Y", strtotime($latestTask->createdat)) ."\n\n";
            $msg .= "```Beschreibung: " . $latestTask->description . "```";

            $chatInteraction->sendText($msg);
        }

    }
}
