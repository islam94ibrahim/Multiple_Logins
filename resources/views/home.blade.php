@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                @if (Auth::user()->type === 2 ||Auth::user()->type === 3)
                <div class="panel-body">
                    Welcome {{Auth::user()->firstname}}
                </div>
                @endif

                @if (Auth::user()->type === 1)
                    <div class="panel-body">
                    <h4>Welcome {{Auth::user()->firstname}}, here you can add musicians to be members in your orcherstra</h4>
                    <br>
                    @if (Session::has('message'))
                       <div class="alert alert-info">{{ Session::get('message') }}</div>
                    @endif
                    @if (Session::has('Error'))
                       <div class="alert alert-danger">{{ Session::get('Error') }}</div>
                    @endif
                    <form class="form-horizontal" method="POST" action="/AddMember">
                    {{csrf_field()}}
                    <input type="hidden" name="orchestra_name" value="{{Auth::user()->orchestra_name}}">
                        <div class="form-group">
                        <label for="member" class="col-md-4 control-label">Musicians list (ID:Full Name)</label>
                        <div class="col-md-6">
                            <select name="member" id="member" class="form-control">
                                
                                @if(count($users) > 0)
                                    @foreach($users as $user)
                                    <option>{{$user->id}}:{{$user->firstname}} {{$user->surname}}</option>
                                    @endforeach
                                @else
                                    <option>No Musicians available</option>
                                @endif
                                
                            </select>
                        </div>
                        </div>
                        @if(count($users) > 0)
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Add
                                </button>
                            </div>
                        </div>
                        @endif
                    </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
