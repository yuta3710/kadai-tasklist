@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $user->name }}</h3>
                </div>
            </div>
        </aside>
        
        <div class="col-sm-8">
            {{-- ステータス一覧 --}}
            @include('tasks.tasks')
        </div>
        
        <div class="col-sm-8">
            {{-- タスク一覧 --}}
            @include('tasks.tasks')
        </div>
    </div>
@endsection