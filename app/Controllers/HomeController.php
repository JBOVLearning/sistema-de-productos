<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Support\Response;
use App\View;

final class HomeController
{
    public function index(): Response
    {
        return Response::html(View::page('home'));
    }
}