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
                                            @for($i = 0; $i < sizeof(old('categories.name')); $i++)
                                                <div class="row category_item">
                                                    <div class="col-11 mb-1 mr-0 pr-0">
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12 pr-md-1">
                                                                <input type="text"
                                                                       class="form-control @error('categories.name.' . $i) is-invalid @enderror"
                                                                       name="categories[name][{{$i}}]"
                                                                       aria-describedby="categories"
                                                                       list="category-list"
                                                                       maxlength="20"
                                                                       placeholder="中文"
                                                                       value="{{ old('categories.name.' . $i) ?? "" }}"
                                                                >

                                                                @error('categories.name.' . $i)
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>

                                                            <div class="col-md-6 col-sm-12 pl-md-1">
                                                                <input type="text"
                                                                       class="form-control @error('categories.name_en.' . $i) is-invalid @enderror"
                                                                       name="categories[name_en][{{$i}}]"
                                                                       aria-describedby="categories"
                                                                       list="category-list"
                                                                       maxlength="20"
                                                                       placeholder="英文"
                                                                       value="{{ old('categories.name_en.' . $i) ?? "" }}"
                                                                >

                                                                @error('categories.name_en.' . $i)
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
                                        @elseif(!empty($item->categories->toArray()))
                                            @for($i = 0; $i < sizeof($item->categories); $i++)
                                                <div class="row category_item">
                                                    <div class="col-11 mb-1 mr-0 pr-0">
                                                        <div class="row">
                                                            <div class="col-md-6 col-sm-12 pr-md-1">
                                                                <input type="text"
                                                                       class="form-control"
                                                                       name="categories[name][{{$i}}]"
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
                                                                       name="categories[name_en][{{$i}}]"
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
                                                                class="btn default-color white-text btn-sm remove-button px-3 py-1">
                                                            X
                                                        </button>
                                                    </div>
                                                </div>
                                            @endfor
                                        @else
                                            <div class="row category_item">
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
                                                            class="btn default-color white-text btn-sm remove-button px-3 py-1">
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

                        <!-- Property -->
                        <div class="col-12">
                            <div class="row">
                                <div class="col-xs-2 col-sm-4 col-md-3 col-lg-4 text-sm-left text-md-right mb-3">
                                    <label for="variations">规格：</label>
                                </div>

                                <div class="col-xs-10 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">
                                    <div id="property-section">
                                        @if(empty($item->variations))
                                            <div class="row">
                                                <div class="col-11 mb-1 mr-0 pr-0">
                                                    <div class="row">
                                                        <div class="col pr-0 mr-0">
                                                            <input type="text" class="form-control v-property"
                                                                   name="variations[0][name1]"
                                                                   aria-describedby="variations" maxlength="100">
                                                        </div>
                                                        <div class="col">
                                                            <input type="text" class="form-control v-property"
                                                                   name="variations[0][name2]"
                                                                   aria-describedby="variations" maxlength="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-1 mb-1 ml-0 pl-0">
                                                    <button type="button"
                                                            class="btn default-color white-text btn-sm remove-button property-remove-button px-3 py-1">
                                                        X
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            @for($i = 0; $i < sizeof($item->variations); $i++)
                                                <div class="row">
                                                    <div class="col-11 mb-1 mr-0 pr-0">
                                                        <div class="row">
                                                            <div class="col pr-0 mr-0">
                                                                <input type="text"
                                                                       class="form-control v-property @error('variations.' . $i . '.name1') is-invalid @enderror"
                                                                       name="variations[{{ $i }}][name1]"
                                                                       aria-describedby="variations" maxlength="100"
                                                                       value="{{ old('variations.' . $i . '.name1') ?? $item->variations[$i]->name1 ?? "" }}">
                                                                @error('variations.' . $i . '.name1')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                            <div class="col">
                                                                <input type="text"
                                                                       class="form-control v-property @error('variations.' . $i . '.name1') is-invalid @enderror"
                                                                       name="variations[{{ $i }}][name2]"
                                                                       aria-describedby="variations" maxlength="100"
                                                                       value="{{ old('variations.' . $i . '.name2') ?? $item->variations[$i]->name2 ?? "" }}">
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
                                                                class="btn default-color white-text btn-sm remove-button property-remove-button px-3 py-1">
                                                            X
                                                        </button>
                                                    </div>
                                                </div>
                                            @endfor
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Property -->

                        <!-- Add extra variety button -->
                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-secondary mb-3" id="extra-property-button">
                                添加更多规格
                            </button>
                        </div>
                        <!-- Add extra variety button -->

                        <!-- Variety -->
                        <div class="col-12"><label>规格销售</label></div>
                        <div class="col-12 mb-3" style="overflow-x: scroll;">
                            <table class="table table-bordered" style="width: 900px;">
                                <thead>
                                <tr>
                                    <th scope="col">选择</th>
                                    <th scope="col">商品货号</th>
                                    <th scope="col">价格(RM)</th>
                                    <th scope="col">重量(kg)</th>
                                    <th scope="col">折扣价钱</th>
                                </tr>
                                </thead>

                                <tbody id="variety-table-section">
                                @if(empty($item->variations))
                                    <tr>
                                        <td><input type="text" class="form-control v-property-view" disabled></td>
                                        <td><input type="text" class="form-control" name="variations[0][barcode]"
                                                   maxlength="20"></td>
                                        <td><input type="number" step="0.01" min="0" class="form-control v-price"
                                                   name="variations[0][price]"></td>
                                        <td><input type="number" step="0.001" min="0" class="form-control"
                                                   name="variations[0][weight]"></td>
                                        <td><input type="number" step="0.01" min="0"
                                                   class="form-control v-discounted-price"
                                                   name="variations[0][discount][rate]"></td>
                                    </tr>
                                @else
                                    @for($i = 0; $i < sizeof($item->variations); $i++)
                                        <tr>
                                            <td><input type="text" class="form-control v-property-view"
                                                       value="{{ $item->variations[$i]->name1 . $item->variations[$i]->name2 ?? "" }}"
                                                       disabled></td>
                                            <td><input type="text" class="form-control"
                                                       name="variations[{{ $i }}][barcode]" aria-describedby="v-barcode"
                                                       maxlength="20"
                                                       value="{{ old('variations.' . $i . '.barcode') ?? $item->variations[$i]->barcode ?? "" }}">
                                            </td>
                                            <td><input type="number" step="0.01" min="0" class="form-control v-price"
                                                       name="v[{{ $i }}][v_price]"
                                                       value="{{ old('variations.' . $i . '.price') ?? number_format($item->variations[$i]->price, 2, '.', '') ?? "" }}"/>
                                            </td>
                                            <td><input type="number" step="0.001" min="0" class="form-control"
                                                       name="v[{{ $i }}][v_weight]"
                                                       value="{{ old('variations.' . $i . '.weight') ?? number_format($item->variations[$i]->weight, 3, '.', '') ?? "" }}"/>
                                            </td>
                                            <td>
                                                @if($item->variations[$i]->discount == null)
                                                    <input type="number" step="0.01" min="0"
                                                           max="{{ $item->variations[$i]->price }}"
                                                           class="form-control v-discounted-price"
                                                           name="v[{{ $i }}][v_discounted_price]"
                                                           value="{{ number_format($item->variations[$i]->price, 2, '.', '') ?? "" }}"/>
                                                @else
                                                    <input type="number" step="0.01" min="0"
                                                           max="{{ $item->variations[$i]->price }}"
                                                           class="form-control v-discounted-price"
                                                           name="v[{{ $i }}][v_discounted_price]"
                                                           value="{{ number_format($item->variations[$i]->price * $item->variations[$i]->discount->rate, 2, '.', '') ?? "" }}"/>
                                                @endif
                                            </td>
                                        </tr>
                                    @endfor
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- Variety -->

                        <!-- Inventory -->
                        <div class="col-12"><label>规格库存</label></div>
                        <div class="col-12 mb-3" style="overflow-x: scroll;">
                            <table class="table table-bordered" style="width: 900px;">
                                <thead>
                                <tr>
                                    <th scope="col">选择</th>
                                    <th scope="col">过期日期</th>
                                    <th scope="col">数量</th>
                                </tr>
                                </thead>

                                <tbody id="inventory-table-section">
                                <tr>
                                    <td><input type="text" class="form-control v-property-view" disabled/></td>
                                    <td colspan="2">
                                        <div class="variety-inventory-table-section">
                                            <div class="row">
                                                <div class="col-6"><input type="date" class="form-control mb-1"
                                                                          name="v[0][inv][0][inv_expire_date]"
                                                                          aria-describedby="inv-expire-date"/></div>
                                                <div class="col-5"><input type="number" min="0"
                                                                          class="form-control mb-1"
                                                                          name="v[0][inv][0][inv_quantity]"
                                                                          aria-describedby="inv-quantity"/></div>
                                                <div class="col-1 mb-1 ml-0 pl-0">
                                                    <button type="button"
                                                            class="btn default-color white-text btn-sm remove-button px-3 py-1">
                                                        X
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Add extra inventory button -->
                                        <div class="text-center">
                                            <button type="button" class="btn btn-secondary mt-1 extra-inventory-button">
                                                添加更多库存
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                {{--                                <?php if ($propertyCount == 0) : ?>--}}
                                {{--                                --}}
                                {{--                                <?php else : ?>--}}
                                {{--                                <?php for ($i = 0; $i < $propertyCount; $i++) : ?>--}}
                                {{--                                <tr>--}}
                                {{--                                    <td><input type="text" class="form-control v-property-view" value="<?= $item->getVarieties()[$i]->getProperty(); ?>" disabled /></td>--}}
                                {{--                                    <td colspan="2">--}}
                                {{--                                        <div class="variety-inventory-table-section">--}}
                                {{--                                            <?php $currentIventoryCount = sizeof($item->getVarieties()[$i]->getInventories()); ?>--}}
                                {{--                                            <?php if ($currentIventoryCount == 0) : ?>--}}
                                {{--                                            <div class="row">--}}
                                {{--                                                <div class="col-6"><input type="date" class="form-control mb-1" name="v[<?= $i; ?>][inv][0][inv_expire_date]" aria-describedby="inv-expire-date" /></div>--}}
                                {{--                                                <div class="col-5"><input type="number" min="0" class="form-control mb-1" name="v[<?= $i; ?>][inv][0][inv_quantity]" aria-describedby="inv-quantity" /></div>--}}
                                {{--                                                <div class="col-1 mb-1 ml-0 pl-0"><button type="button" class="btn default-color white-text btn-sm remove-button px-3 py-1">X</button></div>--}}
                                {{--                                            </div>--}}
                                {{--                                            <?php else : ?>--}}
                                {{--                                            <?php for ($j = 0; $j < $currentIventoryCount; $j++) : ?>--}}
                                {{--                                            <div class="row">--}}
                                {{--                                                <div class="col-6"><input type="date" class="form-control mb-1" name="v[<?= $i; ?>][inv][<?= $j; ?>][inv_expire_date]" aria-describedby="inv-expire-date" value="<?= $item->getVarieties()[$i]->getInventories()[$j]->getExpireDate(); ?>" /></div>--}}
                                {{--                                                <div class="col-5"><input type="number" min="0" class="form-control mb-1" name="v[<?= $i; ?>][inv][<?= $j; ?>][inv_quantity]" aria-describedby="inv-quantity" value="<?= $item->getVarieties()[$i]->getInventories()[$j]->getQuantity(); ?>" /></div>--}}
                                {{--                                                <div class="col-1 mb-1 ml-0 pl-0"><button type="button" class="btn default-color white-text btn-sm remove-button px-3 py-1">X</button></div>--}}
                                {{--                                            </div>--}}
                                {{--                                            <?php endfor; ?>--}}
                                {{--                                            <?php endif; ?>--}}
                                {{--                                        </div>--}}
                                {{--                                        <!-- Add extra inventory button -->--}}
                                {{--                                        <div class="text-center"><button type="button" class="btn btn-secondary mt-1 extra-inventory-button">添加更多库存</button></div>--}}
                                {{--                                    </td>--}}
                                {{--                                </tr>--}}
                                {{--                                <?php endfor; ?>--}}
                                {{--                                <?php endif; ?>--}}
                                </tbody>
                            </table>
                        </div>
                        <!-- Inventory -->

                        <!-- Wholesale -->
                        <div class="col-12 wholesale-section"><label>批发价管理（单件规格折扣后的规格将无视此设定）</label></div>
                        <div class="col-12 mb-3 wholesale-section" style="overflow-x: scroll;">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">开始数量</th>
                                    <th scope="col">结束数量</th>
                                    <th scope="col">价格(RM)</th>
                                    <th scope="col">操作</th>
                                </tr>
                                </thead>

                                <tbody id="wholesale-table-section">
                                <tr>
                                    <td><input type="number" class="form-control mb-1 w-min" min="1" name="w[0][w_min]"
                                               aria-describedby="w-min"/></td>
                                    <td><input type="number" class="form-control mb-1 w-max" min="1" name="w[0][w_max]"
                                               aria-describedby="w-max" disabled/></td>
                                    <td><input type="number" class="form-control mb-1 w-price" step="0.01" min="0.01"
                                               name="w[0][w_price]" aria-describedby="w-price"/></td>
                                    <td>
                                        <button type="button"
                                                class="btn default-color white-text btn-sm remove-button wholesale-remove-button px-3 py-1">
                                            X
                                        </button>
                                    </td>
                                </tr>
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
                                </tbody>
                            </table>
                            <!-- Add extra wholesales button -->
                            <div class="text-center">
                                <button type="button" class="btn btn-secondary mt-1" id="extra-wholesale-button">
                                    添加更多批发价
                                </button>
                            </div>
                        </div><!-- Wholesale -->

                        <div class="h2" id="step-three">媒体管理</div>


                        <div class="col-12 mb-3">

                            <div class="row general-image-section">

                                <div class="col-12"><label>基本照片</label></div>

                                <!-- Cover picture (0.jpg) -->
                                <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
                                    <input type="file" name="item-image[0]" class="image-file-selector"
                                           style="display:none;"/>
                                    <figure class="figure">
                                        <div class="img-upload-container">
                                            <img class="img-fluid image-preview"
                                                 src="{{ asset($item->images->toArray()[0]['image'] ?? 'img/alt/image-upload-alt.png') }}"/>
                                            <div class="img-upload-overlay">
                                                <div class="img-upload-overlay-icon edit-img-button"
                                                     title="Upload Image" onclick="uploadImage(this)">
                                                    <i class="icofont icofont-edit"></i>
                                                </div>
                                                <div class="img-upload-overlay-icon remove-img-button"
                                                     title="Remove Image" onclick="removeImage(this)">
                                                    <i class="icofont icofont-ui-delete"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <figcaption class="figure-caption text-center">封面</figcaption>
                                    </figure>
                                </div><!-- Cover picture (0.jpg) -->

                                <!-- General picture -->
                            {{--                                <?php for ($i = 1; $i <= 8; $i++) : ?>--}}
                            {{--                                <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">--}}
                            {{--                                    <input type="file" name="item-image[<?= $i; ?>]" class="image-file-selector" style="display:none;" />--}}
                            {{--                                    <figure class="figure">--}}
                            {{--                                        <div class="img-upload-container">--}}
                            {{--                                            <img class="img-fluid image-preview" src="<?= file_exists("../assets/images/items/$i_id/$i.jpg") ? "../assets/images/items/$i_id/$i.jpg" : "../assets/images/alt/image-upload-alt.png"; ?>" />--}}
                            {{--                                            <div class="img-upload-overlay">--}}
                            {{--                                                <div class="img-upload-overlay-icon edit-img-button" title="Upload Image" onclick="uploadImage(this)">--}}
                            {{--                                                    <i class="icofont-edit"></i>--}}
                            {{--                                                </div>--}}
                            {{--                                                <div class="img-upload-overlay-icon remove-img-button" title="Remove Image" onclick="removeImage(this)">--}}
                            {{--                                                    <i class="icofont-ui-delete"></i>--}}
                            {{--                                                </div>--}}
                            {{--                                            </div>--}}
                            {{--                                        </div>--}}
                            {{--                                        <figcaption class="figure-caption text-center">照片 <?= $i ?></figcaption>--}}
                            {{--                                    </figure>--}}
                            {{--                                </div><?php endfor; ?>--}}
                            <!-- General picture -->

                            </div>

                        </div>

                        <!-- Variety image section -->
                        {{--                        <div class="col-12 mb-3">--}}

                        {{--                            <div class="row" id="variety-image-section">--}}
                        {{--                                <div class="col-12"><label>规格照片<label></div>--}}
                        {{--                                <?php if ($propertyCount == 0) : ?>--}}
                        {{--                                <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">--}}
                        {{--                                    <input type="file" name="variety-image[0]" class="image-file-selector" style="display:none;" />--}}
                        {{--                                    <figure class="figure">--}}
                        {{--                                        <div class="img-upload-container">--}}
                        {{--                                            <img class="img-fluid image-preview" src="../assets/images/alt/image-upload-alt.png" />--}}
                        {{--                                            <div class="img-upload-overlay">--}}
                        {{--                                                <div class="img-upload-overlay-icon edit-img-button" title="Upload Image" onclick="uploadImage(this)">--}}
                        {{--                                                    <i class="icofont-edit"></i>--}}
                        {{--                                                </div>--}}
                        {{--                                                <div class="img-upload-overlay-icon remove-img-button" title="Remove Image" onclick="removeImage(this)">--}}
                        {{--                                                    <i class="icofont-ui-delete"></i>--}}
                        {{--                                                </div>--}}
                        {{--                                            </div>--}}
                        {{--                                        </div>--}}
                        {{--                                        <figcaption class="figure-caption text-center">照片 <?= $i ?></figcaption>--}}
                        {{--                                    </figure>--}}
                        {{--                                </div>--}}
                        {{--                                <?php else : ?>--}}
                        {{--                                <?php for ($i = 0; $i < $propertyCount; $i++) : ?>--}}
                        {{--                                <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">--}}
                        {{--                                    <input type="file" name="variety-image[<?= $i; ?>]" class="image-file-selector" style="display:none;" />--}}
                        {{--                                    <figure class="figure">--}}
                        {{--                                        <div class="img-upload-container">--}}
                        {{--                                            <?php $b = $item->getVarieties()[$i]->getBarcode(); ?>--}}
                        {{--                                            <img class="img-fluid image-preview" src="<?= file_exists("../assets/images/items/$i_id/$b.jpg") ? "../assets/images/items/$i_id/$b.jpg" : "../assets/images/alt/image-upload-alt.png"; ?>" />--}}
                        {{--                                            <div class="img-upload-overlay">--}}
                        {{--                                                <div class="img-upload-overlay-icon edit-img-button" title="Upload Image" onclick="uploadImage(this)">--}}
                        {{--                                                    <i class="icofont-edit"></i>--}}
                        {{--                                                </div>--}}
                        {{--                                                <div class="img-upload-overlay-icon remove-img-button" title="Remove Image" onclick="removeImage(this)">--}}
                        {{--                                                    <i class="icofont-ui-delete"></i>--}}
                        {{--                                                </div>--}}
                        {{--                                            </div>--}}
                        {{--                                        </div>--}}
                        {{--                                        <figcaption class="figure-caption text-center variety-property-caption"><?= $item->getVarieties()[$i]->getProperty(); ?></figcaption>--}}
                        {{--                                    </figure>--}}
                        {{--                                </div>--}}
                        {{--                                <?php endfor; ?>--}}
                        {{--                                <?php endif; ?>--}}
                        {{--                            </div>--}}

                        {{--                        </div><!-- Variety image section -->--}}


                        <div classs="col-12 mb-3">
                            <div class="row">
                                <div class="h2" id="step-four">其他商品设定</div>
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
                            <input class="btn btn-primary mr-2" type="submit" value="保存" name="save"
                                   style="width: 200px">
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
                           class="item-create-step-info list-group-item list-group-item-action">媒体管理</a>
                        <a href="#step-four" id="step-four-link"
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
            return $("#category-section div.category_item").length;
        }

        function getExtraCategoryHTML(categoryCount) {
            return `
            <div class="row">
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
                    <button type="button" class="btn default-color white-text btn-sm remove-button px-3 py-1">
                        X
                    </button>
                </div>
            </div>
            `;
        }

        $(document).ready(function(){
            $("#extra-category-button").on("click", function () {
                $("#category-section").append(getExtraCategoryHTML(getCategoryCount()));
            });
        });





        function getExtraPropertyHTML(propertyCount) {
            return `` +
                `<div class="row">` +
                `<div class="col-11 mb-1 mr-0 pr-0"><input type="text" class="form-control v-property" name="v[${propertyCount}][v_property]" aria-describedby="v-property" maxlength="100"/></div>` +
                `<div class="col-1 mb-1 ml-0 pl-0"><button type="button" class="btn default-color white-text btn-sm remove-button px-3 py-1">X</button></div>` +
                `</div>`;
        }

        function getExtraVarietyTableRowHTML(propertyCount) {
            return `` +
                `<tr>` +
                `<td><input type="text" class="form-control v-property-view" disabled/></td>` +
                `<td><input type="text" class="form-control" name="v[${propertyCount}][v_barcode]" aria-describedby="v-barcode" maxlength="20"/></td>` +
                `<td><input type="number" step="0.01" min="0" class="form-control v-price" name="v[${propertyCount}][v_price]" aria-describedby="v-price"/></td>` +
                `<td><input type="number" step="0.001" min="0" class="form-control" name="v[${propertyCount}][v_weight]" aria-describedby="v-weight"/></td>` +
                `<td><input type="number" step="0.01" min="0" class="form-control v-discounted-price" name="v[${propertyCount}][v_discount_price]" aria-describedby="v-discounted-price"/></td>` +
                `</tr>`;
        }

        function getExtraInventoryTableRowHTML(propertyCount) {
            return `` +
                `<tr>` +
                `<td><input type="text" class="form-control v-property-view" disabled/></td>` +
                `<td colspan="2">` +
                `<div class="variety-inventory-table-section">` +
                `<div class="row">` +
                `<div class="col-6"><input type="date" class="form-control mb-1" name="v[${propertyCount}][inv][0][inv_expire_date]" aria-describedby="inv-expire-date"/></div>` +
                `<div class="col-5"><input type="number" min="0" class="form-control mb-1" name="v[${propertyCount}][inv][0][inv_quantity]" aria-describedby="inv-quantity"/></div>` +
                `<div class="col-1 mb-1 ml-0 pl-0"><button type="button" class="btn default-color white-text btn-sm remove-button px-3 py-1">X</button></div>` +
                `</div>` +
                `</div>` +
                `<div class="text-center"><button type="button" class="btn btn-secondary mt-1 extra-inventory-button">添加更多库存</button></div></td></tr>`;
        }

        function getExtraInventoryHTML(currentVarietyIndex, inventoryCount) {
            return `` +
                `<div class="row">` +
                `<div class="col-6"><input type="date" class="form-control mb-1" name="v[${currentVarietyIndex}][inv][${inventoryCount}][inv_expire_date]" aria-describedby="inv-expire-date"/></div>` +
                `<div class="col-5"><input type="number" min="0" class="form-control mb-1" name="v[${currentVarietyIndex}][inv][${inventoryCount}][inv_quantity]" aria-describedby="inv-quantity"/></div>` +
                `<div class="col-1 mb-1 ml-0 pl-0"><button type="button" class="btn default-color white-text btn-sm remove-button px-3 py-1">X</button></div>` +
                `</div>`;
        }

        function getExtraVarietyImageBoxHTML(propertyCount) {
            return `` +
                `<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">` +
                `<input type="file" name="variety-image[${propertyCount}]" class="image-file-selector" style="display:none;"/>` +
                `<figure class="figure">` +
                `<div class="img-upload-container">` +
                `<img class="img-fluid image-preview" src="{{ asset('img/alt/image-upload-alt.png') }}"/>` +
                `<div class="img-upload-overlay">` +
                `<div class="img-upload-overlay-icon edit-img-button" title="Upload Image" onclick="uploadImage(this)">` +
                `<i class="icofont-edit"></i>` +
                `</div>` +
                `<div class="img-upload-overlay-icon remove-img-button" title="Remove Image" onclick="removeImage(this)">` +
                `<i class="icofont-ui-delete"></i>` +
                `</div>` +
                `</div>` +
                `</div>` +
                `<figcaption class="figure-caption text-center variety-property-caption"></figcaption>` +
                `</figure>` +
                `</div>`;
        }

        function getExtraWholesaleTableRowHTML(wholesaleCount, max, price) {
            var d = $("#wholesale-table-section tr").has("td").length == 0 ? "" : "disabled";
            return `` +
                `<tr>` +
                `<td><input type="number" class="form-control mb-1 w-min" min="1" name="w[${wholesaleCount}][w_min]" aria-describedby="w-min" value="${max}" ${d}/></td>` +
                `<td><input type="number" class="form-control mb-1 w-max" min="${max}" name="w[${wholesaleCount}][w_max]" aria-describedby="w-max" disabled/></td>` +
                `<td><input type="number" class="form-control mb-1 w-price" step="0.01" min="0.01" max="${price}" name="w[${wholesaleCount}][w_price]" aria-describedby="w-price"/></td>` +
                `<td><button type="button" class="btn default-color white-text btn-sm remove-button wholesale-remove-button px-3 py-1">X</button></td>` +
                `</tr>`;
        }

        /* Get form information */


        function getPropertyCount() {
            return $("#property-section div.row").length;
        }

        function getIventoryRowCount(source) {
            return $(source).parent().parent().children(".inventory-section-class tr").length;
        }

        function getInventoryCount(source) {
            return $(source).parent().parent().find(".variety-inventory-table-section div.row").length;
        }

        function getWholesaleCount() {
            return $("#wholesale-table-section tr").length;
        }

        // Extra inventory
        $(document).on("click", ".extra-inventory-button", function (e) { // To detect and modify real time (new generated) HTML
            e.preventDefault();

            var currentVarietyIndex = $(".extra-inventory-button").index($(this)); // Get current located index in the table
            $(".variety-inventory-table-section").eq(currentVarietyIndex).append(getExtraInventoryHTML(currentVarietyIndex, getInventoryCount(this)));
        });

        // Auto sync property shown below two table
        $(document).on("change", ".v-property", function (e) { // To detect and modify real time (new generated) HTML
            e.preventDefault();

            var value = $(this).val();
            var propertyCount = $("#property-section div.row").has("div").length;
            var propertyIndex = $(".v-property").index(this);

            $(".v-property-view").eq(propertyIndex).val(value); // Variety table
            $(".v-property-view").eq(propertyIndex + propertyCount).val(value); // Inventory table  // Minus 1 of property count to index
            $(".variety-property-caption").eq(propertyIndex).html(value); // Variety Image Box Caption
        });

        // Wholesale w-max column auto sync
        $(document).on("change", ".w-max", function (e) {
            e.preventDefault();

            var index = $(".w-max").index(this);
            var nextMin = parseInt($(this).val()) + 1;

            $(".w-min").eq(index + 1).val(nextMin); // Set w-min of next row sync value with current w-max
            $(".w-max").eq(index + 1).attr("min", nextMin); // Set min value of w-max of next row with current w-max
        });

        // Wholesale w-price column auto sync
        $(document).on("change", ".w-price", function (e) {
            e.preventDefault();

            var index = $(".w-price").index(this);
            var nextMaxPrice = $(this).val();

            $(".w-price").eq(index + 1).attr("max", nextMaxPrice); // Set max value of w-price of next row with current w-price

        });

        // Category or property or inventory remove button
        $(document).on("click", ".remove-button", function () {
            if ($(this).hasClass("property-remove-button")) {
                var propertyCount = getPropertyCount();
                var propertyInput = $(this).parent().parent().find(".v-property");
                var propertyIndex = $(".v-property").index(propertyInput);

                $(".v-property-view").eq(propertyIndex).parent().parent().html('');
                $(".v-property-view").eq(propertyIndex + propertyCount - 1).parent().parent().html(''); // Minus 1 of property count to index
                $(".variety-property-caption").eq(propertyIndex).parent().parent().attr("hidden", "hidden"); // Hidden the div to delete to avoid blank space
                $(".variety-property-caption").eq(propertyIndex).parent().parent().html('');
            }

            $(this).parent().parent().html("");

            // Checking after delete the html
            if ($(this).hasClass("wholesale-remove-button")) {
                // Last romove button of wholesale table will always enable and the rest will be disabled
                $("#wholesale-table-section").find(".remove-button").attr("disabled", "disabled");
                $("#wholesale-table-section").find(".remove-button").last().removeAttr("disabled");

                // Last max column will always disabled and the rest will be enabled
                $(".w-max").removeAttr("disabled");
                $(".w-max").last().attr("disabled", "disabled");
            }
        });

        // Disable wholesales if all variety price is difference
        $(document).on("change", ".v-price", function () {

            // Wholesale settings
            var first = $(".v-price").eq(0).val();
            var isSame = true;
            for (var i = 1; i < $(".v-price").length; i++) {
                if ($(".v-price").eq(i).val() != first) {
                    isSame = false;
                    break;
                }
            }

            if (isSame) {
                $(".wholesale-section").show();
            } else {
                $(".wholesale-section").hide();
            }

            // Discount price settings
            var index = $(".v-price").index(this);
            $(".v-price").eq(index).parent().parent().find(".v-discounted-price").attr("max", $(this).val());
        });

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

            // Extra category


            // Extra property
            $("#extra-property-button").on("click", function () {
                var propertyCount = getPropertyCount();
                $("#property-section").append(getExtraPropertyHTML(propertyCount));
                $('#variety-table-section').append(getExtraVarietyTableRowHTML(propertyCount));
                $('#inventory-table-section').append(getExtraInventoryTableRowHTML(propertyCount));
                $("#variety-image-section").append(getExtraVarietyImageBoxHTML(propertyCount));
            });

            // Extra wholesale
            $("#extra-wholesale-button").on("click", function () {
                var min = parseInt($("#wholesale-table-section tr").last().find("input.w-min").val());
                var max = parseInt($("#wholesale-table-section tr").last().find("input.w-max").val()) + 1;
                var price = $("#wholesale-table-section tr").last().find("input.w-price").val();
                if (price != "" && min != "" && max != "") {
                    $("#wholesale-table-section").append(getExtraWholesaleTableRowHTML(getWholesaleCount(), max, price));

                    // Last romove button of wholesale table will always enable and the rest will be disabled
                    $("#wholesale-table-section").find(".remove-button").attr("disabled", "disabled");
                    $("#wholesale-table-section").find(".remove-button").last().removeAttr("disabled");

                    // Last max column will always disabled and the rest will be enabled
                    $(".w-max").removeAttr("disabled");
                    $(".w-max").last().attr("disabled", "disabled");
                } else {
                    alert("请填写最后一行的所以信息！");
                }

            });

            // Wholesale first w-min column auto sync
            $(".w-min").first().change(function () {
                $(this).parent().parent().first().find("input.w-max").attr("min", $(this).val()); // Set min value for w-max in same row
                $(this).parent().parent().first().find("input.w-price").attr("max", $(".v-price").eq(0).val()); // Set value of first wholesale after w-max is modified from blank (Apply for item without wholesale at first)
            });

            // Wholesale settings
            var first = $(".v-price").eq(0).val();
            var isSame = true;
            for (var i = 1; i < $(".v-price").length; i++) {
                if ($(".v-price").eq(i).val() != first) {
                    isSame = false;
                    break;
                }
            }

            if (isSame) {
                $(".wholesale-section").show();
            } else {
                $(".wholesale-section").hide();
            }

            // For changing "active tag" when scrolling
            // Reference: https://www.steckinsights.com/change-active-menu-as-you-scroll-with-jquery/
            $(window).scroll(function () {
                var Scroll = $(window).scrollTop();
                StepOneOffset = $('#step-one').offset().top;
                StepTwoOffset = $('#step-two').offset().top - 100;
                // StepThreeOffset = $('#step-three').offset().top - 100;
                // StepFourOffset = $('#step-four').offset().top - 100;

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
                //
                // if (Scroll >= StepThreeOffset) {
                //     $("#step-three-link").addClass("active");
                //     $("#step-two-link").removeClass("active");
                // } else {
                //     $("#step-three-link").removeClass("active");
                // }
                //
                // if (Scroll >= StepFourOffset) {
                //     $("#step-two-link").addClass("active");
                //     $("#step-three-link").removeClass("active");
                // } else {
                //     $("#step-four-link").removeClass("active");
                // }
            });

        });
    </script>
@endsection
