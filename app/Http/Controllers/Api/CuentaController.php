<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;

class CuentaController extends Controller
{
    public function obtenerSaldo($pcuenta) {
        $cuenta = Cuenta::where("numero", $pcuenta)->get()->first();
        $usuario = auth()->user();
        $enviarusuario["id"] = $usuario->id;
        $enviarusuario["name"] = $usuario->name;
        $enviarusuario["email"] = $usuario->email;
        if (!$cuenta) {
            return response()->json(['status'=>'error 404', "cuenta"=> $pcuenta, 'data'=> "La cuenta no existe"],404);
        } else {
            // Comprobar que el cliente es el dueño de la cuenta
            if($cuenta->cliente->id  != $usuario->id) {
                return response()->json(["status" => "error 404", "cuenta"=> $cuenta->numero, "usuario" => $enviarusuario,
                    "data" => "El usuario no esta autorizado (no es el propietario de la cuenta)"], 404);
            }
            return response()->json(['status'=>'ok',"cliente" => $enviarusuario, "cuenta"=> $cuenta->numero, 'saldo'=>$cuenta->saldo()],200);
        }
    }
}
