<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function search(Request $request)
    {
        $txt = $request->input('txt');

        if ($txt) {
            $users = User::where('name', 'like', '%' . $txt . '%')
                ->orWhere('email', 'like', '%' . $txt . '%')
                ->orWhere('cpf', 'like', '%' . $txt . '%')
                ->orWhere('profile_id', 'like', '%' . $txt . '%')
                ->orWhere('created_at', 'like', '%' . $txt . '%')
                ->get();

            foreach ($users as $user) {
                $array['users'][] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'cpf' => $user->cpf,
                    'profile_id' => $user->profile_id,
                    'created_at' => $user->created_at
                ];
            }
        } else {
            return response()->json(['error' => 'Digite alguma informação!'], 400);
        }

        return response()->json(['data' => $array['users']], 200);
    }
}
