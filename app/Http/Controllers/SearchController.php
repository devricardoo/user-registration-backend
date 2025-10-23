<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;

class SearchController extends Controller
{

    public function __construct(private readonly UserService $service) {}

    public function search(Request $request)
    {
        return $this->service->search($request->all());
    }
}