<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Contest;

class CheckContestAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $contestId = $request->route('contest') ?? $request->route('id');

        if (!$contestId) {
            return $next($request);
        }

        $contest = Contest::find($contestId);
        if (!$contest) {
            return response()->json(['message' => 'Contest not found'], 404);
        }

        // Guests can only view contests (index/show) never participate
        $method = $request->method();
        $isRead = in_array($method, ['GET', 'HEAD']);

        // Admins and VIPs can access everything.
        if ($user && in_array($user->role, ['admin', 'vip'])) {
            return $next($request);
        }

        // Guests and Normal Users have different rules for read vs write
        if ($isRead) {
            // Anyone can view any contest
            return $next($request);
        }

        // --- Participation Logic (POST/PUT/etc) ---

        // Guests are blocked from participating.
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Normal users cannot participate in VIP contests.
        if ($contest->access_level === 'vip' && $user->role === 'normal') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
