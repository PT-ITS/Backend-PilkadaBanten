<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use DateTimeZone;
use DateTime;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register()
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'alamat' => 'required',
            'noHP' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'alamat' => request('alamat'),
            'noHP' => request('noHP'),
        ]);

        if ($user) {
            return response()->json(['message' => 'Registration successful']);
        } else {
            return response()->json(['message' => 'Registration failed'], 500);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::where('email', request('email'))->first();

        // Menambahkan keterangan khusus langsung ke token yang dihasilkan
        $customClaims = [
            'id' => $user->id,
            'name' => $user->name,
            'level' => $user->level,
        ];

        $tokenWithClaims = JWTAuth::claims($customClaims)->fromUser($user);

        return $this->respondWithToken($tokenWithClaims, $user);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'sub' => $user->id,
            'name' => $user->name,
            'level' => $user->level,
            'iat' => now()->timestamp,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    public function listPengguna()
    {
        try {
            $result = User::where('level', '0')
                ->get();
            return response()->json(
                [
                    'message' => 'data pengguna ditemukan',
                    'data' => $result
                ],
                200
            );
        } catch (\Exception  $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null
            ], 401);
        }
    }

    public function listPengelola()
    {
        try {
            $result = User::where('level', '2')
                ->get();
            return response()->json(
                [
                    'message' => 'data pengelola ditemukan',
                    'data' => $result
                ],
                200
            );
        } catch (\Exception  $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => null
            ], 401);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'alamat' => 'required',
            'noHP' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->alamat = $request->input('alamat');
        $user->noHP = $request->input('noHP');

        $user->save();

        return response()->json([
            'message' => 'success',
            'data' => $user
        ], 200);
    }

    public function delete($id)
    {
        $data = User::find($id);
        if ($data) {
            $data->delete();
            return response()->json([
                'message' => 'success',

            ], 200);
        }
        return response()->json([
            'message' => 'data not found'
        ], 404);
    }
}
