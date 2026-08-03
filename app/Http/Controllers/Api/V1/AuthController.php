<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => UserRole::User,
            'terms_accepted_at' => now(),
        ]);

        // The endpoint has always required a name; storing it here is what
        // stops it from being asked for and then dropped. Split the same way
        // the web form collects it, so both paths produce the same profile.
        [$firstName, $lastName] = $this->splitName((string) $request->name);

        $user->profile()->create([
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Înregistrare reușită.',
            'user' => new UserResource($user),
            'token' => $token,
        ], 201);
    }

    /**
     * Everything before the first space is the given name, the rest is the
     * family name - which keeps compound surnames intact. A single word has
     * no family name rather than a duplicated one.
     *
     * @return array{0: string, 1: string}
     */
    private function splitName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name), 2) ?: [];

        return [$parts[0] ?? '', $parts[1] ?? ''];
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credențiale invalide.',
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();
        $user->markLogin();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Autentificare reușită.',
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Deconectare reușită.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }
}
