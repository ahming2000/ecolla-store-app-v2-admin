@extends('layouts.app')

@section('title')
    应用更新日志
@endsection

@section('content')
    <main class="container">
        <div class="row">

            <div class="col-12 text-center">当前版本</div>
            <div class="col-12 text-center" style="font-size: 30px; color: {{ $color }}">{{ $currentVersion }}</div>
            <div class="col-12 text-center mb-5">更新日期：{{ $updateDate }}</div>

            <div class="col-md-6 offset-md-3 col-sm-12">
                <div class="h2 mb-3">更新日志</div>
                @foreach($logs as $log)
                    <div class="mb-3">
                        <div class="h3">{{ $log['version'] }} [{{ $log['date'] }}]</div>
                        @foreach($log['desc'] as $desc)
                            <span>- {{ $desc }}</span><br>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </main>
@endsection
