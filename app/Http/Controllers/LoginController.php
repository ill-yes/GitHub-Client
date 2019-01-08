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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class LoginController
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function initLogin(Request $request)
    {
        $callManager = new CallManager($request->get('username'), $request->get('password'));
        $username = $callManager->getUsername();

        if ($username == null)
        {
            return view ('pages.home', ['failedLogin' => true]);
        }
        SessionController::setLoginSession($callManager);
        SessionController::setUsernameSession($username);

        return redirect(request()->session()->previousUrl());
    }


    /**
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
                    'error' => "Error!"
                ]);
            }

        }
        return view('pages.user');
    }

    /**
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
                    'error' => "Error!"
                ]);
            }

        }
        return view('pages.repo');
    }

    /**
     * @return View
     */
    public function deadBranchesCall():View
    {
        if (!session()->exists('userLogin'))
        {
            return view('pages.home');
        }

        $callManager = SessionController::getSession();
        if (isset($callManager))
        {
            $pullRequests = $callManager->getPullRequests();

            if (isset($pullRequests))
            {
                $branches = FilterCallData::filterPullRequestsByBranches($pullRequests, $callManager);

                return view('pages.branch', [
                    'branches' => $branches
                ]);
            }
            else
            {
                return view('pages.branch', [
                    'error' => "Error!"
                ]);
            }

        }
        return view('pages.branch');
    }

}
