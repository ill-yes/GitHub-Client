<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 06.11.18
 * Time: 15:57
 */

namespace App\Http\Controllers;


use App\Client\CallManager;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class LoginController
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
    private $callManager;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function initLogin(Request $request)
    {
        $this->callManager = new CallManager($request->get('username'), $request->get('password'));
        SessionController::setLoginSession($this->callManager);

       return $this->usernameCall();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function usernameCall()
    {
        if (!session()->exists('userLogin'))
        {
            return view('main');
        }
        $this->callManager = SessionController::getSession();
        $username = $this->callManager->getUsername();

        SessionController::setUsernameSession($username);

        return redirect(request()->session()->previousUrl());
    }

    /**q
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userInfoCall()
    {
        if (!session()->exists('userLogin'))
        {
            return view('main');
        }

        $this->callManager = SessionController::getSession();
        if (isset($this->callManager))
        {
            $data = $this->callManager->getUserInfo();

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
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ownRepoCall()
    {
        if (!session()->exists('userLogin'))
        {
            return view('main');
        }

        $this->callManager = SessionController::getSession();
        if (isset($this->callManager)) {
            $dataArray = $this->callManager->getUserOwnRepo();

            if (isset($dataArray))
            {
                $filter = ['name', 'private', 'html_url', 'created_at', 'updated_at', 'language', 'forks'];

                $allRepositories = [];
                foreach($dataArray AS $repo)
                {
                    $singleRepo = [];
                    foreach ($filter as $value)
                    {
                        if ($value == 'created_at' || $value == 'updated_at')
                        {
                            $date = Carbon::createFromFormat(DATE_ISO8601, $repo->{$value})->format('d.m.Y');
                            $singleRepo[$value] = $date;
                        }
                        else
                        {
                            $singleRepo[$value] = $repo->{$value};
                        }
                    }
                    $allRepositories[] = $singleRepo;
                }

                return view('pages.user', [
                    'repoDataArray' => $allRepositories
                ]);
            }
            else
            {
                return view('pages.user', [
                    'error' => "Error!"
                ]);
            }

        }
    }

}
