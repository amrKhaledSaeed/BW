<?php

namespace App\Http\Controllers\V1\Api\Auth;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Requests\loginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\RepositoryInterface\RepositoryAuthInterface;

class LoginController extends Controller
{
    use helpers;
    private $authInterface;
    public function __construct(RepositoryAuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }
    /**
     * @param $request
     * $request email,password
     * return $userData with token
     */

    public function login(loginRequest $request)
    {
        $login = $this->authInterface->login($request);
        return $this->apiResponse(new UserResource($login));
    }

}
