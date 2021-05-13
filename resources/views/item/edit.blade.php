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

        @if(session()->has('message'))
            <div class="alert alert-info text-center" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div class="row">

            <!-- Content -->
            <div class="col-sm-12 col-md-10">
                <form action="{{ url('/item/' . $item->id) }}" method="post" enctype="multipart/form-data">

                    @csrf
                    @method('PATCH')

                    <div class="h1">商品属性编辑</div>

                    <div class="h2" id="step-one">基本资讯</div>

                    <!-- Name -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                            商品名称：
                        </div>

                        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 pr-md-1">
                                    <input type="text"
                                           class="form-control @error('item.name') is-invalid @enderror"
                                           name="item[name]"
                                           maxlength="250"
                                           value="{{ old('item.name') ?? $item->name ?? "" }}"
                                           placeholder="商品名称">

                                    @error('item.name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12 pl-md-1">
                                    <input type="text"
                                           class="form-control @error('item.name_en') is-invalid @enderror"
                                           name="item[name_en]"
                                           maxlength="250"
                                           value="{{ old('item.name_en') ?? $item->name_en ?? "" }}"
                                           placeholder="Item Name">

                                    @error('item.name_en')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Name -->

                    <!-- Description -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                            商品描述：
                        </div>

                        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-8 mb-3">
                            <textarea class="form-control @error('item.desc') is-invalid @enderror"
                                      name="item[desc]"
                                      rows="5"
                                      placeholder="商品描述 Item Description"
                                      maxlength="3000">{{ old('item.desc') ?? $item->desc ?? "" }}</textarea>

                            @error('item.desc')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <!-- Description -->

                    <!-- Origin -->
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                            出产地：
                        </div>

                        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">

                            <div class="row">
                                <div class="col-md-6 col-sm-12 pr-md-1">
                                    <input type="text"
                                           class="form-control @error('item.origin') is-invalid @enderror"
                                           name="item[origin]"
                                           value="{{ old('item.origin') ?? $item->origin ?? "" }}"
                                           placeholder="出产地">

                                    @error('item.origin')
                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12 pl-md-1">
                                    <input type="text"
                                           class="form-control @error('item.origin_en') is-invalid @enderror"
                                           name="item[origin_en]"
                                           value="{{ old('item.origin_en') ?? $item->origin_en ?? "" }}"
                                           placeholder="Origin">

                                    @error('item.origin_en')
                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Origin -->

                    <!-- Category -->
                    <div class="row mb-3">
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                            商品类别/标签：
                        </div>

                        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">
                            <div id="category-section">
                                @if(!empty(old('categories')))
                                    <input type="hidden"
                                           value="{{ sizeof(old('categories')) }}"
                                           id="currentCategoryCount">
                                    @for($i = 0; $i < sizeof(old('categories')); $i++)
                                        <div class="row category-item">
                                            <div class="col-11 mb-1 mr-0 pr-0">
                                                <select class="form-control" name="categories[{{$i}}][id]">
                                                    @foreach($categories as $category)
                                                        <option
                                                            value="{{ $category->id }}" {{ old("categories.$i.id") == $category->id ? "selected" : "" }}>
                                                            {{ $category->name . ' ' . $category->name_en }}
                                                        </option>
                                                    @endforeach
                                                </select>
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
                                    <input type="hidden"
                                           value="{{ sizeof($item->categories) }}"
                                           id="currentCategoryCount">
                                    @for($i = 0; $i < sizeof($item->categories); $i++)
                                        <div class="row category-item">
                                            <div class="col-11 mb-1 mr-0 pr-0">
                                                <select class="form-control" name="categories[{{$i}}][id]">
                                                    @foreach($categories as $category)
                                                        <option
                                                            value="{{ $category->id }}" {{ $item->categories[$i]->id == $category->id ? "selected" : "" }}>
                                                            {{ $category->name . ' ' . $category->name_en }}
                                                        </option>
                                                    @endforeach
                                                </select>
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
                                            <select class="form-control" name="categories[0][id]">
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ $category->name . ' ' . $category->name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
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

                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-secondary" id="extra-category-button">
                                添加更多类别/标签
                            </button>
                        </div>
                    </div>
                    <!-- Category -->

                    <div class="h2" id="step-two">规格资讯</div>

                    <!-- Variation Name -->
                    <div class="row mb-3">
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                            规格：
                        </div>

                        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">
                            <div id="variation-section">
                                @if(!empty(old('variations')))
                                    <input type="hidden"
                                           value="{{ sizeof($item->variations) }}"
                                           id="currentVariationCount">

                                    @for($i = 0; $i < sizeof(old('variations')); $i++)
                                        <div class="row variation-item">
                                            <div class="col-11 mb-1 mr-0 pr-0">
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-12 pr-md-1">
                                                        <input type="text"
                                                               class="form-control variation-name @error("variations.$i.name") is-invalid @enderror"
                                                               name="variations[{{$i}}][name]"
                                                               maxlength="100"
                                                               value="{{ old("variations.$i.name") ?? "" }}"
                                                               placeholder="规格">

                                                        @error("variations.$i.name")
                                                        <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 pl-md-1">
                                                        <input type="text"
                                                               class="form-control variation-name @error("variations.$i.name_en") is-invalid @enderror"
                                                               name="variations[{{$i}}][name_en]"
                                                               maxlength="100"
                                                               value="{{ old("variations.$i.name_en") ?? "" }}"
                                                               placeholder="Variation">

                                                        @error("variations.$i.name_en")
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
                                                               name="variations[{{$i}}][name]"
                                                               maxlength="100"
                                                               value="{{ $item->variations[$i]->name ?? "" }}"
                                                               placeholder="规格">
                                                    </div>
                                                    <div class="col-md-6 col-sm-12 pl-md-1">
                                                        <input type="text"
                                                               class="form-control variation-name"
                                                               name="variations[{{$i}}][name_en]"
                                                               maxlength="100"
                                                               value="{{ $item->variations[$i]->name_en ?? "" }}"
                                                               placeholder="Variation">
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
                                                           name="variations[0][name]"
                                                           maxlength="100"
                                                           placeholder="规格">
                                                </div>
                                                <div class="col-md-6 col-sm-12 pl-md-1">
                                                    <input type="text"
                                                           class="form-control variation-name"
                                                           name="variations[0][name_en]"
                                                           maxlength="100"
                                                           placeholder="Variation">
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

                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-secondary mb-3" id="extra-variation-button">
                                添加更多规格
                            </button>
                        </div>
                    </div>
                    <!-- Variation Name -->

                    <!-- Variation Detail -->
                    <div class="row mb-3">
                        <div class="col-12">
                            规格销售
                        </div>
                        <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">名称</th>
                                    <th scope="col">货号</th>
                                    <th scope="col">价格(RM)</th>
                                    <th scope="col">重量(kg)</th>
                                    <th scope="col">库存</th>
                                </tr>
                                </thead>

                                <tbody id="variation-table-section">
                                @if(!empty(old('variations')))
                                    @for($i = 0; $i < sizeof(old('variations')); $i++)
                                        <tr class="variation-table-item">
                                            <td>
                                                <input type="text"
                                                       class="form-control variation-name-display"
                                                       value="{{ (old("variations.$i.name") ?? "") }}"
                                                       disabled>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control @error("variations.$i.barcode") is-invalid @enderror"
                                                       name="variations[{{$i}}][barcode]"
                                                       maxlength="20"
                                                       value="{{ old("variations.$i.barcode") ?? "" }}">
                                            </td>
                                            <td><input type="number"
                                                       step="0.01"
                                                       min="0"
                                                       class="form-control @error("variations.$i.price") is-invalid @enderror variation-price-display"
                                                       name="variations[{{$i}}][price]"
                                                       value="{{ old("variations.$i.price") ?? "" }}">
                                            </td>
                                            <td><input type="number"
                                                       step="0.001"
                                                       min="0"
                                                       class="form-control @error("variations.$i.weight") is-invalid @enderror"
                                                       name="variations[{{$i}}][weight]"
                                                       value="{{ old("variations.$i.weight") ?? "" }}">
                                            <td>
                                                <input type="number"
                                                       min="0"
                                                       class="form-control @error("variations.$i.stock") is-invalid @enderror"
                                                       name="variations[{{$i}}][stock]"
                                                       value="{{ old("variations.$i.stock") ?? "" }}">
                                            </td>
                                        </tr>
                                    @endfor
                                @elseif(!empty($item->variations->toArray()))
                                    @for($i = 0; $i < sizeof($item->variations); $i++)

                                        <tr class="variation-table-item">
                                            <td>
                                                <input type="text"
                                                       class="form-control variation-name-display"
                                                       value="{{ $item->variations[$i]->name }}"
                                                       disabled>
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control"
                                                       name="variations[{{$i}}][barcode]"
                                                       maxlength="20"
                                                       value="{{ $item->variations[$i]->barcode ?? "" }}">
                                            </td>
                                            <td><input type="number"
                                                       step="0.01"
                                                       min="0"
                                                       class="form-control variation-price-display"
                                                       name="variations[{{$i}}][price]"
                                                       value="{{ number_format($item->variations[$i]->price, 2, '.', '') ?? "" }}">
                                            </td>
                                            <td><input type="number"
                                                       step="0.001"
                                                       min="0"
                                                       class="form-control"
                                                       name="variations[{{$i}}][weight]"
                                                       value="{{ number_format($item->variations[$i]->weight, 3, '.', '') ?? "" }}">
                                            </td>
                                            <td>
                                                <input type="number"
                                                       min="0"
                                                       class="form-control"
                                                       name="variations[{{$i}}][stock]"
                                                       value="{{ $item->variations[$i]->stock ?? 0 }}">
                                            </td>
                                        </tr>
                                    @endfor
                                @else
                                    <tr class="variation-table-item">
                                        <td>
                                            <input type="text"
                                                   class="form-control variation-name-display"
                                                   disabled>
                                        </td>
                                        <td>
                                            <input type="text"
                                                   class="form-control"
                                                   name="variations[0][barcode]"
                                                   maxlength="20">
                                        </td>
                                        <td><input type="number"
                                                   step="0.01"
                                                   min="0"
                                                   class="form-control variation-price-display"
                                                   name="variations[0][price]">
                                        </td>
                                        <td><input type="number"
                                                   step="0.001"
                                                   min="0"
                                                   class="form-control"
                                                   name="variations[0][weight]">
                                        </td>
                                        <td>
                                            <input type="number"
                                                   min="0"
                                                   class="form-control"
                                                   name="variations[0][stock]">
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Variation Detail -->

                    <div class="h2" id="step-three">折扣管理</div>

                    <div class="row mb-3">
                        <div class="col-12 text-center">Under Maintenance</div>
                    </div>
                    <!-- Variation discount -->
                {{--                    <div class="row mb-3">--}}
                {{--                        <div class="col-12">--}}
                {{--                            <label>规格折扣</label>--}}
                {{--                        </div>--}}
                {{--                        <div class="col-12 mb-3">--}}
                {{--                            <table class="table table-bordered">--}}
                {{--                                <thead>--}}
                {{--                                <tr>--}}
                {{--                                    <th scope="col">规格名称</th>--}}
                {{--                                    <th scope="col">开始日期</th>--}}
                {{--                                    <th scope="col">结束日期</th>--}}
                {{--                                    <th scope="col">折扣价钱</th>--}}
                {{--                                </tr>--}}
                {{--                                </thead>--}}
                {{--                                <tbody id="variation-discount-table-section">--}}

                {{--                                </tbody>--}}
                {{--                            </table>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                <!-- Variation discount -->

                    <!-- Wholesale discount -->
                {{--                        <div class="row">--}}
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
                {{--                    </div>--}}

                <!-- Wholesale discount -->

                    <div class="h2" id="step-four">媒体管理</div>

                    <!-- General item image -->
                    <div class="row mb-3">
                        <div class="col-12">
                            基本照片
                            <span class="ml-2">
                                <i class="icofont icofont-ui-add" id="add-general-image-button"></i>
                            </span>
                        </div>
                        <div class="col-12">
                            <div class="row" id="general-image-section">
                                @if(empty($item->images->toArray()))
                                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 image-item general-image-item">
                                        <input type="file"
                                               name="item[images][0][newImage]"
                                               class="image-file-selector"
                                               style="display:none;">
                                        <input type="hidden" name="item[images][0][oldImage]"
                                               value="">
                                        <input type="hidden" class="image-is-empty-flag" name="item[images][0][isEmpty]"
                                               value="1">
                                        <figure class="figure">
                                            <div class="img-upload-container">
                                                <img class="img-fluid image-preview w-100"
                                                     src="{{ asset('img/alt/image-upload-alt.png') }}"
                                                     alt="基本照片">
                                                <div class="img-upload-overlay">
                                                    <div class="img-upload-overlay-icon edit-img-button"
                                                         title="Upload Image"
                                                         onclick="uploadImage(this)">
                                                        <i class="icofont icofont-edit"></i>
                                                    </div>
                                                    <div class="img-upload-overlay-icon remove-img-button"
                                                         title="Remove Image"
                                                         onclick="removeImage(this)">
                                                        <i class="icofont icofont-ui-delete"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </figure>
                                    </div>
                                @else
                                    @for($i = 0; $i < sizeof($item->images); $i++)
                                        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 image-item general-image-item">
                                            <input type="file"
                                                   name="item[images][{{$i}}][newImage]"
                                                   class="image-file-selector"
                                                   style="display:none;">
                                            <input type="hidden" name="item[images][{{$i}}][oldImage]"
                                                   value="{{ $item->images[$i]->image ?? "" }}">
                                            <input type="hidden" class="image-is-empty-flag"
                                                   name="item[images][{{$i}}][isEmpty]" value="{{ $item->images[$i]->image == null ? '1' : '0' }}">
                                            <figure class="figure">
                                                <div class="img-upload-container">
                                                    <img class="img-fluid image-preview"
                                                         src="{{ asset($item->images[$i]->image ?? 'img/alt/image-upload-alt.png') }}"
                                                         alt="基本照片">
                                                    <div class="img-upload-overlay">
                                                        <div class="img-upload-overlay-icon edit-img-button"
                                                             title="Upload Image"
                                                             onclick="uploadImage(this)">
                                                            <i class="icofont icofont-edit"></i>
                                                        </div>
                                                        <div class="img-upload-overlay-icon remove-img-button"
                                                             title="Remove Image"
                                                             onclick="removeImage(this)">
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
                    </div>
                    <!-- General item image -->

                    <!-- Variation image -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label>规格照片</label>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="row" id="variation-image-section">
                                @if(empty($item->variations->toArray()))
                                    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 image-item variation-image-item">
                                        <input type="file"
                                               name="variations[0][image]"
                                               class="image-file-selector"
                                               style="display:none;">
                                        <input type="hidden"
                                               name="variations[0][oldImage]"
                                               value="">
                                        <input type="hidden" class="image-is-empty-flag" name="variations[0][isEmpty]"
                                               value="1">
                                        <figure class="figure">
                                            <div class="img-upload-container">
                                                <img class="img-fluid image-preview w-100"
                                                     src="{{ asset("img/alt/image-upload-alt.png") }}"
                                                     alt="规格照片">
                                                <div class="img-upload-overlay">
                                                    <div class="img-upload-overlay-icon edit-img-button"
                                                         title="Upload Image"
                                                         onclick="uploadImage(this)">
                                                        <i class="icofont-edit"></i>
                                                    </div>
                                                    <div class="img-upload-overlay-icon remove-img-button"
                                                         title="Remove Image"
                                                         onclick="removeImage(this)">
                                                        <i class="icofont-ui-delete"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <figcaption class="figure-caption text-center"></figcaption>
                                        </figure>
                                    </div>
                                @else
                                    @for($i = 0; $i < sizeof($item->variations->toArray()); $i++)
                                        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 image-item variation-image-item">
                                            <input type="file"
                                                   name="variations[{{$i}}][image]"
                                                   class="image-file-selector"
                                                   style="display:none;">
                                            <input type="hidden"
                                                   name="variations[{{$i}}][oldImage]"
                                                   value="{{ $item->variations[$i]->image ?? "" }}">
                                            <input type="hidden" class="image-is-empty-flag"
                                                   name="variations[{{$i}}][isEmpty]" value="{{ $item->variations[$i]->image == null ? '1' : '0' }}">
                                            <figure class="figure">
                                                <div class="img-upload-container">
                                                    <img class="img-fluid image-preview w-100"
                                                         src="{{ asset($item->variations[$i]->image ?? "img/alt/image-upload-alt.png") }}"
                                                         alt="规格照片">
                                                    <div class="img-upload-overlay">
                                                        <div class="img-upload-overlay-icon edit-img-button"
                                                             title="Upload Image"
                                                             onclick="uploadImage(this)">
                                                            <i class="icofont-edit"></i>
                                                        </div>
                                                        <div class="img-upload-overlay-icon remove-img-button"
                                                             title="Remove Image"
                                                             onclick="removeImage(this)">
                                                            <i class="icofont-ui-delete"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <figcaption class="figure-caption text-center">
                                                    {{ $item->variations[$i]->name }}
                                                </figcaption>
                                            </figure>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Variation image -->

                    <div class="col-12 text-center mb-3">
                        <button class="btn btn-primary mr-2" type="submit">保存</button>
                    </div>

                </form>

                <div class="h2" id="step-five">其他商品设定</div>

                <div class="row mb-3">
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
                                <td>
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           value="{{ $item->util->view_count ?? 0 }}"
                                           disabled>
                                </td>
                                <td>
                                    <button type="submit"
                                            class="btn btn-primary btn-sm" disabled>
                                        重置
                                    </button>
                                </td>

                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <!-- Content -->

            <!-- Content Navigator -->
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
            </div>
            <!-- Content Navigator -->

        </div>
    </main>
@endsection


@section('extraScriptEnd')
    <script>
        /* Category */
        function getCategoryCount() {
            return $("#category-section div.category-item").length;
        }

        function getExtraCategoryHTML(categoryCount) {
            return `
            <div class="row category-item">
                <div class="col-11 mb-1 mr-0 pr-0">
                    <select class="form-control" name="categories[${categoryCount}][id]">
                        @foreach($categories as $category)
            <option value="{{ $category->id }}">
                                {{ $category->name . ' ' . $category->name_en }}
            </option>
@endforeach
            </select>
        </div>

        <div class="col-1 mb-1 ml-0 pl-0">
            <button type="button"
                    class="btn default-color white-text btn-sm remove-button category-remove-button px-3 py-1">
                X
            </button>
        </div>
    </div>
`;
        }

        $(document).ready(function () {
            $("#extra-category-button").on("click", function () {
                $("#category-section").append(getExtraCategoryHTML(getCategoryCount()));
                let currentCategoryCountSelector = $('#currentCategoryCount');
                currentCategoryCountSelector.val(parseInt(currentCategoryCountSelector.val()) + 1);
            });
        });

        /* Variation  */
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
                                                           name="variations[${variationCount}][name]"
                                                           maxlength="100"
                                                           placeholder="规格">
                                                </div>
                                                <div class="col-md-6 col-sm-12 pl-md-1">
                                                    <input type="text"
                                                           class="form-control variation-name"
                                                           name="variations[${variationCount}][name_en]"
                                                           maxlength="100"
                                                           placeholder="Variation">
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
                                                   class="form-control variation-name-display"
                                                   disabled>
                                        </td>
                                        <td>
                                            <input type="text"
                                                   class="form-control"
                                                   name="variations[${variationCount}][barcode]"
                                                   maxlength="20">
                                        </td>
                                        <td><input type="number"
                                                   step="0.01"
                                                   min="0"
                                                   class="form-control variation-price-display"
                                                   name="variations[${variationCount}][price]">
                                        </td>
                                        <td><input type="number"
                                                   step="0.001"
                                                   min="0"
                                                   class="form-control"
                                                   name="variations[${variationCount}][weight]">
                                        </td>
                                        <td>
                                            <input type="number"
                                                   min="0"
                                                   class="form-control"
                                                   name="variations[${variationCount}][stock]">
                                        </td>
                                    </tr>
            `;
        }

        function getExtraVariationImageBoxHTML(variationCount) {
            return `
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 image-item variation-image-item">
                <input type="file"
                       name="variations[image][${variationCount}]"
                       class="image-file-selector"
                       style="display:none;"
                >
                <input type="hidden"
                                                   name="variations[${variationCount}][oldImage]"
                                                   value="">
                                                   <input type="hidden" class="image-is-empty-flag" name="variations[${variationCount}][isEmpty]" value="1">
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

        // Auto sync variation display
        $(document).on("change", ".variation-name", function (e) {
            e.preventDefault();

            let sourceSelector = $(this).closest('.row');
            let value = sourceSelector.find('.variation-name').eq(0).val();
            let variationIndex = $(".variation-item").index($(this).closest('.variation-item'));

            $(".variation-table-item").eq(variationIndex).find('.variation-name-display').val(value);
            $(".variation-image-item").eq(variationIndex).find(".figure-caption").html(value); // Variety Image Box Caption
        });

        $(document).ready(function () {
            $("#extra-variation-button").on("click", function () {
                let variationCount = getVariationCount();

                $("#variation-section").append(getExtraVariationHTML(variationCount));
                $('#variation-table-section').append(getExtraVariationTableRowHTML(variationCount));
                $("#variation-image-section").append(getExtraVariationImageBoxHTML(variationCount));

                let selector = $('#currentVariationCount');
                selector.val(parseInt(selector.val()) + 1);
            });
        });

        /* General Image Upload */
        function getGeneralImageCount() {
            return $('#general-image-section div.general-image-item').length;
        }

        function getExtraGeneralImageHTML(generalImageCount) {
            return `
            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 image-item general-image-item">
                                        <input type="file"
                                               name="item[images][${generalImageCount}][newImage]"
                                               class="image-file-selector"
                                               style="display:none;">
                                        <input type="hidden" name="item[images][${generalImageCount}][oldImage]"
                                               value="">
                                               <input type="hidden" class="image-is-empty-flag" name="item[images][${generalImageCount}][isEmpty]" value="1">
                                        <figure class="figure">
                                            <div class="img-upload-container">
                                                <img class="img-fluid image-preview w-100"
                                                     src="{{ asset('img/alt/image-upload-alt.png') }}"
                                                     alt="基本照片">
                                                <div class="img-upload-overlay">
                                                    <div class="img-upload-overlay-icon edit-img-button"
                                                         title="Upload Image"
                                                         onclick="uploadImage(this)">
                                                        <i class="icofont icofont-edit"></i>
                                                    </div>
                                                    <div class="img-upload-overlay-icon remove-img-button"
                                                         title="Remove Image"
                                                         onclick="removeImage(this)">
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


        // TODO - Wholesale js
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


        /* Remove Button */
        $(document).on("click", ".remove-button", function () {

            // Variation: Remove variation table row
            if ($(this).hasClass("variation-remove-button")) {
                let variationIndex = $(".variation-item").index($(this).closest('.variation-item'));

                $(".variation-table-item").eq(variationIndex).html('');

                let variationImageItemSelector = $(".variation-image-item").eq(variationIndex);
                variationImageItemSelector.attr("hidden", "hidden"); // Hide the blank space
                variationImageItemSelector.html('');
            }

            $(this).parent().parent().html("");

            // Wholesale: Enable or Disable with the wholesale table behaviour
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
                    $('#variation-image-section').append(getExtraVariationImageBoxHTML(variationCount));
                } else {
                    selector.val(currentVariationCount - 1);
                }
            }
        });

        /* Image onclick function */
        function uploadImage(source) {
            let selector = jQuery(source);
            selector.closest('.image-item').find('.image-file-selector').click();
        }

        function removeImage(source) {
            let selector = jQuery(source);
            selector.closest('.img-upload-container').find('.image-preview').attr('src', "{{ asset('img/alt/image-upload-alt.png') }}");
            selector.closest('.image-item').find('.image-file-selector').val('');
            selector.closest('.image-item').find('.image-is-empty-flag').val('1');
        }

        /*
        Image file validator
        Reference: https://stackoverflow.com/questions/4234589/validation-of-file-extension-before-uploading-file
        */
        let validFileExtensions = [".jpg", ".jpeg", ".gif", ".png"];
        let maxUploadSize = 5000000; // Unit in Bytes // 5MB
        function validateImage(fileInput) {
            if (fileInput.type === "file") {
                let fileName = fileInput.value;
                let fileSize = fileInput.files[0].size;

                if (fileName.length > 0) {
                    let extensionValid = false;
                    let sizeValid = false;
                    for (let j = 0; j < validFileExtensions.length; j++) {
                        let cur = validFileExtensions[j];
                        if (fileName.substr(fileName.length - cur.length, cur.length).toLowerCase() === cur.toLowerCase()) {
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

        /* Load Image to Preview */
        let selected;

        function loadImage(e) {
            selected.attr('src', e.target.result);
        }

        $(document).on('change', ".image-file-selector", function () {
            if (!validateImage($(this)[0])) {
                $(this).val(''); // Empty the file upload input if wrong extension
            } else {
                // Load preview
                if (typeof (FileReader) != "undefined") {
                    selected = $(this).parent().find(".image-preview");
                    let reader = new FileReader();
                    reader.onload = loadImage;
                    reader.readAsDataURL($(this)[0].files[0]);

                    // Change the isEmpty flag to false when load the image successfully
                    $(this).closest('.image-item').find('.image-is-empty-flag').val('0');

                    // Crop and shape the preview
                    let style = getComputedStyle(selected[0]);
                    let width = parseInt(style.width) || 0;
                    let height = parseInt(style.height) || 0;
                    if (width > height) {
                        let padding = (width - height) / 2.0;
                        selected[0].style.marginTop = padding;
                        selected[0].style.marginBottom = padding;
                    } else {
                        let padding = (height - width) / 2.0;
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

        /*
            Content Navigator
            Reference: https://www.steckinsights.com/change-active-menu-as-you-scroll-with-jquery/
        */
        $(document).ready(function () {
            $(window).scroll(function () {
                let Scroll = $(window).scrollTop();
                // let StepOneOffset = $('#step-one').offset().top;
                let StepTwoOffset = $('#step-two').offset().top - 100;
                let StepThreeOffset = $('#step-three').offset().top - 100;
                let StepFourOffset = $('#step-four').offset().top - 100;
                let StepFiveOffset = $('#step-five').offset().top - 100;

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
