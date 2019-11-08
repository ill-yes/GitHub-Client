<?php

namespace App\Http\Controllers;

use App\Discourse\Api\Contracts\ApiHandlerRepositoryContract;
use App\Discourse\Api\Services\TopicsService;
use App\Discourse\Repositories\ApiHandlerRepository;
use App\Github\Client\CallManager;
use App\Github\Models\Pullrequest;
use App\Github\Models\Repository;
use App\Github\Services\FilterCallData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
    public function prLocationCall():View
    {

        $api = new TopicsService();
        $test = $api->getTeamStatsForAllCategories();


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
}
