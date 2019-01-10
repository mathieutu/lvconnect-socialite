<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\StatefulGuard as Auth;
use Illuminate\Contracts\Routing\ResponseFactory as Response;
use Illuminate\Http\Request;

class Logout
{
    public function __invoke(Request $request, Auth $auth, Response $response)
    {
        $auth->logout();
        $request->session()->invalidate();

        return $response->redirectToRoute('login');
    }
}
