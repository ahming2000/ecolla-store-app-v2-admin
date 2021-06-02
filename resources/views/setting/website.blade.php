@extends('layouts.app')

@section('title')
    网站属性设定
@endsection

@section('content')
    <main class="container">
        <div class="h1">网站属性设定</div>

        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-8 offset-md-1 offset-lg-2">

                @if(session()->has('message'))
                    <div class="alert alert-info text-center" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="mb-3 mt-3">
                    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="item-tab" data-toggle="tab" href="#item" role="tab"
                               aria-controls="item" aria-selected="false">商品</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="order-tab" data-toggle="tab" href="#order" role="tab"
                               aria-controls="order" aria-selected="false">订单</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" id="other-tab" data-toggle="tab" href="#other" role="tab"
                               aria-controls="other" aria-selected="false" tabindex="-1" aria-disabled="true">其他</a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade" id="item" role="tabpanel" aria-labelledby="item-tab">
                        <div id="setting-item-category">
                            <form method="post" action="{{ url('/setting/update/category') }}">

                                @csrf
                                @method('PATCH')

                                <div class="row align-content-center mb-1">
                                    <div class="col-md-6 col-sm-12">
                                        <div style="font-size: 30px">类别管理</div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="row mb-2">
                                            <div class="col-12 text-right">
                                                <button type="button" class="btn btn-outline-blue btn-sm"
                                                        id="extra-category-button">
                                                    <i class="icofont icofont-ui-add"></i> 添加更多类别
                                                </button>
                                                <button class="btn btn-primary btn-sm">保存</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden"
                                       value="{{ sizeof($categories->toArray()) > 1 ? sizeof($categories->toArray()) : '1' }}"
                                       id="currentCategoryCount">

                                <div id="default-category-section">
                                    @for($i = 0; $i < $DEFAULT_CATEGORY_COUNT; $i++)
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
                                                                   placeholder="类别">

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
                                                                   placeholder="Category">

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
                                        @for($i = $DEFAULT_CATEGORY_COUNT; $i < sizeof($categories->toArray()); $i++)
                                            <div class="row category-item">
                                                <div class="col-11 mb-1 mr-0 pr-0">
                                                    <div class="row">
                                                        <input type="hidden"
                                                               name="category[{{$i - $DEFAULT_CATEGORY_COUNT}}][id]"
                                                               value="{{ $categories[$i]->id }}">
                                                        <div class="col-md-6 col-sm-12 pr-md-1">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="category[{{$i - $DEFAULT_CATEGORY_COUNT}}][name]"
                                                                   value="{{ $categories[$i]->name ?? "" }}"
                                                                   placeholder="类别">
                                                        </div>
                                                        <div class="col-md-6 col-sm-12 pl-md-1">
                                                            <input type="text"
                                                                   class="form-control"
                                                                   name="category[{{$i - $DEFAULT_CATEGORY_COUNT}}][name_en]"
                                                                   value="{{ $categories[$i]->name_en ?? "" }}"
                                                                   placeholder="Category">
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
                            </form>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="order" role="tabpanel" aria-labelledby="order-tab">
                        <div class="row" id="setting-order-prefix">
                            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-4 align-content-center text-sm-left text-md-right mb-2">
                                订单编号开头：
                            </div>

                            <div class="col-xs-12 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">
                                <form action="{{ url('/setting/order/clt_o_codePrefix') }}" method="post">
                                    @csrf
                                    @method('patch')
                                    <div class="row">
                                        <div class="col-9">
                                            <input type="text"
                                                   class="form-control form-control-sm m-0 @error('clt_o_codePrefix') is-invalid @enderror"
                                                   name="clt_o_codePrefix"
                                                   value="{{ old('clt_o_codePrefix') ?? $order['clt_o_codePrefix'] ?? "" }}"
                                                   placeholder="编号">

                                            @error('clt_o_codePrefix')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-3">
                                            <button type="submit" class="btn btn-primary btn-sm m-0 w-100">保存</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row" id="setting-order-shipping-fee-kampar">
                            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-4 align-content-center text-sm-left text-md-right mb-2">
                                金宝外送邮费：
                            </div>

                            <div class="col-xs-12 col-sm-8 col-md-9 col-lg-8 mb-3 text-center">
                                <form action="{{ url('/setting/order/clt_o_shippingFeeKampar') }}" method="post">
                                    @csrf
                                    @method('patch')
                                    <div class="row">
                                        <div class="col-9">
                                            <input type="number"
                                                   class="form-control form-control-sm m-0 @error('clt_o_shippingFeeKampar') is-invalid @enderror"
                                                   name="clt_o_shippingFeeKampar"
                                                   value="{{ old('clt_o_shippingFeeKampar') ?? $order['clt_o_shippingFeeKampar'] ?? "0.00" }}"
                                                   placeholder="价格（RM）">

                                            @error('clt_o_shippingFeeKampar')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-3">
                                            <button type="submit" class="btn btn-primary btn-sm m-0 w-100">保存</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="other-tab">
                        Other
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('extraScriptEnd')
    <script>
        $(function () {

        })

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
                                   placeholder="类别">
                        </div>
                        <div class="col-md-6 col-sm-12 pl-md-1">
                            <input type="text"
                                   class="form-control"
                                   name="category[${categoryCount}][name_en]"
                                   placeholder="Category">
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
            $('#myTab a[href="#{{ $_GET['show'] ?? 'item' }}"]').tab('show');

            $('#extra-category-button').on('click', function () {
                $('#category-section').append(getExtraCategoryHTML(getCategoryCount()));
            });
        });
    </script>
@endsection
