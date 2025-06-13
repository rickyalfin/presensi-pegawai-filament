<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            /**
             * Email
             *
             * @example admin@rickyalfina.com
             */
            'email'    => 'required|string|email',
            /**
             * Password
             *
             * @example admin123
             */

            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'data'    => null,
                'message' => 'Invalid email or password',
            ], 422);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data'    => [
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => $user,
            ],
            'message' => 'Login successful',
        ]);
    }
}
