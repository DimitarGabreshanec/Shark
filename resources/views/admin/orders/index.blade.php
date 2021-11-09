@extends('admin.layouts.app')

@section('content')
    <h2 class="title">注文管理</h2>

    <div class="hscroll mt40">
        @include('admin.layouts.flash-message')

        <div class="search_wrap">
            <h3 class="search_title">検索条件</h3>
            {{ Form::open(['route' => ['admin.orders.index'], 'id' => 'order-search-form', 'name' => 'order-search-form', 'method' => 'GET']) }}
            <div class="table-search_wrap">
                <table class="search half_table">
                    <tr>
                        <th>注文番号</th>
                        <td>
                            <input type="text" value="{{ isset($search_params['order_no']) ? $search_params['order_no'] : "" }}" class="input-text"  name="search_params[order_no]" id="order_no">
                        </td>
                        <th>注文者</th>
                        <td>
                            <input type="text" value="{{ isset($search_params['user_name']) ? $search_params['user_name'] : "" }}" class="input-text"  name="search_params[user_name]" id="user_name">
                        </td>
                    </tr>
                    <tr>
                        <th>注文日時</th>
                        <td>
                            <input type="text" value="{{ isset($search_params['order_start_at']) ? $search_params['order_start_at'] : "" }}" class="input-text datepicker w40per"  name="search_params[order_start_at]" id="order_start_at">&nbsp;～&nbsp;
                            <input type="text" value="{{ isset($search_params['order_end_at']) ? $search_params['order_end_at'] : "" }}" class="input-text datepicker w40per"  name="search_params[order_end_at]" id="order_end_at">
                        </td>
                        <th>ステータス</th>
                        <td>
                            <div class="select-wrap">
                                <label>
                                    <select name="search_params[order_status]" id="order_status">
                                        <option value=""></option>
                                        @foreach(config('const.order_status_admin') as $k => $v)
                                            <option value="{{ $k }}" {{ isset($search_params['order_status']) ? ($k == $search_params['order_status'] ? "selected" : "") : "" }}>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>処理日時</th>
                        <td>
                            <input type="text" value="{{ isset($search_params['completed_start_at']) ? $search_params['completed_start_at'] : "" }}" class="input-text datepicker w45per"  name="search_params[completed_start_at]" id="completed_start_at">&nbsp;～&nbsp;
                            <input type="text" value="{{ isset($search_params['completed_end_at']) ? $search_params['completed_end_at'] : "" }}" class="input-text datepicker w45per"  name="search_params[completed_end_at]" id="completed_end_at">
                        </td>
                        <th></th>
                        <td>
                            {{--<input type="text" value="{{ isset($search_params['number']) ? $search_params['number'] : "" }}" class="input-text"  name="search_params[number]" id="number">--}}
                        </td>
                    </tr>

                </table>
            </div>

            <div class="center-submit center">
                <input type="submit" value="検索">
                <input type="button" class="btn-clear" data-url="{{ route('admin.orders.index') }}" value="クリア">
            </div>
            {{ Form::close() }}
        </div>

        <div class="contents_wrap">
            <div class="table_wrap">
                {{ $orders->links('vendor.pagination.admin-pagination') }}
                <div class="table_inner">
                    <table class="normal type3">
                        <thead>
                        <tr>
                            <th>アクション</th>
                            <th class="w80">注文番号</th>
                            <th>注文者</th>
                            <th>注文日時</th>
                            <th>引受者</th>
                            <th>電話番号</th>
                            <th>住所</th>
                            <th>注文金額</th>
                            <th>ステータス</th>
                            <th>処理日時</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if ($orders->count() > 0)
                            @foreach ($orders as $key => $record)
                                <tr>
                                    <td>
                                        <p><a href="{{ route('admin.orders.detail', ['order'=>$record->id]) }}">詳細</a></p>
                                    </td>
                                    <td>{{ $record->order_no }}</td>
                                    <td>{{ $record->obj_user->name }}</td>
                                    <td>{{ $record->ordered_at ? Carbon::parse($record->ordered_at)->format('Y.m.d H:i:s') : '' }}</td>
                                    <td>{{ $record->target_client() }}</td>
                                    <td>{{ $record->tel }}</td>
                                    <td>{{ $record->target_address() }}</td>
                                    <td>{{ $record->order_price ? ('￥' . number_format($record->order_price)) : '' }}</td>
                                    <td style="background-color: {{ config('const.order_status_color.' . $record->order_status) }}">{{ config('const.order_status_admin.' . $record->order_status) }}</td>
                                    <td>{{ $record->completed_at ? Carbon::parse($record->completed_at)->format('Y.m.d H:i:s') : '' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="10">データが存在しません。</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                {{ $orders->links('vendor.pagination.admin-pagination') }}
            </div>
        </div>

    </div>
@endsection

@section('page_css')
    <style>
        input.datepicker {
            width: 120px !important;
            margin-right: 5px;
        }
        img.ui-datepicker-trigger {
            padding-top: 3px;
        }
    </style>
@endsection

@section('page_js')
@endsection
