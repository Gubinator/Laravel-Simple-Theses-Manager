<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\User;
use App\Models\UserProject;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $all = User::all();
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $projects = $user->projects;
        
        $degreeProjects = Project::where('degree', '=', $user->degree)->get();
        //$degreeProjects = $userDegree->projects;
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

            return view('projects.index', [
                'projects' => $projects,
                'nonUsers' => $nonUsers,
                'userId' => $user_id,
                'degreeProjects' => $degreeProjects,
                'user' => $user,
                'all' => $all
            ]);

    }

    public function update(Request $request, $id)
    {

        $project = Project::findOrFail($id);
        $project->task_name = $request->task_name;
        $project->task_name_en = $request->task_name_en;
        $project->study_type = $request->study_type;
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

}