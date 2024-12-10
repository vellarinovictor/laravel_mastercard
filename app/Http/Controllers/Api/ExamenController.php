<?php

namespace App\Http\Controllers\Api;

use App\Models\Models\Cuenta;
use App\Models\Models\Movimiento;
use App\Models\Models\User;


class ExamenController
{
    public function listarCuentas()
    {
        $datos = [];
        $clientes = User::all();
        foreach ($clientes as $cliente) {
            $cuentas = $cliente->cuentas;
            foreach ($cuentas as $cuenta) {
                $datos[] = $cuenta->cliente->id;
                $datos[] = $cuenta->cliente->email;
                $datos[] = $cuenta->id;
                $datos[] = $cuenta->numero;
                $datos[] = $cuenta->saldo();
            }
        }
        return response()->json($datos);
    }

    public function mayoresRetiradas(){
        $tamanio = request()->num;
        $movimientos = Movimiento::where('cantidad', '<', 0)
            ->orderBy('cantidad', 'asc')
            ->limit($tamanio)
            ->get();
        foreach ($movimientos as $movimiento) {
            $datos[] = [
                'idCliente' => $movimiento->tarjeta->cliente->id,
                'email' => $movimiento->tarjeta->cliente->email,
                'idCuenta' => $movimiento->cuenta->id,
                'numeroCuenta' => $movimiento->cuenta->numero,
                'idTarjeta' => $movimiento->tarjeta->id,
                'numeroCuenta' => $movimiento->tarjeta->numero,
                'cantidad' => $movimiento->cantidad,
                'fecha' => $movimiento->fecha,
            ];
        }

        return response()->json(['datos'=>$datos],200);
    }

    public function borrarCuenta($numcuenta){
        $cliente = auth()->user();
        $cuenta = Cuenta::where('numero', $numcuenta)->get()->first();
        $clienteCuenta = $cuenta->cliente;
        if ($clienteCuenta->id == $cliente->id || $cliente->id<5) {
            $enviar["idCliente"] = $clienteCuenta->id;
            $enviar["email"] = $clienteCuenta->email;
            $enviar["idCuenta"] = $cuenta->id;
            $enviar["numeroCuenta"] = $cuenta->numcuenta;
            $enviar["saldo"] = $cuenta->saldo();
            $cuenta->delete();
            return response()->json(['datos'=>$enviar],200);
        }
        return response()->json(['error'=>'No tienes permiso para esto'],403);
    }

}
