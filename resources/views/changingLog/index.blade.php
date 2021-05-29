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
                <div class="accordion" id="version-list">
                    @foreach($logs as $logClass)
                        <div class="card">
                            <div class="card-header" id="heading-{{ $logClass->getPlainClassName() }}">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed p-0 pl-3" type="button"
                                            data-toggle="collapse" data-target="#{{ $logClass->getPlainClassName() }}"
                                            aria-expanded="false" aria-controls="{{ $logClass->getPlainClassName() }}">
                                        <span style="font-size: large">{{ $logClass->class }}</span>
                                    </button>
                                </h2>
                            </div>

                            <div id="{{ $logClass->getPlainClassName() }}" class="collapse"
                                 aria-labelledby="heading-{{ $logClass->getPlainClassName() }}"
                                 data-parent="#version-list">
                                <div class="card-body">
                                    @foreach($logClass->logs as $log)
                                        <div class="mb-3">
                                            <div class="h4">{{ $log->version }}（{{ $log->date }}）</div>
                                            @foreach($log->details as $logDetail)
                                                <div class="mb-2">
                                                    <div class="h6">{{ $logDetail->category }}</div>
                                                    @foreach($logDetail->contents as $detail)
                                                        <span>-{{ $detail }}</span><br>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </main>
@endsection

@section('extraScriptEnd')
    <script>
        $(document).ready(function () {
            // Show the first child
            let selector = $('#version-list').children('.card').first();
            selector.find('button').removeClass('collapsed');
            selector.find('.collapse').addClass('show');
        });
    </script>
@endsection
