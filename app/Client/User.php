<?php
/**
 * Created by IntelliJ IDEA.
 * User: ilyestascou
 * Date: 05.11.18
 * Time: 17:16
 */

namespace App\Client;

class User
{
    private $username;
    private $password;
    private $userToken;

    function __construct($username, $password, $userToken)
    {
        $this->username = $username;
        $this->password = $password;
        $this->userToken = $userToken;
    }

    public function getUsername ():String
    {
        return $this->username;
    }

    private function getPassword ():String
    {
        return $this->password;
    }

    public function getUserToken ():String
    {
        return $this->userToken;
    }

    public function setUserToken ($userToken)
    {
        $this->userToken = $userToken;
    }
}
