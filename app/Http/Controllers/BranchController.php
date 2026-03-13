<?php

namespace App\Http\Controllers;

use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return BranchResource::collection($branches);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'manager_id' => 'nullable|exists:employees,id',
        ]);

        $branch = Branch::create($validated);

        return new BranchResource($branch);
    }

    public function show(string $id)
    {
        $branch = Branch::findOrFail($id);
        return new BranchResource($branch);
    }

    public function update(Request $request, string $id)
    {
        $branch = Branch::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'phone_number' => 'sometimes|required|string|max:20',
            'manager_id' => 'nullable|exists:employees,id',
        ]);

        $branch->update($validated);

        return new BranchResource($branch);
    }

    public function destroy(string $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return response()->json([
            'message' => 'Branch deleted successfully'
        ]);
    }
}