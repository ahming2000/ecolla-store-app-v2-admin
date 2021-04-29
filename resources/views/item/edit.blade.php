@extends('layouts.app')

@section('title')
    编辑 {{ $item->name }}
@endsection

@section('extraStyle')
    <style>
        .img-upload-container {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .img-upload-overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            width: 100%;
            opacity: 0;
            transition: .3s ease;
            background-color: grey;
        }


        .img-upload-container:hover .img-upload-overlay {
            opacity: 1.0;
        }

        .icofont-ui-delete:hover .icofont-edit:hover {
            color: #eee;
        }

        .img-upload-overlay-icon {
            color: white;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }

        .remove-img-button {
            font-size: 20px;
            position: absolute;
            top: 50%;
            right: 15%;
        }

        .edit-img-button {
            font-size: 20px;
            position: absolute;
            top: 50%;
            left: 30%;
        }
    </style>
@endsection

@section('content')
    <main class="container">

        <!-- Page content with row class -->
        <div class="row">

            <div class="col-sm-12 col-md-10">
                <form action="{{ url('/item/' . $item->id . '/edit') }}" method="post" enctype="multipart/form-data">

                    @csrf

                    <div class="h1">商品属性编辑</div>

                    <div class="h2" id="step-one">基本资讯</div>

                    <div class="row">
                        <!-- Name -->
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xs-2 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                                    <label for="name">商品名称：</label>
                                </div>

                                <div class="col-xs-10 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 pr-md-1">
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   name="name"
                                                   aria-describedby="name"
                                                   maxlength="250"
                                                   value="{{ old('name') ?? $item->name ?? "" }}"
                                                   placeholder="华文"
                                            >

                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 col-sm-12 pl-md-1">
                                            <input type="text"
                                                   class="form-control @error('name_en') is-invalid @enderror"
                                                   name="name_en"
                                                   aria-describedby="name_en"
                                                   maxlength="250"
                                                   value="{{ old('name_en') ?? $item->name_en ?? "" }}"
                                                   placeholder="英文"
                                            >

                                            @error('name_en')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <!-- Name -->

                        <!-- Description -->
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xs-2 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                                    <label for="desc">商品描述：</label>
                                </div>

                                <div class="col-xs-10 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">
                                    <textarea class="form-control @error('desc') is-invalid @enderror" name="desc"
                                              aria-describedby="desc" rows="5"
                                              maxlength="3000">{{ old('desc') ?? $item->desc ?? "" }}</textarea>

                                    @error('desc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Description -->

                        <!-- Origin -->
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xs-2 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                                    <label for="origin">出产地：</label>
                                </div>

                                <div class="col-xs-10 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">

                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 pr-md-1">
                                            <input type="text"
                                                   class="form-control @error('origin') is-invalid @enderror"
                                                   name="origin"
                                                   aria-describedby="origin"
                                                   value="{{ old('origin') ?? $item->origin ?? "" }}"
                                                   placeholder="华文"
                                            >

                                            @error('origin')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 col-sm-12 pl-md-1">
                                            <input type="text"
                                                   class="form-control @error('origin_en') is-invalid @enderror"
                                                   name="origin_en"
                                                   aria-describedby="origin_en"
                                                   value="{{ old('origin_en') ?? $item->origin_en ?? "" }}"
                                                   placeholder="英文"
                                            >

                                            @error('origin_en')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Origin -->

                        <!-- Brand -->
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xs-2 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                                    <label for="brand">商品品牌：</label>
                                </div>

                                <div class="col-xs-10 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 pr-md-1">
                                            <input type="text"
                                                   class="form-control @error('brand') is-invalid @enderror"
                                                   name="brand"
                                                   aria-describedby="brand"
                                                   value="{{ old('brand') ?? $item->brand ?? "" }}"
                                                   placeholder="华文"
                                            >

                                            @error('brand')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 col-sm-12 pl-md-1">
                                            <input type="text"
                                                   class="form-control @error('brand_en') is-invalid @enderror"
                                                   name="brand_en"
                                                   aria-describedby="brand_en"
                                                   value="{{ old('brand_en') ?? $item->brand_en ?? "" }}"
                                                   placeholder="英文"
                                            >

                                            @error('brand_en')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Brand -->

                        <!-- Category -->
                        <div class="col-12">
                            <!-- Current category list -->
                            <datalist id="category-list">
                                @foreach($categories as $category)
                                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                                @endforeach
                            </datalist>
                            <!-- Current category list -->

                            <!-- Category field -->
                            <div class="row">
                                <div class="col-xs-2 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                                    <label for="categories">商品类别/标签：</label>
                                </div>

                                <div class="col-xs-10 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">
                                    <div id="category-section">
                                        @if(!empty(old('categories')))
                                            <input type="hidden" value="{{ sizeof(old('categories')) }}"
                                                   id="currentCategoryCount">
                                            @for($i = 0; $i < sizeof(old('categories')); $i++)
                                                <div class="row category-item">
                                                    <div class="col-11 mb-1 mr-0 pr-0">
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12 pr-md-1">
                                                                <input type="text"
                                                                       class="form-control @error('categories.' . $i . '.name') is-invalid @enderror"
                                                                       name="categories[{{$i}}][name]"
                                                                       aria-describedby="categories"
                                                                       list="category-list"
                                                                       maxlength="20"
                                                                       placeholder="中文"
                                                                       value="{{ old('categories.' . $i . '.name') ?? "" }}"
                                                                >

                                                                @error('categories.' . $i . '.name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6 col-sm-12 pl-md-1">
                                                                <input type="text"
                                                                       class="form-control @error('categories.' . $i . '.name_en') is-invalid @enderror"
                                                                       name="categories[{{$i}}][name_en]"
                                                                       aria-describedby="categories"
                                                                       list="category-list"
                                                                       maxlength="20"
                                                                       placeholder="英文"
                                                                       value="{{ old('categories.' . $i . '.name_en') ?? "" }}"
                                                                >

                                                                @error('categories.' . $i . '.name_en')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-1 mb-1 ml-0 pl-0">
                                                        <button type="button"
                                                                class="btn default-color white-text btn-sm remove-button category-remove-button px-3 py-1">
                                                            X
                                                        </button>
                                                    </div>
                                                </div>
                                            @endfor
                                        @elseif(!empty($item->categories->toArray()))
                                            <input type="hidden" value="{{ sizeof($item->categories) }}"
                                                   id="currentCategoryCount">
                                            @for($i = 0; $i < sizeof($item->categories); $i++)
                                                <div class="row category-item">
                                                    <div class="col-11 mb-1 mr-0 pr-0">
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12 pr-md-1">
                                                                <input type="text"
                                                                       class="form-control"
                                                                       name="categories[{{$i}}][name]"
                                                                       aria-describedby="categories"
                                                                       list="category-list"
                                                                       maxlength="20"
                                                                       placeholder="中文"
                                                                       value="{{ $item->categories[$i]->name ?? "" }}"
                                                                >
                                                            </div>

                                                            <div class="col-md-6 col-sm-12 pl-md-1">
                                                                <input type="text"
                                                                       class="form-control"
                                                                       name="categories[{{$i}}][name_en]"
                                                                       aria-describedby="categories"
                                                                       list="category-list"
                                                                       maxlength="20"
                                                                       placeholder="英文"
                                                                       value="{{ $item->categories[$i]->name_en ?? "" }}"
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-1 mb-1 ml-0 pl-0">
                                                        <button type="button"
                                                                class="btn default-color white-text btn-sm remove-button category-remove-button px-3 py-1">
                                                            X
                                                        </button>
                                                    </div>
                                                </div>
                                            @endfor
                                        @else
                                            <input type="hidden" value="1" id="currentCategoryCount">
                                            <div class="row category-item">
                                                <div class="col-11 mb-1 mr-0 pr-0">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12 pr-md-1">z
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="categories[name][0]"
                                                                   aria-describedby="categories"
                                                                   list="category-list"
                                                                   maxlength="20"
                                                                   placeholder="中文"
                                                            >
                                                        </div>

                                                        <div class="col-md-6 col-sm-12 pl-md-1">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="categories[name_en][0]"
                                                                   aria-describedby="categories"
                                                                   list="category-list"
                                                                   maxlength="20"
                                                                   placeholder="英文"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-1 mb-1 ml-0 pl-0">
                                                    <button type="button"
                                                            class="btn default-color white-text btn-sm remove-button category-remove-button px-3 py-1">
                                                        X
                                                    </button>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Category -->

                        <!-- Add extra category button -->
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-secondary" id="extra-category-button">
                                添加更多类别/标签
                            </button>
                        </div>
                        <!-- Add extra category button -->

                        <div class="h2" id="step-two">规格资讯</div>

                        <!-- Variation Name -->
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xs-2 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                                    <label for="variations">规格：</label>
                                </div>

                                <div class="col-xs-10 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">
                                    <div id="variation-section">
                                        @if(!empty(old('variations')))
                                            <input type="hidden"
                                                   value="{{ sizeof(old('variations')) }}"
                                                   id="currentVariationCount"
                                            >

                                            @for($i = 0; $i < sizeof($item->variations); $i++)
                                                <div class="row variation-item">
                                                    <div class="col-11 mb-1 mr-0 pr-0">
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12 pr-md-1">
                                                                <input type="text"
                                                                       class="form-control variation-name @error('variations.' . $i . '.name1') is-invalid @enderror"
                                                                       name="variations[{{ $i }}][name1]"
                                                                       aria-describedby="variations"
                                                                       maxlength="100"
                                                                       value="{{ old('variations.' . $i . '.name1') ?? "" }}"
                                                                >

                                                                @error('variations.' . $i . '.name1')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6 col-sm-12 pl-md-1">
                                                                <input type="text"
                                                                       class="form-control variation-name @error('variations.' . $i . '.name2') is-invalid @enderror"
                                                                       name="variations[{{ $i }}][name2]"
                                                                       aria-describedby="variations" maxlength="100"
                                                                       value="{{ old('variations.' . $i . '.name2') ?? "" }}"
                                                                >

                                                                @error('variations.' . $i . '.name2')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-1 mb-1 ml-0 pl-0">
                                                        <button type="button"
                                                                class="btn default-color white-text btn-sm remove-button variation-remove-button px-3 py-1">
                                                            X
                                                        </button>
                                                    </div>
                                                </div>
                                            @endfor
                                        @elseif(!empty($item->variations->toArray()))
                                            <input type="hidden"
                                                   value="{{ sizeof($item->variations) }}"
                                                   id="currentVariationCount"
                                            >
                                            @for($i = 0; $i < sizeof($item->variations); $i++)
                                                <div class="row variation-item">
                                                    <div class="col-11 mb-1 mr-0 pr-0">
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12 pr-md-1">
                                                                <input type="text"
                                                                       class="form-control variation-name"
                                                                       name="variations[{{ $i }}][name1]"
                                                                       aria-describedby="variations"
                                                                       maxlength="100"
                                                                       value="{{ $item->variations[$i]->name1 ?? "" }}"
                                                                >
                                                            </div>
                                                            <div class="col-md-6 col-sm-12 pl-md-1">
                                                                <input type="text"
                                                                       class="form-control variation-name"
                                                                       name="variations[{{ $i }}][name2]"
                                                                       aria-describedby="variations"
                                                                       maxlength="100"
                                                                       value="{{ $item->variations[$i]->name2 ?? "" }}"
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-1 mb-1 ml-0 pl-0">
                                                        <button type="button"
                                                                class="btn default-color white-text btn-sm remove-button variation-remove-button px-3 py-1">
                                                            X
                                                        </button>
                                                    </div>
                                                </div>
                                            @endfor
                                        @else
                                            <input type="hidden" value="1" id="currentVariationCount">
                                            <div class="row variation-item">
                                                <div class="col-11 mb-1 mr-0 pr-0">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-12 pr-md-1">
                                                            <input type="text"
                                                                   class="form-control variation-name"
                                                                   name="variations[0][name1]"
                                                                   aria-describedby="variations"
                                                                   maxlength="100"
                                                            >
                                                        </div>
                                                        <div class="col-md-6 col-sm-12 pl-md-1">
                                                            <input type="text"
                                                                   class="form-control variation-name"
                                                                   name="variations[0][name2]"
                                                                   aria-describedby="variations"
                                                                   maxlength="100"
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-1 mb-1 ml-0 pl-0">
                                                    <button type="button"
                                                            class="btn default-color white-text btn-sm remove-button variation-remove-button px-3 py-1">
                                                        X
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Variation Name -->

                        <!-- Add extra variation button -->
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-secondary mb-3" id="extra-variation-button">
                                添加更多规格
                            </button>
                        </div>
                        <!-- Add extra variation button -->

                        <!-- Variation Detail -->
                        <div class="col-12">
                            <label for="variations">规格销售</label>
                        </div>
                        <div class="col-12 mb-3">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">名称</th>
                                    <th scope="col">货号</th>
                                    <th scope="col">价格(RM)</th>
                                    <th scope="col">重量(kg)</th>
                                </tr>
                                </thead>

                                <tbody id="variation-table-section">
                                @if(!empty(old('variations')))
                                    @for($i = 0; $i < sizeof($item->variations); $i++)
                                        <tr class="variation-table-item">
                                            <td>
                                                <input type="text"
                                                       class="form-control variation-table-variation-name-display"
                                                       value="{{ (old("variation.$i.name1") ?? "") . (old("variation.$i.name2") ?? "") }}"
                                                       disabled
                                                >
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control"
                                                       name="variations[{{ $i }}][barcode]"
                                                       maxlength="20"
                                                       value="{{ old('variations.' . $i . '.barcode') ?? $item->variations[$i]->barcode ?? "" }}"
                                                >
                                            </td>
                                            <td><input type="number"
                                                       step="0.01"
                                                       min="0"
                                                       class="form-control variation-price-display"
                                                       name="variations[{{ $i }}][price]"
                                                       value="{{ old('variations.' . $i . '.price') ?? number_format($item->variations[$i]->price, 2, '.', '') ?? "" }}"
                                                >
                                            </td>
                                            <td><input type="number"
                                                       step="0.001"
                                                       min="0"
                                                       class="form-control"
                                                       name="variations[{{ $i }}][weight]"
                                                       value="{{ old('variations.' . $i . '.weight') ?? number_format($item->variations[$i]->weight, 3, '.', '') ?? "" }}"
                                                >
                                            </td>
                                        </tr>
                                    @endfor
                                @elseif(!empty($item->variations->toArray()))
                                    @for($i = 0; $i < sizeof($item->variations); $i++)

                                        <tr class="variation-table-item">
                                            <td>
                                                <input type="text"
                                                       class="form-control variation-table-variation-name-display"
                                                       value="{{ $item->variations[$i]->name1 . $item->variations[$i]->name2 }}"
                                                       disabled
                                                >
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control"
                                                       name="variations[{{ $i }}][barcode]"
                                                       maxlength="20"
                                                       value="{{ $item->variations[$i]->barcode ?? "" }}"
                                                >
                                            </td>
                                            <td><input type="number"
                                                       step="0.01"
                                                       min="0"
                                                       class="form-control variation-price-display"
                                                       name="variations[{{ $i }}][price]"
                                                       value="{{ number_format($item->variations[$i]->price, 2, '.', '') ?? "" }}"
                                                >
                                            </td>
                                            <td><input type="number"
                                                       step="0.001"
                                                       min="0"
                                                       class="form-control"
                                                       name="variations[{{ $i }}][weight]"
                                                       value="{{ number_format($item->variations[$i]->weight, 3, '.', '') ?? "" }}"
                                                >
                                            </td>
                                        </tr>
                                    @endfor
                                @else
                                    <tr class="variation-table-item">
                                        <td>
                                            <input type="text"
                                                   class="form-control variation-table-variation-name-display"
                                                   disabled
                                            >
                                        </td>
                                        <td>
                                            <input type="text"
                                                   class="form-control"
                                                   name="variations[0][barcode]"
                                                   maxlength="20"
                                            >
                                        </td>
                                        <td><input type="number"
                                                   step="0.01"
                                                   min="0"
                                                   class="form-control variation-price-display"
                                                   name="variations[0][price]"
                                            >
                                        </td>
                                        <td><input type="number"
                                                   step="0.001"
                                                   min="0"
                                                   class="form-control"
                                                   name="variations[0][weight]"
                                            >
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- Variation Detail -->

                        <!-- Inventory -->
                        <div class="col-12">
                            <label for="inventories">规格库存</label>
                        </div>
                        <div class="col-12 mb-3">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">规格</th>
                                    <th scope="col">数量</th>
                                </tr>
                                </thead>

                                <tbody id="inventory-table-section">
                                @if(!empty(old('variations')))
                                    @for($i = 0; $i < sizeof(old('variations')); $i++)
                                        <tr class="inventory-table-item">
                                            <td>
                                                <input type="text"
                                                       class="form-control inventory-table-variation-name-display"
                                                       value="{{ (old("variation.$i.name1") ?? "") . (old("variation.$i.name2") ?? "") }}"
                                                       disabled
                                                >
                                            </td>
                                            <td>
                                                <input type="number"
                                                       min="0"
                                                       class="form-control"
                                                       name="variations[{{$i}}][inventories][0][stock]"
                                                       value="{{ old("variations.$i.inventories.0.stock") ?? 0 }}"
                                                >
                                            </td>
                                        </tr>
                                    @endfor
                                @elseif(!empty($item->variations->toArray()))
                                    @for($i = 0; $i < sizeof($item->variations); $i++)
                                        <tr class="inventory-table-item">
                                            <td>
                                                <input type="text"
                                                       class="form-control inventory-table-variation-name-display"
                                                       value="{{ $item->variations[$i]->name1 . $item->variations[$i]->name2 ?? "" }}"
                                                       disabled
                                                >
                                            </td>
                                            <td>
                                                <input type="number"
                                                       min="0"
                                                       class="form-control"
                                                       name="variations[{{$i}}][inventories][0][stock]"
                                                       value="{{ $item->variations[$i]->inventories->toArray()[0]['stock'] ?? 0 }}"
                                                >
                                            </td>
                                        </tr>
                                    @endfor
                                @else
                                    <tr class="inventory-table-item">
                                        <td>
                                            <input type="text"
                                                   class="form-control inventory-table-variation-name-display"
                                                   disabled
                                            >
                                        </td>
                                        <td>
                                            <input type="number"
                                                   min="0"
                                                   class="form-control"
                                                   name="variations[0][inventories][0][stock]"
                                            >
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- Inventory -->


                        <div class="h2" id="step-three">折扣管理</div>

                        <!-- Variation discount -->
                        <div class="col-12">
                            <label>规格折扣</label>
                        </div>
                        <div class="col-12 mb-3">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">规格名称</th>
                                    <th scope="col">开始日期</th>
                                    <th scope="col">结束日期</th>
                                    <th scope="col">折扣价钱</th>
                                </tr>
                                </thead>
                                <tbody id="variation-discount-table-section">

                                </tbody>
                            </table>
                        </div>
                        <!-- Variation discount -->


                        <!-- Wholesale discount -->
                        {{--                        <div class="col-12 wholesale-section">--}}
                        {{--                            <label>批发价</label>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="col-12 mb-3 wholesale-section" style="overflow-x: scroll;">--}}
                        {{--                            <table class="table table-bordered">--}}
                        {{--                                <thead>--}}
                        {{--                                <tr>--}}
                        {{--                                    <th scope="col">开始数量</th>--}}
                        {{--                                    <th scope="col">结束数量</th>--}}
                        {{--                                    <th scope="col">价格(RM)</th>--}}
                        {{--                                    <th scope="col">操作</th>--}}
                        {{--                                </tr>--}}
                        {{--                                </thead>--}}

                        {{--                                <tbody id="wholesale-table-section">--}}
                        {{--                                <tr>--}}
                        {{--                                    <td><input type="number" class="form-control mb-1 w-min" min="1" name="w[0][w_min]"--}}
                        {{--                                               aria-describedby="w-min"/></td>--}}
                        {{--                                    <td><input type="number" class="form-control mb-1 w-max" min="1" name="w[0][w_max]"--}}
                        {{--                                               aria-describedby="w-max" disabled/></td>--}}
                        {{--                                    <td><input type="number" class="form-control mb-1 w-price" step="0.01" min="0.01"--}}
                        {{--                                               name="w[0][w_price]" aria-describedby="w-price"/></td>--}}
                        {{--                                    <td>--}}
                        {{--                                        <button type="button"--}}
                        {{--                                                class="btn default-color white-text btn-sm remove-button wholesale-remove-button px-3 py-1">--}}
                        {{--                                            X--}}
                        {{--                                        </button>--}}
                        {{--                                    </td>--}}
                        {{--                                </tr>--}}
                        {{--                                <?php $wholesaleCount = sizeof($item->getWholesales()); ?>--}}
                        {{--                                <?php if ($wholesaleCount == 0) : ?>--}}
                        {{--                                <?php else : ?>--}}
                        {{--                                <?php $maxPrice = $item->getVarieties()[0]->getPrice(); ?>--}}
                        {{--                                <?php for($i = 0; $i < $wholesaleCount; $i++) : ?>--}}
                        {{--                                <?php $discountedPrice = number_format($item->getVarieties()[0]->getPrice() * $item->getWholesales()[$i]->getDiscountRate(), 2, '.', ''); ?>--}}
                        {{--                                <tr>--}}
                        {{--                                    <td><input type="number" class="form-control mb-1 w-min" min="1" name="w[<?= $i; ?>][w_min]" aria-describedby="w-min" value="<?= $item->getWholesales()[$i]->getMin(); ?>" <?= $i != 0 ? "disabled" : ""; ?>/></td>--}}
                        {{--                                    <td><input type="number" class="form-control mb-1 w-max" min="<?= $item->getWholesales()[$i]->getMin(); ?>" name="w[<?= $i; ?>][w_max]" aria-describedby="w-max" value="<?= $item->getWholesales()[$i]->getMax(); ?>" <?= ($i == ($wholesaleCount - 1)) ? "disabled" : ""; ?>/></td>--}}
                        {{--                                    <td><input type="number" class="form-control mb-1 w-price" step="0.01" min="0.01" max="<?= $maxPrice ?>" name="w[<?= $i; ?>][w_price]" aria-describedby="w-price" value="<?= $discountedPrice ?>"/></td>--}}
                        {{--                                    <td><button type="button" class="btn default-color white-text btn-sm remove-button wholesale-remove-button px-3 py-1" <?= $i != $wholesaleCount - 1 ? "disabled" : ""; ?>>X</button></td>--}}
                        {{--                                </tr>--}}
                        {{--                                <?php $maxPrice = $discountedPrice; ?>--}}
                        {{--                                <?php endfor; ?>--}}
                        {{--                                <?php endif; ?>--}}
                        {{--                                </tbody>--}}
                        {{--                            </table>--}}
                        {{--                            <!-- Add extra wholesales button -->--}}
                        {{--                            <div class="text-center">--}}
                        {{--                                <button type="button" class="btn btn-secondary mt-1" id="extra-wholesale-button">--}}
                        {{--                                    添加更多批发价--}}
                        {{--                                </button>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <!-- Wholesale discount -->


                        <div class="h2" id="step-four">媒体管理</div>

                        <!-- General item image -->
                        <div class="col-12">
                            <label>基本照片</label>
                            <i class="icofont icofont-ui-add" id="add-general-image-button"></i>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="row" id="general-image-section">
                                @if(empty($item->images->toArray()))
                                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 general-image-item">
                                        <input type="file"
                                               name="item-image[0]"
                                               class="image-file-selector"
                                               style="display:none;"
                                        >
                                        <figure class="figure">
                                            <div class="img-upload-container">
                                                <img class="img-fluid image-preview w-100"
                                                     src="{{ asset('img/alt/image-upload-alt.png') }}"
                                                >
                                                <div class="img-upload-overlay">
                                                    <div class="img-upload-overlay-icon edit-img-button"
                                                         title="Upload Image"
                                                         onclick="uploadImage(this)"
                                                    >
                                                        <i class="icofont icofont-edit"></i>
                                                    </div>
                                                    <div class="img-upload-overlay-icon remove-img-button"
                                                         title="Remove Image"
                                                         onclick="removeImage(this)"
                                                    >
                                                        <i class="icofont icofont-ui-delete"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </figure>
                                    </div>
                                @else
                                    @for($i = 0; $i < sizeof($item->images); $i++)
                                        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 general-image-item">
                                            <input type="file"
                                                   name="item-image[{{$i}}]"
                                                   class="image-file-selector"
                                                   style="display:none;"
                                            >
                                            <figure class="figure">
                                                <div class="img-upload-container">
                                                    <img class="img-fluid image-preview"
                                                         src="{{ asset($item->images[$i]->image ?? 'img/alt/image-upload-alt.png') }}"
                                                    >
                                                    <div class="img-upload-overlay">
                                                        <div class="img-upload-overlay-icon edit-img-button"
                                                             title="Upload Image"
                                                             onclick="uploadImage(this)"
                                                        >
                                                            <i class="icofont icofont-edit"></i>
                                                        </div>
                                                        <div class="img-upload-overlay-icon remove-img-button"
                                                             title="Remove Image"
                                                             onclick="removeImage(this)"
                                                        >
                                                            <i class="icofont icofont-ui-delete"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </figure>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        </div>
                        <!-- General item image -->

                        <!-- Variation image -->
                        <div class="col-12">
                            <label>规格照片</label>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="row" id="variation-image-section">
                                @if(!empty($item->variations))
                                    @for($i = 0; $i < sizeof($item->variations->toArray()); $i++)
                                        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 variation-image-item">
                                            <input type="file"
                                                   name="variations[image][{{$i}}]"
                                                   class="image-file-selector"
                                                   style="display:none;"
                                            >
                                            <figure class="figure">
                                                <div class="img-upload-container">
                                                    <img class="img-fluid image-preview w-100"
                                                         src="{{ asset($item->variations[$i]->image ?? "img/alt/image-upload-alt.png") }}"
                                                    >
                                                    <div class="img-upload-overlay">
                                                        <div class="img-upload-overlay-icon edit-img-button"
                                                             title="Upload Image"
                                                             onclick="uploadImage(this)"
                                                        >
                                                            <i class="icofont-edit"></i>
                                                        </div>
                                                        <div class="img-upload-overlay-icon remove-img-button"
                                                             title="Remove Image"
                                                             onclick="removeImage(this)"
                                                        >
                                                            <i class="icofont-ui-delete"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <figcaption class="figure-caption text-center">
                                                    {{ $item->variations[$i]->name1 . $item->variations[$i]->name2 }}
                                                </figcaption>
                                            </figure>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        </div>
                        <!-- Variation image -->


                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="h2" id="step-five">其他商品设定</div>
                                <br>
                                <div class="col-12">
                                    <div class="mx-auto">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th scope="col">设定名称</th>
                                                <th scope="col">数值</th>
                                                <th scope="col">操作</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            <tr>
                                                <td>商品浏览次数</td>
                                                <td><input type="text" class="form-control form-control-sm"
                                                           value="{{ $item->util->view_count }}" disabled/></td>
                                                <td>
                                                    <button type="submit" class="btn btn-primary btn-sm"
                                                            name="reset-view-count-button">重置
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>


                        </div>

                        <div class="col-12 text-center mb-3">
                            <input class="btn btn-primary mr-2" type="submit" value="保存" name="save">
                        </div>

                    </div>

                </form>
            </div>

            <!-- Navigation guideline -->
            <div class="col-sm-0 col-md-2">
                <div style="position: fixed;" id="menu-list">
                    <ul class="list-group text-center">
                        <a href="#step-one" id="step-one-link"
                           class="item-create-step-info list-group-item list-group-item-action active">基本资讯</a>
                        <a href="#step-two" id="step-two-link"
                           class="item-create-step-info list-group-item list-group-item-action">销售资料</a>
                        <a href="#step-three" id="step-three-link"
                           class="item-create-step-info list-group-item list-group-item-action">折扣管理</a>
                        <a href="#step-four" id="step-four-link"
                           class="item-create-step-info list-group-item list-group-item-action">媒体管理</a>
                        <a href="#step-five" id="step-five-link"
                           class="item-create-step-info list-group-item list-group-item-action">其他商品设定</a>
                    </ul>
                </div>
            </div><!-- Navigation guideline -->

        </div><!-- Page content with row class -->

    </main>



@endsection


@section('extraScriptEnd')
    <script>

        // Category js
        function getCategoryCount() {
            return $("#category-section div.category-item").length;
        }

        function getExtraCategoryHTML(categoryCount) {
            return `
            <div class="row category-item">
                <div class="col-11 mb-1 mr-0 pr-0">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 pr-md-1">
                            <input type="text" class="form-control" name="categories[name][${categoryCount}]" aria-describedby="categories" list="category-list" maxlength="20" placeholder="中文">
                        </div>

                        <div class="col-md-6 col-sm-12 pl-md-1">
                            <input type="text" class="form-control" name="categories[name_en][${categoryCount}]" aria-describedby="categories" list="category-list" maxlength="20" placeholder="英文">
                        </div>
                    </div>
                </div>

                <div class="col-1 mb-1 ml-0 pl-0">
                    <button type="button" class="btn default-color white-text btn-sm remove-button category-remove-button px-3 py-1">
                        X
                    </button>
                </div>
            </div>
            `;
        }

        $(document).ready(function () {

            $("#extra-category-button").on("click", function () {
                $("#category-section").append(getExtraCategoryHTML(getCategoryCount()));
                $('#currentCategoryCount').val(parseInt($('#currentCategoryCount').val()) + 1);
            });
        });

        // Variation js

        function getVariationCount() {
            return $("#variation-section div.variation-item").length;
        }

        function getExtraVariationHTML(variationCount) {
            return `
            <div class="row variation-item">
                <div class="col-11 mb-1 mr-0 pr-0">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 pr-md-1">
                            <input type="text"
                                   class="form-control variation-name"
                                   name="variations[${variationCount}][name1]"
                                   aria-describedby="variations"
                                   maxlength="100"
                            >
                        </div>
                        <div class="col-md-6 col-sm-12 pl-md-1">
                            <input type="text"
                                   class="form-control variation-name"
                                   name="variations[${variationCount}][name2]"
                                   aria-describedby="variations"
                                   maxlength="100"
                            >
                        </div>
                    </div>
                </div>
                <div class="col-1 mb-1 ml-0 pl-0">
                    <button type="button"
                            class="btn default-color white-text btn-sm remove-button variation-remove-button px-3 py-1">
                        X
                    </button>
                </div>
            </div>
            `;
        }

        function getExtraVariationTableRowHTML(variationCount) {
            return `
            <tr class="variation-table-item">
                <td>
                    <input type="text"
                           class="form-control variation-table-variation-name-display"
                           disabled
                    >
                </td>
                <td>
                    <input type="text"
                           class="form-control"
                           name="variations[${variationCount}][barcode]"
                           maxlength="20"
                    >
                </td>
                <td><input type="number"
                           step="0.01"
                           min="0"
                           class="form-control variation-price-display"
                           name="variations[${variationCount}][price]"
                >
                </td>
                <td><input type="number"
                           step="0.001"
                           min="0"
                           class="form-control"
                           name="variations[${variationCount}][weight]"
                >
                </td>
            </tr>
            `;
        }

        function getExtraInventoryTableRowHTML(variationCount) {
            return `
            <tr class="inventory-table-item">
                <td>
                    <input type="text"
                           class="form-control inventory-table-variation-name-display"
                           disabled
                    >
                </td>
                <td>
                    <input type="number"
                           min="0"
                           class="form-control"
                           name="variations[${variationCount}][inventories][0][stock]"
                    >
                </td>
            </tr>
            `;
        }

        function getExtraVariationImageBoxHTML(variationCount) {
            return `
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 variation-image-item">
                <input type="file"
                       name="variations[image][${variationCount}]"
                       class="image-file-selector"
                       style="display:none;"
                >
                <figure class="figure">
                    <div class="img-upload-container">
                        <img class="img-fluid image-preview w-100"
                             src="{{ asset("img/alt/image-upload-alt.png") }}"
                        >
                        <div class="img-upload-overlay">
                            <div class="img-upload-overlay-icon edit-img-button"
                                 title="Upload Image"
                                 onclick="uploadImage(this)"
                            >
                                <i class="icofont-edit"></i>
                            </div>
                            <div class="img-upload-overlay-icon remove-img-button"
                                 title="Remove Image"
                                 onclick="removeImage(this)"
                            >
                                <i class="icofont-ui-delete"></i>
                            </div>
                        </div>
                    </div>
                    <figcaption class="figure-caption text-center"></figcaption>
                </figure>
            </div>
            `;
        }



        $(document).ready(function () {
            $("#extra-variation-button").on("click", function () {
                let variationCount = getVariationCount();

                $("#variation-section").append(getExtraVariationHTML(variationCount));
                $('#variation-table-section').append(getExtraVariationTableRowHTML(variationCount));
                $('#inventory-table-section').append(getExtraInventoryTableRowHTML(variationCount));
                $("#variation-image-section").append(getExtraVariationImageBoxHTML(variationCount));

                let selector = $('#currentVariationCount');
                selector.val(parseInt(selector.val()) + 1);
            });
        });


        // General Image upload js

        function getGeneralImageCount() {
            return $('#general-image-section div.general-image-item').length;
        }

        function getExtraGeneralImageHTML(generalImageCount) {
            return `
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 general-image-item">
                <input type="file"
                       name="item-image[${generalImageCount}]"
                       class="image-file-selector"
                       style="display:none;"
                >
                    <figure class="figure">
                        <div class="img-upload-container">
                            <img class="img-fluid image-preview"
                                 src="{{ asset('img/alt/image-upload-alt.png') }}"
                            >
                                <div class="img-upload-overlay">
                                    <div class="img-upload-overlay-icon edit-img-button"
                                         title="Upload Image"
                                         onclick="uploadImage(this)"
                                    >
                                        <i class="icofont icofont-edit"></i>
                                    </div>
                                    <div class="img-upload-overlay-icon remove-img-button"
                                         title="Remove Image"
                                         onclick="removeImage(this)"
                                    >
                                        <i class="icofont icofont-ui-delete"></i>
                                    </div>
                                </div>
                        </div>
                    </figure>
            </div>
            `;
        }


        $(document).ready(function () {
            $('#add-general-image-button').on('click', function () {
                let count = getGeneralImageCount();
                if (count >= 10) {
                    alert("最多只能有10张基本照片！");
                } else {
                    $('#general-image-section').append(getExtraGeneralImageHTML(getGeneralImageCount()));
                }
            });
        });


        // Attribute utility js (variation name auto sync, remove button)
        // Auto sync variation display
        $(document).on("change", ".variation-name", function (e) {
            e.preventDefault();

            let sourceSelector = $(this).closest('.row');
            let value = sourceSelector.find('.variation-name').eq(0).val() + sourceSelector.find('.variation-name').eq(1).val();
            let variationIndex = $(".variation-item").index($(this).closest('.variation-item'));

            $(".variation-table-variation-name-display").eq(variationIndex).val(value);
            $(".inventory-table-variation-name-display").eq(variationIndex).val(value);
            $(".variation-image-item").eq(variationIndex).find(".figure-caption").html(value); // Variety Image Box Caption
        });


        // Category or variation or inventory remove button
        $(document).on("click", ".remove-button", function () {

            // Variation: Remove variation table row
            if ($(this).hasClass("variation-remove-button")) {
                let variationIndex = $(".variation-item").index($(this).closest('.variation-item'));

                $(".variation-table-item").eq(variationIndex).html('');
                $(".inventory-table-item").eq(variationIndex).html('');

                let variationImageItemSelector = $(".variation-image-item").eq(variationIndex);
                variationImageItemSelector.attr("hidden", "hidden"); // Hide the blank space
                variationImageItemSelector.html('');
            }

            $(this).parent().parent().html("");

            // Checking after delete the html
            // if ($(this).hasClass("wholesale-remove-button")) {
            //     // Last romove button of wholesale table will always enable and the rest will be disabled
            //     $("#wholesale-table-section").find(".remove-button").attr("disabled", "disabled");
            //     $("#wholesale-table-section").find(".remove-button").last().removeAttr("disabled");
            //
            //     // Last max column will always disabled and the rest will be enabled
            //     $(".w-max").removeAttr("disabled");
            //     $(".w-max").last().attr("disabled", "disabled");
            // }

            // Category: Restore the template html for removed until no markup
            if ($(this).hasClass("category-remove-button")) {
                let selector = $('#currentCategoryCount');
                let currentCategoryCount = parseInt(selector.val());
                if (currentCategoryCount === 1) {
                    $("#category-section").append(getExtraCategoryHTML(getCategoryCount()));
                } else {
                    selector.val(currentCategoryCount - 1);
                }
            }

            // Variation: Restore the template html for removed until no markup
            if ($(this).hasClass("variation-remove-button")) {
                let selector = $('#currentVariationCount');
                let currentVariationCount = parseInt(selector.val());
                if (currentVariationCount === 1) {
                    let variationCount = getVariationCount();
                    $("#variation-section").append(getExtraVariationHTML(variationCount));
                    $('#variation-table-section').append(getExtraVariationTableRowHTML(variationCount));
                    $('#inventory-table-section').append(getExtraInventoryTableRowHTML(variationCount));
                    $('#variation-image-section').append(getExtraVariationImageBoxHTML(variationCount));
                } else {
                    selector.val(currentVariationCount - 1);
                }
            }

        });

















        // function getExtraWholesaleTableRowHTML(wholesaleCount, max, price) {
        //     var d = $("#wholesale-table-section tr").has("td").length == 0 ? "" : "disabled";
        //     return `` +
        //         `<tr>` +
        //         `<td><input type="number" class="form-control mb-1 w-min" min="1" name="w[${wholesaleCount}][w_min]" aria-describedby="w-min" value="${max}" ${d}/></td>` +
        //         `<td><input type="number" class="form-control mb-1 w-max" min="${max}" name="w[${wholesaleCount}][w_max]" aria-describedby="w-max" disabled/></td>` +
        //         `<td><input type="number" class="form-control mb-1 w-price" step="0.01" min="0.01" max="${price}" name="w[${wholesaleCount}][w_price]" aria-describedby="w-price"/></td>` +
        //         `<td><button type="button" class="btn default-color white-text btn-sm remove-button wholesale-remove-button px-3 py-1">X</button></td>` +
        //         `</tr>`;
        // }


        // function getWholesaleCount() {
        //     return $("#wholesale-table-section tr").length;
        // }



        // Wholesale w-max column auto sync
        // $(document).on("change", ".w-max", function (e) {
        //     e.preventDefault();
        //
        //     var index = $(".w-max").index(this);
        //     var nextMin = parseInt($(this).val()) + 1;
        //
        //     $(".w-min").eq(index + 1).val(nextMin); // Set w-min of next row sync value with current w-max
        //     $(".w-max").eq(index + 1).attr("min", nextMin); // Set min value of w-max of next row with current w-max
        // });

        // Wholesale w-price column auto sync
        // $(document).on("change", ".w-price", function (e) {
        //     e.preventDefault();
        //
        //     var index = $(".w-price").index(this);
        //     var nextMaxPrice = $(this).val();
        //
        //     $(".w-price").eq(index + 1).attr("max", nextMaxPrice); // Set max value of w-price of next row with current w-price
        //
        // });


        // Disable wholesales if all variety price is difference
        // $(document).on("change", ".v-price", function () {
        //
        //     // Wholesale settings
        //     var first = $(".v-price").eq(0).val();
        //     var isSame = true;
        //     for (var i = 1; i < $(".v-price").length; i++) {
        //         if ($(".v-price").eq(i).val() != first) {
        //             isSame = false;
        //             break;
        //         }
        //     }
        //
        //     if (isSame) {
        //         $(".wholesale-section").show();
        //     } else {
        //         $(".wholesale-section").hide();
        //     }
        //
        //     // Discount price settings
        //     var index = $(".v-price").index(this);
        //     $(".v-price").eq(index).parent().parent().find(".v-discounted-price").attr("max", $(this).val());
        // });

        // $(document).ready(function(){
            // Extra wholesale
            // $("#extra-wholesale-button").on("click", function () {
            //     var min = parseInt($("#wholesale-table-section tr").last().find("input.w-min").val());
            //     var max = parseInt($("#wholesale-table-section tr").last().find("input.w-max").val()) + 1;
            //     var price = $("#wholesale-table-section tr").last().find("input.w-price").val();
            //     if (price != "" && min != "" && max != "") {
            //         $("#wholesale-table-section").append(getExtraWholesaleTableRowHTML(getWholesaleCount(), max, price));
            //
            //         // Last romove button of wholesale table will always enable and the rest will be disabled
            //         $("#wholesale-table-section").find(".remove-button").attr("disabled", "disabled");
            //         $("#wholesale-table-section").find(".remove-button").last().removeAttr("disabled");
            //
            //         // Last max column will always disabled and the rest will be enabled
            //         $(".w-max").removeAttr("disabled");
            //         $(".w-max").last().attr("disabled", "disabled");
            //     } else {
            //         alert("请填写最后一行的所以信息！");
            //     }
            //
            // });

            // Wholesale first w-min column auto sync
            // $(".w-min").first().change(function () {
            //     $(this).parent().parent().first().find("input.w-max").attr("min", $(this).val()); // Set min value for w-max in same row
            //     $(this).parent().parent().first().find("input.w-price").attr("max", $(".v-price").eq(0).val()); // Set value of first wholesale after w-max is modified from blank (Apply for item without wholesale at first)
            // });

            // Wholesale settings
            // var first = $(".v-price").eq(0).val();
            // var isSame = true;
            // for (var i = 1; i < $(".v-price").length; i++) {
            //     if ($(".v-price").eq(i).val() != first) {
            //         isSame = false;
            //         break;
            //     }
            // }
            //
            // if (isSame) {
            //     $(".wholesale-section").show();
            // } else {
            //     $(".wholesale-section").hide();
            // }
        // });



















        // For separate image upload
        var selected;

        function loadImage(e) {
            selected.attr('src', e.target.result);
        }

        // Image file validater
        // Reference: https://stackoverflow.com/questions/4234589/validation-of-file-extension-before-uploading-file
        var validFileExtensions = [".jpg", ".jpeg", ".gif", ".png"];
        var maxUploadSize = 5000000; // Unit in Bytes // 5MB
        function validateImage(fileInput) {

            if (fileInput.type == "file") {
                var fileName = fileInput.value;
                var fileSize = fileInput.files[0].size;

                if (fileName.length > 0) {

                    var extensionValid = false;
                    var sizeValid = false;

                    for (var j = 0; j < validFileExtensions.length; j++) {
                        var cur = validFileExtensions[j];
                        if (fileName.substr(fileName.length - cur.length, cur.length).toLowerCase() == cur.toLowerCase()) {
                            extensionValid = true;
                            break;
                        }
                    }

                    if (fileSize < maxUploadSize) {
                        sizeValid = true;
                    }

                    if (!extensionValid) {
                        alert("请上传格式正确的图像");
                        return false;
                    }

                    if (!sizeValid) {
                        alert("请上传少于5MB的图像文件");
                        return false;
                    }
                }
            }

            return true;
        }

        $(document).on('change', ".image-file-selector", function () {

            if (!validateImage($(this)[0])) {
                $(this).val(''); // Empty the file upload input if wrong extension
            } else {
                // Load preview
                if (typeof (FileReader) != "undefined") {
                    selected = $(this).parent().find(".image-preview");
                    var reader = new FileReader();
                    reader.onload = loadImage;
                    reader.readAsDataURL($(this)[0].files[0]);


                    // Crop and shape the preview
                    var style = getComputedStyle(selected[0]);
                    var width = parseInt(style.width) || 0;
                    var height = parseInt(style.height) || 0;

                    if (width > height) {
                        padding = (width - height) / 2.0;
                        selected[0].style.marginTop = padding;
                        selected[0].style.marginBottom = padding;
                    } else {
                        padding = (height - width) / 2.0;
                        selected[0].style.marginLeft = padding;
                        selected[0].style.marginRight = padding;
                    }

                    selected[0].style.width = width;
                    selected[0].style.height = height;
                    selected[0].style.objectFit = 'cover';

                } else {
                    alert("您使用的浏览器不支持这个功能！");
                }

            }

        });

        // Retrieve image source to the file input for server submit testing
        function getImageOriginalSource() {
            var imgs = document.getElementsByTagName("img");

            for (var i = 0; i < imgs.length; i++) {
                var imgSrc = imgs[i].src;
                var fileInput = imgs[i].parentElement.parentElement.parentElement.getElementsByTagName("input")[0];
                if (fileInput == null) continue;
                const dT = new ClipboardEvent('').clipboardData || // Firefox < 62 workaround exploiting https://bugzilla.mozilla.org/show_bug.cgi?id=1422655
                    new DataTransfer(); // specs compliant (as of March 2018 only Chrome)
                dT.items.add(new File(['imgSource'], imgSrc));
                fileInput.files = dT.files;
            }
        }

        function uploadImage(source) {
            source.parentElement.parentElement.parentElement.parentElement.firstElementChild.click();
        }

        function removeImage(source) {
            source.parentElement.parentElement.firstElementChild.src = "{{ asset('img/alt/image-upload-alt.png') }}";
            source.parentElement.parentElement.parentElement.parentElement.firstElementChild.value = "";
        }

        $(document).ready(function () {

            // Retrieve image source to the file input for server submit testing
            getImageOriginalSource();



            // For changing "active tag" when scrolling
            // Reference: https://www.steckinsights.com/change-active-menu-as-you-scroll-with-jquery/
            $(window).scroll(function () {
                var Scroll = $(window).scrollTop();
                var StepOneOffset = $('#step-one').offset().top;
                var StepTwoOffset = $('#step-two').offset().top - 100;
                var StepThreeOffset = $('#step-three').offset().top - 100;
                var StepFourOffset = $('#step-four').offset().top - 100;
                var StepFiveOffset = $('#step-five').offset().top - 100;

                if (Scroll < StepTwoOffset) {
                    $("#step-one-link").addClass("active");
                } else {
                    $("#step-one-link").removeClass("active");
                }

                if (Scroll >= StepTwoOffset) {
                    $("#step-two-link").addClass("active");
                    $("#step-one-link").removeClass("active");
                } else {
                    $("#step-two-link").removeClass("active");
                }

                if (Scroll >= StepThreeOffset) {
                    $("#step-three-link").addClass("active");
                    $("#step-two-link").removeClass("active");
                } else {
                    $("#step-three-link").removeClass("active");
                }

                if (Scroll >= StepFourOffset) {
                    $("#step-four-link").addClass("active");
                    $("#step-three-link").removeClass("active");
                } else {
                    $("#step-four-link").removeClass("active");
                }

                if (Scroll >= StepFiveOffset) {
                    $("#step-five-link").addClass("active");
                    $("#step-four-link").removeClass("active");
                } else {
                    $("#step-five-link").removeClass("active");
                }
            });

        });
    </script>
@endsection
