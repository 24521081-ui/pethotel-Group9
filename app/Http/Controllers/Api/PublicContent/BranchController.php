<?php

namespace App\Http\Controllers\Api\PublicContent;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Branch::with('rooms.typeRoom')
                ->where('is_active', 1)
                ->orderBy('branch_name')
                ->get(),
        ]);
    }

    public function show(string $branchId): JsonResponse
    {
        return response()->json([
            'data' => Branch::with(['rooms.typeRoom'])
                ->where('is_active', 1)
                ->where('branch_id', $branchId)
                ->firstOrFail(),
        ]);
    }
}
