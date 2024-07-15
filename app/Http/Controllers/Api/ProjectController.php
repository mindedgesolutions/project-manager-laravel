<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectSingleResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = request('status');
        $search = request('search');
        $query = Project::orderBy('id', 'desc');
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
    public function store(ProjectRequest $request)
    {
        $image = $request->image ?? null;
        $imagePath = $image ? $image->store('project-' . Str::random(16), 'public') : null;

        Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'due_date' => $request->dueDate,
            'status' => $request->status,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'image_path' => $imagePath
        ]);

        return response()->json(['msg' => 'created'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::where(['id' => $id])->first();
        $tasks = Task::with(['assignedTo' => function ($query) {
            $query->select('id', 'name');
        }])->where('project_id', $id)->paginate(10);

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
