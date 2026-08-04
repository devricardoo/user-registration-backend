<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Services\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    public function __construct(
        private readonly AddressService $service
    ) {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        return $this->service->index($request->all());
    }

    public function store(StoreAddressRequest $request)
    {
        $address = $this->service->create($request->validated());

        return response()->json([
            'message' => 'Endereço cadastrado com sucesso!'
        ], 201);
    }

    public function show(int $id)
    {
        return response()->json(['address' => $this->service->show($id)], 200);
    }

    public function searchByCep(string $cep)
    {
        return response()->json([
            'addresses' => $this->service->searchByCep($cep),
        ], 200);
    }

    public function update(UpdateAddressRequest $request, int $id)
    {
        $address = $this->service->update($id, $request->validated());

        return response()->json([
            'message' => 'Endereço atualizado com sucesso'
        ], 200);
    }

    public function delete(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Endereço deletado com sucesso'
        ], 200);
    }
}
