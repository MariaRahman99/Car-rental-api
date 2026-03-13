<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
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

    public function store(StoreBranchRequest $request)
    {
        $branch = Branch::create($request->validated());

        return new BranchResource($branch);
    }

    public function show(string $id)
    {
        $branch = Branch::findOrFail($id);
        return new BranchResource($branch);
    }

    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $branch->update($request->validated());

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
