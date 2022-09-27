<?php

    namespace App\Http\Controllers;

    use Illuminate\Foundation\Auth\AuthenticatesUsers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class UserController extends Controller
    {
        use AuthenticatesUsers;

        public function doLogin(Request $request) {
            Auth::logout();
            $this->validateLogin($request);
            $credentials = $this->credentials($request);
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('apiToken');
                return response()->json([
                    'status' => 'success',
                    'token' => $token->plainTextToken,
                ]);

            } else {
                return response([
                    'status' => 'error',
                ], 403);
            }
        }
    }
