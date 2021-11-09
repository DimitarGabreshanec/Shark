@extends('admin.layouts.app')

@section('content')

    <h2 class="title">商品ー覧</h2>
    <div class="hscroll mt40">
        @include('admin.layouts.flash-message')

        <div class="search_wrap">
            <h3 class="search_title">検索条件</h3>
            {{ Form::open(['route'=>"admin.products.index", 'method'=>'get']) }}
            <div class="table-search_wrap">
                <table class="search half_table">
                    <tr>
                        <th>商品名</th>
                        <td><input type="text" name="search_params[product_name]" value="{{ isset($search_params['product_name']) ? $search_params['product_name'] : "" }}" class="input-text"></td>
                        <th>出品価格</th>

                        <td>
                            <div class="select-wrap display-inline-flex">
                                @php
                                    $price_from = old('price_from',  isset($product->price_from) ? $product->price_from : '' );
                                @endphp
                                <input type="text" class="input-text w100 text-right" name="search_params[price_from]" value="{{ isset($search_params['price_from']) ? $search_params['price_from'] : "" }}" class="input-text">
                            </div>
                            <div class="select-wrap display-inline-flex">&nbsp;&nbsp;&nbsp;&nbsp;~</div>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="select-wrap display-inline-flex">
                                @php
                                    $price_to = old('price_to',  isset($product->price_to) ? $product->price_to : '' );
                                @endphp
                                <input type="text" class="input-text w100 text-right" name="search_params[price_to]" value="{{ isset($search_params['price_to']) ? $search_params['price_to'] : "" }}" class="input-text">
                            </div>
                            <div class="select-wrap display-inline-flex">&nbsp;&nbsp;円</div>
                        </td>
                    </tr>

                    <tr>
                        <th>店舗</th>
                        <td><input type="text" name="search_params[store_name]" value="{{ isset($search_params['store_name']) ? $search_params['store_name'] : "" }}" class="input-text"></td>
                        <th>商品種類</th>
                        <td>
                            <div class="radiobtn-wrap f-left">
                                <input type="radio" name="search_params[type]" id="type_all" value="all" {{ 'all' === (isset($search_params['type']) ? $search_params['type'] : 'all') ? 'checked' : '' }}>
                                <label for="type_all" class="switch-on">すべて</label>
                                @foreach(config('const.product_type') as $key => $value)
                                    <input type="radio" name="search_params[type]" id="type{{ $key }}" value="{{ $key }}" {{ (string)$key === (isset($search_params['type']) ? $search_params['type'] : '') ? 'checked' : '' }}>
                                    <label for="type{{ $key }}" class="switch-on">{{ $value }}</label>

                                @endforeach
                            </div>
                        </td>

                    </tr>
                </table>
            </div>
            <div class="center-submit center">
                <input type="submit" value="検索">
                <input type="button" class="btn-clear" data-url="{{ route('admin.products.index') }}" value="クリア">
            </div>

            {{ Form::close() }}
        </div>

        {{ $products->appends(['search_params' =>$search_params])->links('vendor.pagination.admin-pagination') }}
        <table class="normal type3">
            <thead>
            <tr>
                <th>詳細</th>
                <th>商品番号</th>
                <th>商品名</th>
                <th>メイン画像</th>
                <th>店舗名</th>
                <th>店舗</th>
                <th>価格</th>
                <th>割引</th>
                <th>在庫数</th>
                <th>備考</th>
                <th>登録日時</th>
            </tr>
            </thead>
            <tbody>
            @if ($products->count() > 0)
                @foreach ($products as $record)
                    <tr>
                        <td>
                            <p><a href="{{ route("admin.products.edit", ["product"=>$record->id]) }}">編集</a></p>
                            <p><a href="{{ route("admin.products.show", ["product"=>$record->id]) }}">詳細</a></p>
                            <p><a href="#" class="delete_record" cidx="{{ $record->id }}">削除</a></p>

                            <form id="frm_delete_{{ $record->id }}" action="{{ route('admin.products.destroy', ['product'=> $record->id]) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </td>
                        <td>{{ $record->product_no }}</td>
                        <td>{{ $record->product_name }}</td>
                        <td>
                            @if(is_object($record->obj_main_img) && Storage::disk('products')->has("{$record->id}/{$record->obj_main_img->img_name}"))
                                <img class="w100" src="{{ asset("storage/products/{$record->id}/{$record->obj_main_img->img_name}") }}">
                            @endif
                        </td>
                        <td>{{ config('const.product_type.' . $record->type) }}</td>
                        <td>{{ is_object($record->obj_store) ? $record->obj_store->store_name : '' }}</td>
                        <td>{{ $record->price ? (number_format($record->price) . "円"): ''}}</td>
                        <td>{{ $record->discount ? (number_format($record->discount).($record->discount_type == config('const.discount_type.percent') ? "%" : "円")) : ''}}</td>
                        <td>{{ $record->quantity ? number_format($record->quantity) : '' }}</td>
                        <td class="w150">{{ Str::limit($record->note,30, '...') }}</td>
                        <td class="w150">{{ $record->created_at ? Carbon::parse($record->created_at)->format('Y.m.d H:i') : '' }}</td>
                    </tr>
                @endforeach
            @else
                <tr> 
                    <td class="text-center" colspan="11">データが存在しません。</td>
                </tr>
            @endif
            </tbody>
        </table>
        {{ $products->appends(['search_params' =>$search_params])->links('vendor.pagination.admin-pagination') }}
    </div>
@endsection
