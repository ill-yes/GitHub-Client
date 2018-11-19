<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 15.11.18
 * Time: 11:56
 */

namespace App\Http\Controllers;


use App\Client\CallManager;

/**
 * Class SessionController
 * @package App\Http\Controllers
 */
class SessionController
{
    /**
     * @param CallManager $callManager
     */
    public static function setLoginSession (CallManager $callManager)
    {
        session()->put('userLogin', $callManager);
    }

    /**
     * @param String $username
     */
    public static function setUsernameSession (String $username)
    {
        session()->put('username', $username);
    }

    /**
     * @return CallManager
     */
    public static function getSession():CallManager
    {
        return session()->get('userLogin');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public static function deleteSession()
    {
        session()->flush();
        return redirect()->route('home');
    }
}
