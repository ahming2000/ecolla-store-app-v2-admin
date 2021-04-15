@extends('layouts.app')

@section('title')
    创建商品
@endsection

@section('content')
    <main class="container">

        <div class="row">
            <div class="col-xs-12 col-sm-8 offset-sm-2">
                <div class="h1 text-center mb-3">请输入商品名字</div>
                <form action="{{ url('/item/create') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                               aria-describedby="i-name" maxlength="250" value="{{ old('name') }}"/>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" name="submit">创建</button>
                    </div>
                </form>

            </div>

        </div>

    </main>
@endsection
