<?php
namespace App\Http\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use App\Http\RepositoryInterface\RepositoryAuthInterface;

class DBRepositoryAuth implements RepositoryAuthInterface
{
    private $userModel;
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }


    public function login($request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return 'InvaledCredentials';
        }

        $token = $user->createToken('auth-token')->plainTextToken;
        $user['token'] = $token;
        $user->getPermissionsViaRoles();

        return $user;
    }
}

