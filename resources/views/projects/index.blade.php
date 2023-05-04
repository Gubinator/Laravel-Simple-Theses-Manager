@extends('layouts.app')



@section('content')
<div style="display: flex; flex-flow: row wrap; justify-content: center; padding-bottom: 16px;">
    <form action="{{route('projects.locale', ['locale' => 'hr'])}}" method="GET">
        <input type="hidden" name="_method" value="GET">
        @csrf
        <button type="submit" class="btn btn-secondary"
        style="margin-top: 8px; margin-bottom: 8px; ">
            <i class="fa fa-btn fa-trash"></i>HR
        </button>
    </form>
    <form action="{{route('projects.locale', ['locale' => 'en'])}}" method="GET">
        <input type="hidden" name="_method" value="GET">
        @csrf
        <button type="submit" class="btn btn-secondary"
        style="margin-top: 8px; margin-left:16px; margin-bottom: 8px; ">
            <i class="fa fa-btn fa-trash"></i>EN
        </button>
    </form>
</div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('index.dashboard') }}</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (Auth::check() && Auth::user()->name)
                            {{ __('index.logged') }} <i>{{ Auth::user()->name }}</i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('projects')
    @if (Auth::check() && Auth::user()->role == 'professor')
        <div class="d-flex flex-column align-items-center pt-5">
            <h1>{{__('index.newTitle')}}</h1>
            <div style="width:40%;">
                <form action="{{ url('projects') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="pb-2">
                            <label for="task-name" class="controllabel">{{__('index.thesisName')}}</label>
                            <input type="text" name="task_name" id="taskname" class="form-control" required>
                        </div>
                        <div class="pb-2">
                            <label for="task-name" class="controllabel">{{__('index.thesisNameEn')}}</label>
                            <input type="text" name="task_name_en" id="taskname" class="form-control" required>
                        </div>
                        <div class="pb-2">
                            <label for="task-name" class="controllabel">{{__('index.degree')}}</label>
                            <select name="study_type" style="margin-top:8px; margin-bottom:8px; margin-left:8px;">
                                <option value="Undergraduate">{{__('index.degreeUnder')}}</option>
                                <option value="Graduate">{{__('index.degreeGraduate')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex flex-row pt-2">
                            @if (count($nonUsers) > 0)
                                <label for="task-name" class="controllabel" style="width: 8rem">{{__('index.asign')}}</label>
                            @endif
                            @foreach ($nonUsers as $nonUser)
                                <div style="display: flex; flex-flow: row wrap; align-items:center;  padding-right: 1rem;">
                                    <input type="checkbox" id="{{ $nonUser->id }}" name="associates[]"
                                        value="{{ $nonUser->id }}" style="margin-right: 0.25rem;">
                                    <label> {{ $nonUser->name }}</label><br>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6 pt-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-plus"></i>{{__('index.addThesis')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
                @foreach ($projects as $project)
                    @if ($project->users()->wherePivot('permission', 3)->exists())
                    <h1 class="pb-5 pt-5">{{__('index.applicationTitle')}}</h1>
                        <div class="card mt-2" style="width: 20%; padding-left:16px; padding-right: 16px; padding-top: 8px; padding-bottom: 8px;">
                            @php
                                $usersApplied = $project->users()->wherePivot('permission', 3)->get();
                                $projectName = $project->task_name;
                            @endphp
                            <span><b>{{__('index.thesis')}}</b> {{$projectName}}</span>
                            <span>{{__('index.applied')}} </span>
                            @foreach ($usersApplied as $userApplied)
                                <div>
                                    <span style="display: flex; flex-flow: row wrap; justify-content: space-evenly; align-items:center;">
                                        <span> Student - {{$userApplied->name}} </span>
                                        <form action="{{route('projects.confirm', ['project_id' => $project->id, 'user_id' => $userApplied->id])}}" method="POST">
                                        <input type="hidden" name="_method" value="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success"
                                        style="margin-top: 8px; margin-bottom: 8px; ">
                                            <i class="fa fa-btn fa-trash"></i>{{__('index.confirmButton')}}
                                        </button>
                                    </form>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                @endforeach


            </div>
    @endif
    @if ($user->role == 'professor')
        @if (count($projects) > 0)
            <h1 class="font-weight-bold pt-5 pb-3" style="text-align: center;">{{__('index.allThesesTitle')}}</h1>
            <div class="container">
                @foreach ($projects as $project)
                    @if ($project->pivot->permission == 1)
                        <ul class="card list-unstyled" style="padding: 10px 10px 10px 10px">
                            <form action="{{ url('projects/' . $project->id) }}" method="POST">
                                <li>
                                    <span><b>{{__('index.name')}} </b><input name="task_name" value="{{ $project->task_name }}"
                                            style=" margin-bottom:8px; margin-left:8px; border: none;"></span>
                                </li>
                                <li>
                                    <span><b>{{__('index.englishName')}} </b><input name="task_name_en"
                                            value="{{ $project->task_name_en }}"
                                            style=" margin-bottom:8px; margin-left:8px; border: none;"></span>
                                </li>
                                <li>
                                    @if ($project->users()->wherePivot('permission', 2)->exists())
                                        <span style="margin-bottom:8px;"><b>Student: </b>
                                            {{ $project->users()->wherePivot('permission', 2)->first()->name }} </span>
                                    @else
                                        <span style=" margin-bottom:8px;"><b>Student: </b>{{__('index.none')}} </span>
                                    @endif
                                </li>
                                <li>
                                    <span><b>{{__('index.degree')}} </b><select name="study_type"
                                            style="margin-top:8px; margin-left:8px; border:none;">
                                            @if ($project->degree == 'Undergraduate')
                                                <option value="Undergraduate" selected>{{__('index.degreeUnder')}}</option>
                                                <option value="Graduate">{{__('index.degreeGraduate')}}</option>
                                            @else
                                                <option value="Undergraduate">{{__('index.degreeUnder')}}</option>
                                                <option value="Graduate" selected>{{__('index.degreeGraduate')}}</option>
                                            @endif
                                        </select></span>
                                </li>
                                <li>
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                    <input type="hidden" name="_method" value="PUT">
                                    <button type="submit" class="btn btn-secondary"
                                        style="margin-top: 8px; margin-bottom: 8px; ">
                                        <i class="fa fa-btn fa-trash"></i>{{__('index.updateButton')}}
                                    </button>
                            </form>
                            </li>
                            <li class="d-flex">
                                <form action="{{ url('projects/' . $project->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa fa-btn fa-trash"></i>{{__('index.deleteButton')}}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    @endif
                @endforeach


            </div>
            </div>
            </div>
        @endif

    @endif

    @if ($user->role != 'professor')
        <h1 class="font-weight-bold pt-5 pb-3" style="text-align: center;">{{__('index.allTheses')}}
            @if ($user->degree == "Undergraduate")
                - {{__('index.degreeUnder')}}
            @else
                - {{__('index.degreeGraduate')}}
            @endif
        </h1>
        <div class="container" style="width:60%;">
            @foreach ($degreeProjects as $project)
                @if ($project->users()->where('user_id', $user->id)->wherePivot('permission', 3)->exists())
                    <ul class="card list-unstyled" style="padding: 10px 10px 10px 10px">
                        <li>
                            <span><b>{{__('index.name')}} </b><input name="task_name" value="{{ $project->task_name }}"
                                    style=" margin-bottom:8px; margin-left:8px; border: none;"></span>
                        </li>
                        <li>
                            <span><b>{{__('index.englishName')}} </b><input name="task_name_en" value="{{ $project->task_name_en }}"
                                    style=" margin-bottom:8px; margin-left:8px; border: none;"></span>
                        </li>
                        <li style="margin-bottom:8px;">
                            @if ($project->users()->wherePivot('permission', 2)->exists())
                                <span style="margin-bottom:8px;"><b>Student: </b>
                                    {{ $project->users()->wherePivot('permission', 2)->first()->name }} </span>
                            @else
                                <span style="margin-bottom:8px;"><b>Student: </b>{{__('index.none')}} </span>
                            @endif
                        </li>
                        <li>
                            <span style="margin-top: 8px;"><b>{{__('index.degree')}} </b> {{ $project->degree }} </span>
                        </li>
                        <li>
                            <button type="submit" disabled class="btn btn-secondary"
                                style="margin-top: 8px; margin-bottom: 8px; ">
                                <i class="fa fa-btn fa-trash"></i>{{__('index.alreadyAppliedButton')}}
                            </button>
                        </li>
                    </ul>
                @elseif($project->users()->where('user_id', $user->id)->wherePivot('permission', 2)->exists())
                    <ul class="card list-unstyled" style="padding: 10px 10px 10px 10px">
                        <li>
                            <span><b>{{__('index.name')}} </b><input name="task_name" value="{{ $project->task_name }}"
                                    style=" margin-bottom:8px; margin-left:8px; border: none;"></span>
                        </li>
                        <li>
                            <span><b>{{__('index.englishName')}}</b><input name="task_name_en" value="{{ $project->task_name_en }}"
                                    style=" margin-bottom:8px; margin-left:8px; border: none;"></span>
                        </li>
                        <li style="margin-bottom:8px;">
                            @if ($project->users()->wherePivot('permission', 2)->exists())
                                <span style="margin-bottom:8px;"><b>Student: </b>
                                    {{ $project->users()->wherePivot('permission', 2)->first()->name }} </span>
                            @else
                                <span style="margin-bottom:8px;"><b>Student: </b>{{__('index.none')}}</span>
                            @endif
                        </li>
                        <li>
                            <span style="margin-top: 8px;"><b>{{__('index.degree')}} </b> {{ $project->degree }} </span>
                        </li>
                        <li>
                            <button type="submit" disabled class="btn btn-success"
                                style="margin-top: 8px; margin-bottom: 8px; ">
                                <i class="fa fa-btn fa-trash"></i>{{__('index.acceptedButton')}}
                            </button>
                        </li>
                    </ul>
                @else
                    <ul class="card list-unstyled" style="padding: 10px 10px 10px 10px">
                        <li>
                            <span><b>{{__('index.name')}} </b><input name="task_name" value="{{ $project->task_name }}"
                                    style=" margin-bottom:8px; margin-left:8px; border: none;"></span>
                        </li>
                        <li>
                            <span><b>{{__('index.englishName')}} </b><input name="task_name_en" value="{{ $project->task_name_en }}"
                                    style=" margin-bottom:8px; margin-left:8px; border: none;"></span>
                        </li>
                        <li style="margin-bottom:8px;">
                            @if ($project->users()->wherePivot('permission', 2)->exists())
                                <span style="margin-bottom:8px;"><b>Student: </b>
                                    {{ $project->users()->wherePivot('permission', 2)->first()->name }} </span>
                            @else
                                <span style="margin-bottom:8px;"><b>Student: </b>{{__('index.none')}} </span>
                            @endif
                        </li>
                        <li>
                            <span style="margin-top: 8px;"><b>{{__('index.degree')}} </b> {{ $project->degree }} </span>
                        </li>
                        <li>
                            <form action="{{ route('projects.apply', ['id' => $project->id]) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('POST') }}
                                <input type="hidden" name="_method" value="POST">
                                @if (!$project->users()->wherePivot('permission', 2)->exists())
                                <button type="submit" class="btn btn-primary"
                                    style="margin-top: 8px; margin-bottom: 8px; ">
                                    <i class="fa fa-btn fa-trash"></i>{{__('index.applyButton')}}
                                </button>
                                @else 
                                <button type="submit" disabled class="btn btn-secondary"
                                    style="margin-top: 8px; margin-bottom: 8px; ">
                                    <i class="fa fa-btn fa-trash"></i>{{__('index.themeTaken')}}
                                </button>
                                @endif
                            </form>
                        </li>
                    </ul>
                @endif
            @endforeach
        </div>
    @endif

@endsection
