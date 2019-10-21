<?php

namespace App\Console\Commands;

use App\Github\Client\CallManager;
use App\Models\Repository;
use Illuminate\Console\Command;

class RepositoriesCron extends Command
{
    protected $signature = 'cron:repositories';

    protected $description = 'Running the cron for fetching repositories';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info("Starting cron!");
        $timeStart = microtime(true);

        $this->loadAndStoreRepositories();

        $timeEnd = microtime(true);
        $timeRun = $timeEnd - $timeStart;

        $this->info(Repository::count() . " new repositories: Completed after " . $timeRun . " seconds!");
    }

    public function loadAndStoreRepositories()
    {
        $callManager = new CallManager();
        $branches = $callManager->getOrgaRepo();

        Repository::truncate();
        foreach ($branches as $branchName => $value)
        {
            Repository::create([
                'name' => $branchName
            ]);
        }
    }
}
