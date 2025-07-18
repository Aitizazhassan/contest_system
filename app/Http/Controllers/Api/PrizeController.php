<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prize;
use App\Support\ApiResponse;

class PrizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Admin route returns all awarded prizes
        if ($request->is('api/v1/admin/*')) {
            // This is already protected by the 'admin' middleware in the route file
            $prizes = Prize::whereNotNull('user_id')
                ->with(['user:id,name', 'contest:id,name'])
                ->latest()
                ->get();
        } else {
            // Regular user route returns only their own prizes
            $prizes = Prize::where('user_id', $user->id)
                ->with('contest:id,name')
                ->latest()
                ->get();
        }

        return ApiResponse::success($prizes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
