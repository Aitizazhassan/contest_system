<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contest;
use App\Support\ApiResponse;
use Illuminate\Validation\Rule;

class ContestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contests = Contest::with('prize')->get();
        return ApiResponse::success($contests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string|nullable',
            'access_level' => ['required', Rule::in(['vip','normal'])],
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'prize.name' => 'string|nullable',
            'prize.description' => 'string|nullable',
        ]);

        $contest = Contest::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'access_level' => $validated['access_level'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
        ]);

        if (!empty($validated['prize']['name'])) {
            $contest->prize()->create([
                'name' => $validated['prize']['name'],
                'description' => $validated['prize']['description'] ?? null,
            ]);
        }

        return ApiResponse::success($contest->load('prize'), 'Contest created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contest = Contest::with(['questions.answers', 'prize'])->findOrFail($id);
        return ApiResponse::success($contest);
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
