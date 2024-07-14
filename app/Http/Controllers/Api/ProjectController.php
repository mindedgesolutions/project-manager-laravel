<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectSingleResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = request('status');
        $search = request('search');
        $query = Project::orderBy('created_at');
        if ($status) {
            $query = $query->where(['status' => $status]);
        }
        if ($search) {
            $query = $query->where(function ($query) use ($search) {
                $query = $query->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }
        $projects = $query->orderBy('name')->paginate(10);

        return ProjectResource::collection(($projects));
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
        $project = Project::where(['id' => $id])->first();
        $tasks = $project->tasks()->paginate(10);

        return ProjectSingleResource::make($project)->additional(['tasks' => $tasks]);
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
