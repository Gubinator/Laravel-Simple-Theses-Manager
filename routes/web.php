<?php

use App\Http\Controllers\AdminController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\UserProject;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/home/en', function () {
    App::setlocale('en');
    return view('index');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/admin', [AdminController::class, 'index']);

//Projects endpoint 
Route::get('/projects', [ProjectsController::class, 'index']);

Route::delete('/projects/{id}', [ProjectsController::class, 'destroy'])->name('projects.destroy');

Route::put('/admin/{id}/', [AdminController::class, 'updateRole'])->name('admin.update-role');

Route::get('/projects/{locale}', [ProjectsController::class, 'changeLocale'])->name('projects.locale');

Route::put('/projects/{id}', [ProjectsController::class, 'update'])->name('projects.update');

Route::post('/projects/{id}', [ProjectsController::class, 'apply'])->name('projects.apply');

Route::post('projects/{project_id}/users/{user_id}', [ProjectsController::class, 'confirmApplicant'])->name('projects.confirm');

Route::post(
    '/projects',
    function (Request $request) {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'task_name' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return redirect('/projects')
                ->withInput()
                ->withErrors($validator);
        }
        $project = new Project;

        $project->task_name = $request->task_name;
        $project->task_name_en = $request->task_name_en;
        $project->degree = $request->study_type;
        $project->save();


        $user->projects()->attach($user->id, [
            'permission' => 1,
            'project_id' => $project->id
        ]);


        $associates = $request->input('associates');
        if ($associates) {
            foreach ($associates as $associateId) {
                $user->projects()->attach($associateId, [
                    'permission' => 2,
                    'project_id' => $project->id,
                    'user_id' => $associateId
                ]);
            }
        }

        /*$selectedUserIds = array_keys($request->all('user*'));
        foreach ($selectedUserIds as $userId) {
        $user = User::find($userId);
        $user->projects()->attach($project->id, [
        'permission' => 2,
        ]);
        }*/


        /*$user->projects()->attach([1,2],[
        'permission' => 1
        ]);*/
        return redirect('/projects');
    }
);

