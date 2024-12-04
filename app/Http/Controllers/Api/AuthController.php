<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Tarjeta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Usuario creado',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'El email/password no coincide con los registrados.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
//            $user->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => 'Usuario logueado',
                'token' => $user->createToken("API TOKEN",["*"], now()->addDays(2))->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }    



    public function loginTarjetaPIN(Request $request)
    {

        $numerotarjeta = $request->tarjeta;
        $pin = $request->pin;

        try {
            $validateData = Validator::make($request->all(),
                [
                    'pin' => 'required',
                    'tarjeta' => 'required'
                ]);

            if ($validateData->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateData->errors()
                ], 401);
            }

            $tarjeta = Tarjeta::where("numero", $numerotarjeta )->where("pin",$pin)->get()->first();            
            if (!$tarjeta) {
                return response()->json([
                    'status' => false,
                    'message' => 'El par tarjeta/pin no coincide con los registrados.',
                ], 401);
            }
            $user = $tarjeta->cliente;
            $user->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => 'Usuario logueado',
                'token' => $user->createToken("API TOKEN",["*"], now()->addDays(2))->plainTextToken,
                'nombre' => $user->name,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }    
}
