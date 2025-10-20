<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(private readonly UserService $service) {}


    public function index(Request $request)
    {
        $validated = $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ]);

        $users = $this->service->index($validated);

        // Adds 'per_page' and 'page' parameters to pagination response to make frontend navigation easier
        $users->appends($request->all());

        return response()->json($users, 200);
    }


    public function createprofile(Request $request)
    {
        $this->service->createprofile($request->all());
        return response()->json(['msg' => 'Perfil criado com sucesso'], 201);
    }

    public function login(Request $request)
    {
        return $this->service->login($request->all());
    }

    public function create(Request $request)
    {
        $request->validate($this->service->entity->rules(), $this->service->entity->feedback());

        $user = $this->service->create($request->all());

        return $user;
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function update(Request $request, $id)
    {
        $user = $this->service->findById($id);

        $validated = $request->validate(
            $this->service->entity->rules($user->id),
            $this->service->entity->feedback()
        );

        $user = $this->service->update($id, $validated);

        return response()->json($user);
    }

    public function delete($id)
    {
        return $this->service->delete($id);
    }
}