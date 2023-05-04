<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\User;
use App\Models\UserProject;
use Illuminate\Foundation\Application;

class ProjectsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $projects = $user->projects;

        if (!session()->has('user')) {
            return redirect('/login');
        }


        $nonUsers = User::where('role', '!=', 'admin')
        ->where('id', '!=', $user_id)
        ->whereNotIn('id', function ($query) use ($user_id) {
            $query->select('users.id')
                ->from('users')
                ->join('user_project', 'users.id', '=', 'user_project.user_id')
                ->join('projects', 'user_project.project_id', '=', 'projects.id')
                ->whereColumn('users.id', '!=', 'user_project.user_id')
                ->pluck('users.id');
        })->get();


        return view('projects/index', [
            'projects' => $projects,
            'nonUsers' => $nonUsers,
        ]);
    }


    public function update(Request $request, $id)
    {

        $project = Project::findOrFail($id);
        $project->task_name = $request->task_name;
        $project->task_name_en = $request->task_name_en;
        $project->degree = $request->study_type;
        $project->save();
        return redirect('/projects');
    }

    public function apply(Request $request, $id){
        $user = auth()->user();
        $project = Project::find($id);

        $project->users()->attach($user->id, [
            'permission' => 3,
            'project_id' => $project->id
        ]);

        return redirect('/projects');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect('/projects');
    }

    public function confirmApplicant(Request $request, $project_id, $user_id){
        $project=Project::findOrFail($project_id);
        $user = User::findOrFail($user_id);

        $project->users()->attach($user->id, [
            'permission' => 2,
            'project_id' => $project_id
        ]);

        $project->users()->wherePivot('permission', 3)->wherePivot('project_id', $project_id)->detach();

        return redirect('/projects');
    }

    public function changeLocale($locale){
        session(['locale' => $locale]);
    return back()->withInput();
    }
}


?>