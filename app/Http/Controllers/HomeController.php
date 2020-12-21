<?php

namespace App\Http\Controllers;

use App\Charts\StatsChart;
use App\Chatbot\Contracts\ChatInteractionContract;
use App\Discourse\Api\Services\TopicsService;
use App\Github\Client\CallManager;
use App\Github\Models\Pullrequest;
use App\Github\Models\Repository;
use App\Github\Services\FilterCallData;
use App\Kanbanize\Contracts\KanbanizeHandlerContract;
use App\Kanbanize\Services\KanbanizeToChatService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Khill\Lavacharts\Charts\PieChart;
use Khill\Lavacharts\Lavacharts;

class HomeController extends Controller
{

    public function __construct()
    {
        // TODO: Auth wieder aktivieren
        //$this->middleware('auth');
    }

    /**
     * Returns the view for the "branches"-tab
     * @return View
     */
    public function branchView():View
    {
        /** @var KanbanizeToChatService $kanbanize */
        $kanbanize = app(KanbanizeToChatService::class);
        //$kanbanize->sendLatestTasksToChat();

        if (Repository::count() > 0)
        {
            return view('pages.branch', [
                'repositories' => Repository::all(['name'])
            ]);
        }
        else {
            return view('pages.branch', [
                'error' => "No list of repositories available!"
            ] );
        }
    }

    /**
     * Request logic for "branches"-tab
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|View
     */
    public function deadBranchesCall(Request $request)
    {
        $repository = (string) $request->get('repository');
        $amountOfDays = (int) $request->get('days');

        if (!isset($repository) || !isset($amountOfDays))
        {
            return view('pages.branch', [
                'error' => "Repository or days not set!"
            ]);
        }

        $callManager = new CallManager();
        $branches = $callManager->getBranches($repository);
        $pullRequests = $callManager->getPullRequests($repository, $amountOfDays);

        if (isset($pullRequests) && isset($branches))
        {
            $filteredBranches = FilterCallData::filterBranchesWithPullRequests($pullRequests, $branches);

            return response($filteredBranches);
        }
        else
        {
            return view('pages.branch', [
                'error' => "Pull Requests or Branches - Call failed!"
            ]);
        }

    }

    /**
     * Returns the view for the "pr-location"-tab
     * @return View
     */
    public function prLocationView(): View
    {
       if (Pullrequest::count() > 0)
        {
            return view('pages.pr-location', [
                'lastUpdate' => Carbon::parse(Pullrequest::first()->created_at)->format('l - H:i, d.m.Y'),
                'pullRequests' => Pullrequest::all()
            ]);
        }
        else
        {
            return view('pages.pr-location', [
                'lastUpdate' => "Never",
                'error' => 'Database empty!'
            ]);
        }
    }

    public function forumStatsView(): View
    {
        $api = new TopicsService();
        $categoryTopics = $api->getTeamStatsForAllCategories();

        $amount = [];
        foreach ($categoryTopics as $category => $topics)
        {
            $amount[$category] = count($topics);
        }

        $labels = array_keys($amount);
        $stats = array_values($amount);

        $lava = new Lavacharts;
        $reasons = $lava->DataTable();

        $reasons
            ->addStringColumn('Categories')
            ->addNumberColumn('Threads');
        foreach ($amount as $key => $counts)
        {
            $reasons->addRow([$key, $counts]);
        }
        /*$reasons->addStringColumn('Reasons')
            ->addNumberColumn('Percent')
            ->addRow(array('Check Reviews', 5))
            ->addRow(array('Watch Trailers', 2))
            ->addRow(array('See Actors Other Work', 4))
            ->addRow(array('Settle Argument', 89));*/


        $donutchart = $lava->DonutChart('dailyStatsDonut', $reasons, [
            'title' => 'DonutChart - Daily stats'
        ]);

        $areaChart = $lava->AreaChart('dailyStatsArea', $reasons, [
            'title' => 'AreaChart - Daily stats'
        ]);

        $barChart = $lava->BarChart('dailyStatsBar', $reasons, [
            'title' => 'BarChart - Daily stats'
        ]);

        $pieChart = $lava->PieChart('dailyStatsPie', $reasons, [
            'title' => 'PieChart - Daily stats'
        ]);

        $lineChart = $lava->LineChart('dailyStatsLine', $reasons, [
            'title' => 'LineChart - Daily stats'
        ]);

        //echo $lava->render('dailyTopis');

        return view('pages.forum-stats', [
            'lava' => $lava
        ]);
    }
}
