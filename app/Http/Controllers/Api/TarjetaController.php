<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\Cuenta;
use App\Models\Models\Tarjeta;

class TarjetaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($pcuenta)
    {
        $cliente = auth()->user();
        if ($cliente) {
            $cuentas = Cuenta::where('user_id', $cliente->id)->get();
            $cuenta = Cuenta::find($pcuenta);
            if ($cuentas->contains($cuenta)) {
                return response()->json($cuenta->tarjetas, 200);
            }
            return response()->json("Esta cuenta no es tuya", 403);
        } return response()->json("Identificate perro", 403);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, $pin)
    {
        $cliente = auth()->user();
        $tarjeta = Tarjeta::find($id);
        if ($cliente->id == $tarjeta->id) {
            if ($tarjeta->pin == $pin) {
                return response()->json($tarjeta);
            } else {
                return response()->json("PIN incorrecto");
            }
        } else return response()->json("Esta tarjeta no es tuya", 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($ptarjeta)
    {
        $cliente = auth()->user();
        if ($cliente){
            $nuevoPin = request()->pin;
            $tarjeta = Tarjeta::find($ptarjeta);
            $clienteTar = $tarjeta->cliente;
                if ($cliente->id == $clienteTar->id) {
                    if ($tarjeta) {
                        $tarjeta->update(['pin' => $nuevoPin]);
                        return response()->json(['tarjeta' => $tarjeta], 200);
                    } else {
                        return response()->json(['error' => 'Tarjeta no encontrada'], 404);
                    }
                } else return response()->json(['error' => 'El cliente no es el dueño de la tarjeta'], 403);
        } else return response()->json(['error' => 'Usuario no autenticado'], 403);


    }

    /**
     * Obtiene el límite de la tarjeta que se introduce como parámetro
     *
     * @param $ptarjeta
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerLimiteTarjeta($ptarjeta)
    {
//        $cliente = User::find(auth()->user()->id);
        $cliente = auth()->user();
        $enviarcliente["id"] = $cliente->id;
        $enviarcliente["name"] = $cliente->name;
        $enviarcliente["email"] = $cliente->email;
        $tarjeta = Tarjeta::where('numero', $ptarjeta)->get()->first();
        if (!$tarjeta) {
            return response()->json(['status'=> 'error 404', 'data'=>"La tarjeta no existe"],404);
        }
        if ($cliente->id == $tarjeta->cliente->id) {
            return response()->json(['status' => 'ok', "cliente" => $enviarcliente, 'tarjeta' => $ptarjeta, 'limite' => $tarjeta->limite], 200);
        } else {
            return response()->json(['status'=> 'error 403', "cliente" => $enviarcliente, 'data'=>"El propietario no coincide con el dueño de la tarjeta"],403);
        }
    }


}
