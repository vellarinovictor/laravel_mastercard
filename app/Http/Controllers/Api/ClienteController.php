<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Cuenta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!isset(request()->contiene)) {
            $clientes = User::all();
        } else {
            $clientes = User::where("nombre", "LIKE", "%" . request()->contiene . "%")->get();
        }

        $mclientes = [];
        foreach ($clientes as $cliente) {
            $mcuentas = [];
            $mcuenta = [];
            foreach ($cliente->cuentas as $cuenta) {
                $mtarjetas = [];
                foreach ($cuenta->tarjetas as $tarjeta) {
                    $mtarjetas[] = [
                        "numero" => $tarjeta->numero,
                        "pin" => $tarjeta->pin,
                        "limite" => $tarjeta->limite
                    ];
                }
                $mcuenta = [
                    "numero" => $cuenta->numero,
                    "saldoinicial" => $cuenta->saldoinicial,
                    "tarjetas" => $mtarjetas
                ];
            }
            $mcuentas[] = $mcuenta;
            $mclientes[] = ["id" => $cliente->id, "nombre" => $cliente->nombre, "cuentas" => $mcuentas];
        }
        return response()->json(['status' => 'ok', 'data' => $mclientes], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cadena = "";
        $cliente = User::find($id);
        $cadena .= "$cliente->id $cliente->nombre<br>";
        foreach ($cliente->cuentas as $cuenta) {
            $cadena .= "&nbsp;&nbsp;&nbsp;$cuenta->numero<br>Saldo inicial: $cuenta->saldoinicial<br>";
            foreach ($cuenta->tarjetas as $tarjeta) {
                $cadena .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
                    "$tarjeta->numero >> $tarjeta->pin >> $tarjeta->limite €<br>";

                foreach ($tarjeta->movimientos as $movimiento) {
                    $cadena .= "Movimiento: " . $movimiento->cantidad . " €<br>";
                }

            }
        }
        return $cadena;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function obtenerCuentas()
    {

        $cliente = auth()->user();
        if (!$cliente) {
            return response()->json(['status' => 'error 404', 'data' => "El cliente no existe"], 404);
        } else {
            if ($cliente->cuentas->count() > 0) {
                foreach (json_decode($cliente->cuentas) as $value) {
                    $cuenta["cuenta"] = $value->numero;
                    $modelocuenta = Cuenta::where("numero", $value->numero)->get()->first();
                    $cuenta["saldo"] = $modelocuenta->saldo();
                    $cuentas[] = $cuenta;
                }
                return response()->json(["status" => "éxito 200", "data" => $cuentas]);
            } else {
                return response()->json(["status" => "error 404", "data" => "El cliente no tiene cuentas", 404]);
            }
        }

    }


    /**
     * Obtiene las tarjetas del usuario autenticado
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerTarjetas()
    {
        $cliente = auth()->user();
        $enviarcliente["id"] = $cliente->id;
        $enviarcliente["name"] = $cliente->name;
        $enviarcliente["email"] = $cliente->email;
        $tarjetas = NULL;
        if ($cliente->tarjetas->count() > 0) {
            foreach ($cliente->tarjetas as $eltotarjeta) {
                $tarjetaaux["numero"] = $eltotarjeta->numero;
                $tarjetaaux["limite"] = $eltotarjeta->limite;
                $tarjetaaux["cuenta"] = $eltotarjeta->cuenta->numero;
                $tarjetas[] = $tarjetaaux;
            }
            return response()->json(['status' => 'ok 200', "cliente" => $enviarcliente, 'data' => $tarjetas], 200);
        } else {
            return response()->json(["status" => "error 404", "cliente" => $enviarcliente, "data" => "No tiene tarjetas"], 404);
        }

    }

}
