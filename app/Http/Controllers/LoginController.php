<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 06.11.18
 * Time: 15:57
 */

namespace App\Http\Controllers;


use App\Client\CallManager;
use App\Console\Commands\PullrequestsCron;
use App\DB\PullrequestsModel;
use App\Services\FilterCallData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Tests\Unit\FilteredPullrequestsTest;

/**
 * Class LoginController
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    /**
     * Logic for login
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|View
     */
    public function initLogin(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        $tokenCheck = (boolean) $request->get('tokenCheck');

        if ($tokenCheck)
        {
            $token = $password;
            $callManager = new CallManager($token, true);
        }
        else
        {
            $userPw = base64_encode($username . ':' . $password);
            $callManager = new CallManager($userPw, false);
        }

        $username = $callManager->username;

        if (!isset($username))
        {
            return view ('pages.home', [
                'error' => "Login failed or 2FA is active for this account (use token-method) !"
            ]);
        }
        elseif (isset($username))
        {
            $members = $callManager->getTeamMembers(env('TEAM_ID'));

            if(isset($members[$username]))
            {
                SessionController::setLoginSession($callManager);
                SessionController::setUsernameSession($username);

                return redirect(request()->session()->previousUrl());
            }
        }

        return view ('pages.home', [
            'error' => "You're not in the team!"
        ]);
    }


    /**
     * Logic for "user"-tab
     * @return View
     */
    public function userInfoCall():View
    {
        if (!session()->exists('userLogin'))
        {
            return view('pages.home');
        }

        $callManager = SessionController::getSession();
        if (isset($callManager))
        {
            $data = $callManager->getUserInfo();

            if (isset($data))
            {
                return view('pages.user', [
                    'login' => $data->login,
                    'avatar_url' => $data->avatar_url,
                    'html_url' => $data->html_url,
                    'fullname' => $data->name,
                    'company' => $data->company,
                    'location' => $data->location,
                    'public_repos' => $data->public_repos,
                    'followers' => $data->followers,
                    'following' => $data->following,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'planName' => $data->plan->name
                ]);
            }
            else
            {
                return view('pages.user', [
                    'error' => "Can't retrieve user information!"
                ]);
            }

        }
        else
        {
            return view('pages.user', [
                'error' => "No session found!"
            ]);
        }
    }

    /**
     * Logic for "repository"-tab
     * @return View
     */
    public function ownRepoCall():View
    {
        if (!session()->exists('userLogin'))
        {
            return view('pages.home');
        }

        $callManager = SessionController::getSession();
        if (isset($callManager))
        {
            $repositories = $callManager->getUserOwnRepo();

            if (isset($repositories))
            {
                $allRepositories = FilterCallData::filterOwnRepos($repositories);

                return view('pages.repo', [
                    'repoDataArray' => $allRepositories
                ]);
            }
            else
            {
                return view('pages.repo', [
                    'error' => "No repositories found!"
                ]);
            }

        }
        else
        {
            return view('pages.repo', [
                'error' => "No session found!"
            ]);
        }
    }


    /**
     * Init logic for "branches"-tab
     * @return View
     */
    public function branchView():View
    {
        if (!session()->exists('userLogin'))
        {
            return view('pages.home');
        }

        $callManager = SessionController::getSession();
        if (isset($callManager))
        {
            $orgaRepo = $callManager->getOrgaRepo();

            if (isset($orgaRepo))
            {
                return view('pages.branch', [
                    'orgaRepo' => $orgaRepo
                ]);
            }
            else
            {
                return view('pages.branch', [
                    'error' => "Can't get list of repositories!"
                ] );
            }
        }
        else
        {
            return view('pages.branch', [
                'error' => "No session found!"
            ]);
        }
    }


    /**
     * Request logic for "branches"-tab
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|View
     */
    public function deadBranchesCall(Request $request)
    {
        $repository = (String) $request->get('repository');
        $amountOfDays = (int) $request->get('days');

        if (!session()->exists('userLogin'))
        {
            return view('pages.home');
        }
        if (!isset($repository) || !isset($amountOfDays))
        {
            return view('pages.branch', [
                'error' => "Repository or days not set!"
            ]);
        }

        $callManager = SessionController::getSession();
        if (isset($callManager))
        {
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
        else
        {
            return view('pages.branch', [
                'error' => "No session found!"
            ]);
        }
    }

    public function prLocationCall():View
    {
        if (!session()->exists('userLogin'))
        {
            return view('pages.home');
        }

        // For debugging
        /*$pr = new PullrequestsCron();
        $baseBranches = [
            'plentymarkets:early' => true,
            'plentymarkets:beta7' => true,
            'plentymarkets:stable7' => true
        ];
        $pr->addCron(env('TOKEN'), 'module-order', env('TEAM_ID'), 5, $baseBranches);
        $pr->addCron(env('TOKEN'), 'php-pl', env('TEAM_ID'), 5, $baseBranches);
        $pr->handle();*/

        if (PullrequestsModel::count() > 0)
        {
            return view('pages.pr-location', [
                'lastUpdate' => Carbon::parse(PullrequestsModel::first()->created_at)->format('l - H:i, d.m.Y'),
                'pullRequests' => PullrequestsModel::all()
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
