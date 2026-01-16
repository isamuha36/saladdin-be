<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Get dashboard statistics
     * GET /api/dashboard/stats
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        $stats = $this->dashboardService->getDashboardStats($user);

        return response()->json([
            'status' => 'success',
            'data' => $stats,
        ]);
    }

    /**
     * Get continue learning (last accessed lesson)
     * GET /api/dashboard/continue-learning
     */
    public function continueLearning(Request $request)
    {
        $user = $request->user();
        $lastLesson = $this->dashboardService->getLastAccessedLesson($user);

        if (!$lastLesson) {
            return response()->json([
                'status' => 'success',
                'data' => null,
                'message' => 'Belum ada lesson yang diselesaikan.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $lastLesson,
        ]);
    }
}
