<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\User;
use App\Models\UserProject;

class AdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
      }
    public function index()
    {
        $admin_id = Auth::user()->id; 
        $users = User::where('id', '!=', $admin_id)->get();
        return view('admin', compact('users'));
    }

    public function updateRole(Request $request, $id){
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();
        return redirect('/projects');
    }
}

?>