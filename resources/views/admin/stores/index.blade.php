@extends('admin.layouts.app')

@section('content')

    <h2 class="title">店舗ー覧</h2>
    <div class="hscroll mt40">
        @include('admin.layouts.flash-message')

        <div class="search_wrap">
            <h3 class="search_title">検索条件</h3>
            {{ Form::open(['route'=>"admin.stores.index", 'method'=>'get']) }}
            <div class="table-search_wrap">
                <table class="search half_table">
                    <tr>
                        <th>店舗番号</th>
                        <td><input type="text" name="search_params[store_no]" value="{{ isset($search_params['store_no']) ? $search_params['store_no'] : "" }}" class="input-text"></td>
                        <th>ログインID</th>
                        <td><input type="text" name="search_params[email]" value="{{ isset($search_params['email']) ? $search_params['email'] : "" }}" class="input-text"></td>
                    </tr>

                    <tr>
                        <th>店舗名</th>
                        <td><input type="text" name="search_params[store_name]" value="{{ isset($search_params['store_name']) ? $search_params['store_name'] : "" }}" class="input-text"></td>
                        <th>ステータス</th>
                        <td>
                            <div class="radiobtn-wrap f-left">
                                <input type="radio" name="search_params[status]" id="status_all" value="all" {{ 'all' === (isset($search_params['status']) ? $search_params['status'] : 'all') ? 'checked' : '' }}>
                                <label for="status_all" class="switch-on">すべて</label>
                                @foreach(config('const.store_status') as $key => $value)
                                    <input type="radio" name="search_params[status]" id="status{{ $key }}" value="{{ $key }}" {{ (string)$key === (isset($search_params['status']) ? $search_params['status'] : '') ? 'checked' : '' }}>
                                    <label for="status{{ $key }}" class="switch-on">{{ $value }}</label>

                                @endforeach
                            </div>
                        </td>

                    </tr>
                </table>
            </div>
            <div class="center-submit center">
                <input type="submit" value="検索">
                <input type="button" class="btn-clear" data-url="{{ route('admin.stores.index') }}" value="クリア">
            </div>

            {{ Form::close() }}
        </div>

        {{ $stores->appends(['search_params' =>$search_params])->links('vendor.pagination.admin-pagination') }}
        <table class="normal type3">
            <thead>
            <tr>
                <th>詳細</th>
                <th>店舗番号</th>
                <th>種別</th>
                <th>ログインID</th>
                <th>店舗名</th>
                <th>メイン画像</th>
                <th>店舗所在地</th>
                <th>担当者名</th>
                <th>電話番号</th>
                <th>登録ステータス</th>
                <th>登録日時</th>
            </tr>
            </thead>
            <tbody>
            @if ($stores->count() > 0)
                @foreach ($stores as $record)
                    <tr>
                        <td>
                            <p><a href="{{ route("admin.stores.edit", ["store"=>$record->id]) }}">編集</a></p>
                            <p><a href="{{ route("admin.stores.show", ["store"=>$record->id]) }}">詳細</a></p>
                            <p><a href="#" class="delete_record" cidx="{{ $record->id }}">削除</a></p>

                            <form id="frm_delete_{{ $record->id }}" action="{{ route('admin.stores.destroy', ['store'=> $record->id]) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </td>
                        <td>{{ $record->store_no }}</td>
                        <td>{{ config('const.store_type.' . $record->type) }}</td>
                        <td>{{ $record->email }}</td>
                        <td>{{ $record->store_name }}</td>
                        <td> 
                            @if(is_object($record->obj_main_img) && Storage::disk('stores')->has("{$record->id}/{$record->obj_main_img->img_name}"))
                                <img class="w100" src="{{ asset("storage/stores/{$record->id}/{$record->obj_main_img->img_name}") }}">
                            @endif
                        </td> 
                        <td>{{ \App\Service\AreaService::getPrefectureNameByID($record->prefecture)  }}{{ $record->store_address }}</td>
                        <td>{{ $record->charger_name }}</td>
                        <td>{{ $record->tel }}</td>
                        <td>{{ config('const.store_status.' . $record->status) }}</td>
                        <td>{{ $record->created_at ? Carbon::parse($record->created_at)->format('Y.m.d H:i') : '' }}</td>
                    </tr>
                @endforeach
            @else
                <tr> 
                    <td class="text-center" colspan="11">データが存在しません。</td>
                </tr>
            @endif
            </tbody>
        </table>
        {{ $stores->appends(['search_params' =>$search_params])->links('vendor.pagination.admin-pagination') }}
    </div>
@endsection
