@extends('layouts.app')

@section('title')
    网站设定
@endsection

@section('content')
    <main class="container">

        @if(session()->has('message'))
            <div class="alert alert-info text-center" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div class="h1">网站设定</div>
            @if(auth()->user()->hasAccess('setting_item'))
        <div class="row">
            <div class="col-sm-12 col-md-8 offset-md-2 category-section">
                <div class="h2">商品类别管理</div>
                <input type="hidden"
                       value="{{ sizeof($categories->toArray()) > 1 ? sizeof($categories->toArray()) : '1' }}"
                       id="currentCategoryCount">

                <div id="default-category-section">
                    @for($i = 0; $i < $defaultCategoryCount; $i++)
                        <div class="row">
                            <div class="col-11 mb-1 mr-0 pr-0">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 pr-md-1">
                                        <input type="text"
                                               class="form-control"
                                               value="{{ $categories[$i]->name ?? "" }}"
                                               disabled>
                                    </div>
                                    <div class="col-md-6 col-sm-12 pl-md-1">
                                        <input type="text"
                                               class="form-control"
                                               value="{{ $categories[$i]->name_en ?? "" }}"
                                               disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-1 mb-1 ml-0 pl-0">
                                <button type="button"
                                        class="btn default-color white-text btn-sm remove-button px-3 py-1"
                                        disabled>
                                    X
                                </button>
                            </div>
                        </div>
                    @endfor
                </div>

                <form method="post" action="{{ url('/setting/update/category') }}">

                    @csrf
                    @method('PATCH')

                    <div id="category-section">

                        @if(!empty(old('category')))
                            @for($i = 0; $i < sizeof(old('category')); $i++)
                                <div class="row category-item">
                                    <div class="col-11 mb-1 mr-0 pr-0">
                                        <div class="row">
                                            <input type="hidden" name="category[{{$i}}][id]"
                                                   value="{{ old("category.$i.id") ?? "" }}">
                                            <div class="col-md-6 col-sm-12 pr-md-1">
                                                <input type="text"
                                                       class="form-control @error("category.$i.name") is-invalid @enderror"
                                                       name="category[{{$i}}][name]"
                                                       value="{{ old("category.$i.name") ?? "" }}"
                                                       placeholder="华文">

                                                @error("category.$i.name")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 col-sm-12 pl-md-1">
                                                <input type="text"
                                                       class="form-control @error("category.$i.name_en") is-invalid @enderror"
                                                       name="category[{{$i}}][name_en]"
                                                       value="{{ old("category.$i.name_en") ?? "" }}"
                                                       placeholder="英文">

                                                @error("category.$i.name_en")
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-1 mb-1 ml-0 pl-0">
                                        <button type="button"
                                                class="btn default-color white-text btn-sm remove-button px-3 py-1">
                                            X
                                        </button>
                                    </div>
                                </div>
                            @endfor
                        @else
                            @for($i = $defaultCategoryCount; $i < sizeof($categories->toArray()); $i++)
                                <div class="row category-item">
                                    <div class="col-11 mb-1 mr-0 pr-0">
                                        <div class="row">
                                            <input type="hidden" name="category[{{$i - $defaultCategoryCount}}][id]"
                                                   value="{{ $categories[$i]->id }}">
                                            <div class="col-md-6 col-sm-12 pr-md-1">
                                                <input type="text"
                                                       class="form-control"
                                                       name="category[{{$i - $defaultCategoryCount}}][name]"
                                                       value="{{ $categories[$i]->name ?? "" }}"
                                                       placeholder="华文">
                                            </div>
                                            <div class="col-md-6 col-sm-12 pl-md-1">
                                                <input type="text"
                                                       class="form-control"
                                                       name="category[{{$i - $defaultCategoryCount}}][name_en]"
                                                       value="{{ $categories[$i]->name_en ?? "" }}"
                                                       placeholder="英文">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-1 mb-1 ml-0 pl-0">
                                        <button type="button"
                                                class="btn default-color white-text btn-sm remove-button px-3 py-1">
                                            X
                                        </button>
                                    </div>
                                </div>
                            @endfor
                        @endif
                    </div>

                    <div class="row d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary mb-3" id="extra-category-button">
                            添加更多类别
                        </button>
                    </div>

                    <div class="row d-flex justify-content-center">
                        <button class="btn btn-primary">保存</button>
                    </div>
                </form>
            </div>
        </div>
                @endif
    </main>
@endsection

@section('extraScriptEnd')
    @if(auth()->user()->hasAccess('setting_item'))

        <script>

        function getCategoryCount() {
            return $('#category-section div.category-item').length;
        }

        function getExtraCategoryHTML(categoryCount) {
            return `
            <div class="row category-item">
            <input type="hidden" name="category[${categoryCount}][id]" value="">
                <div class="col-11 mb-1 mr-0 pr-0">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 pr-md-1">
                            <input type="text"
                                   class="form-control"
                                   name="category[${categoryCount}][name]"
                                   placeholder="华文">
                        </div>
                        <div class="col-md-6 col-sm-12 pl-md-1">
                            <input type="text"
                                   class="form-control"
                                   name="category[${categoryCount}][name_en]"
                                   placeholder="英文">
                        </div>
                    </div>
                </div>
                <div class="col-1 mb-1 ml-0 pl-0">
                    <button type="button"
                            class="btn default-color white-text btn-sm remove-button px-3 py-1">
                        X
                    </button>
                </div>
            </div>
            `;
        }

        $(document).on("click", ".remove-button", function () {
            $(this).closest('.category-item').html('');

            let currentCategoryCountSelector = $('#currentCategoryCount');
            if (currentCategoryCountSelector.val() === 1) {
                $('#category-section').append(getExtraCategoryHTML(getCategoryCount()));
            } else {
                currentCategoryCountSelector.val(parseInt(currentCategoryCountSelector.val()) - 1);
            }
        });

        $(document).ready(function () {
            $('#extra-category-button').on('click', function () {
                $('#category-section').append(getExtraCategoryHTML(getCategoryCount()));
            });
        });
    </script>
    @endif
@endsection
