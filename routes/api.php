<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CuentaController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\TarjetaController;
use App\Http\Controllers\Api\MovimientoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Registrar usuario
Route::post('/register', [AuthController::class, 'createUser']);

// Autorizacion por usuario/pass
Route::post('/login', [AuthController::class, 'loginUser']);
Route::get("/error/login", function (){
    return response()->json(["status" => false, "message"=>"Necesita darse de alta o autentificarse mediante la API"],401);
})->name("login");
// Autorizacion por tarjeta+PIN
Route::post("/logintarjetapin", [AuthController::class, "loginTarjetaPIN"]);




Route::middleware('auth:sanctum')->group( function () {
// Obtener las cuentas de un cliente
    Route::get("clientes/cuentas", [ClienteController::class, "obtenerCuentas"]);
// Obtener las tarjetas de un cliente
    Route::get("clientes/tarjetas", [ClienteController::class, "obtenerTarjetas"]);
// Obtener saldo de cuenta
    Route::get("cuentas/{cuenta}/saldo", [CuentaController::class, "obtenerSaldo"]);
//obtenerLimiteTarjeta
    Route::get("/tarjetas/{tarjeta}/limite", [TarjetaController::class, "obtenerLimiteTarjeta"]);
//cambiar PIN
    Route::patch('/tarjetas/{tarjetas}/pin', [TarjetaController::class, 'update']);  // o changePin
// Ingreso o extracción desde cajero
    Route::post("tarjetas/{tarjeta}/movimientos", [MovimientoController::class, "realizarMovimientoCajero"]);
//obtenerMovimientosCuenta
    Route::get("cuentas/{cuenta}/movimientos", [MovimientoController::class, "obtenerMovimientosCuenta"]);
//ver tarjeta
    Route::get("/tarjetas/{tarjeta}/pin/{pin}", [TarjetaController::class, "show"]);
//obtenerMovimientosTarjeta
    Route::get("/tarjetas/{tarjeta}/movimientos", [MovimientoController::class, "obtenerMovimientosTarjeta"]);
//obtenerMovimientosCliente
    Route::get("/movimientos", [MovimientoController::class, "obtenerMovimientosCliente"]);
});










Route::middleware("auth:sanctum")->get("/pediralgo", function (){
    // return request()->server("REMOTE_ADDR");
    // return request()->header("authorization");
    //  dd(auth());
            if(!request()->user()->currentAccessToken()->expires_at->isPast()) {
                return "hola";
            } else {
                return "adios";
            };

    });
