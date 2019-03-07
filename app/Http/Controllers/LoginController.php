<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 06.11.18
 * Time: 15:57
 */

namespace App\Http\Controllers;


use App\Client\CallManager;
use App\Services\FilterCallData;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $callManager = new CallManager($request->get('username'), $request->get('password'));
        $username = $callManager->getUsername();

        if (!isset($username))
        {
            return view ('pages.home', [
                'error' => "Login failed!"
            ]);
        }

        SessionController::setLoginSession($callManager);
        SessionController::setUsernameSession($username);

        return redirect(request()->session()->previousUrl());
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
        $pagination = (int) $request->get('pagination');

        if (!session()->exists('userLogin'))
        {
            return view('pages.home');
        }
        if (!isset($repository) || !isset($pagination))
        {
            return view('pages.branch', [
                'error' => "Repository or pagination not set!"
            ]);
        }

        $callManager = SessionController::getSession();
        if (isset($callManager))
        {
            $branches = $callManager->getBranches($repository);
            $pullRequests = $callManager->getPullRequests($repository, $pagination);

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

        $callManager = SessionController::getSession();
        if (isset($callManager))
        {
            $repo = 'php-py';
            $teamId = 2093095;
            $pagin = 1;

            $pullRequests = $callManager->getPullRequests($repo, $pagin);
            $members = $callManager->getTeamMembers($teamId);

            if (isset($pullRequests) && isset($members))
            {
                $filteredPulls = FilterCallData::filterPullrequestsWithMembers($pullRequests, $members);

                foreach ($filteredPulls as $key => $value)
                {
                    $filteredPulls[$key]['location'] = $callManager->compareCommitWithBranch($repo, $value['merge_commit_sha']);
                    if (!$filteredPulls[$key]['location']) unset($filteredPulls[$key]);
                    //if (!$filteredPulls[$key]['location']) $filteredPulls[$key]['location'] = 'others';
                }

                return view('pages.pr-location', [
                    'pullRequests' => $filteredPulls
                ]);
            }
            else
            {
                return view('pages.location', [
                    'error' => "Pull Requests or Member - Call failed!"
                ]);
            }
        }
        else
        {
            return view('pages.pr-location', [
                'error' => "No session found!"
            ]);
        }
    }
}
