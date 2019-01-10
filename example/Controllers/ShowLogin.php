<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory as View;

class ShowLogin
{
    public function __invoke(View $view)
    {
        return $view->make('login');
    }
}
