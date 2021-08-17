@extends('layouts.app')

@section('title')
    创建商品
@endsection

@section('content')
    <main class="container">
        <div class="h1 text-center">请输入商品名字</div>

        <form action="{{ url('/item') }}" method="post">
            @csrf

            <div class="mb-2">
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name" maxlength="250" value="{{ old('name') }}">

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">创建商品</button>
            </div>
        </form>
    </main>
@endsection
