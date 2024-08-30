<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $validationRules = [
            'first_name' => 'required|string|max:25',
            'last_name' => 'required|string|max:25',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:8|confirmed',
        ];

        $validatedData = $req->validate($validationRules);

        $newUser =  User::create([
            'first_name' => strtolower($validatedData['first_name']),
            'last_name' => strtolower($validatedData['last_name']),
            'email' => strtolower($validatedData['email']),
            'password' => Hash::make($validatedData['password']),
        ]);

        $newUser->assignRole('basic user');
        $token = JWTAuth::fromUser($newUser);
        $expired_at = Carbon::now()->addDays((env('JWT_TTL') ?? 4320) / 1440);

        return response()->json([
            'msg' => 'user account created successfully',
            'token' => $token,
            'expires' => $expired_at
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
