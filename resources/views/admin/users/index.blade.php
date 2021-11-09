@extends('admin.layouts.app')

@section('content')

    <h2 class="title">ユーザーー覧</h2>
    <div class="hscroll mt40">
        @include('admin.layouts.flash-message')

        <div class="search_wrap">
            <h3 class="search_title">検索条件</h3>
            {{ Form::open(['route'=>"admin.users.index", 'method'=>'get']) }}
            <div class="table-search_wrap">
                <table class="search half_table">
                    <tr>
                        <th>UID</th>
                        <td><input type="text" name="search_params[member_no]" value="{{ isset($search_params['member_no']) ? $search_params['member_no'] : "" }}" class="input-text"></td>
                        <th>メールアドレス</th>
                        <td><input type="text" name="search_params[email]" value="{{ isset($search_params['email']) ? $search_params['email'] : "" }}" class="input-text"></td>
                    </tr>

                    <tr>
                        <th>名前</th>
                        <td><input type="text" name="search_params[name]" value="{{ isset($search_params['name']) ? $search_params['name'] : "" }}" class="input-text"></td>
                        <th>ステータス</th>
                        <td> 
                            <div class="radiobtn-wrap f-left">
                                <input type="radio" name="search_params[status]" id="status_all" value="all" {{ 'all' === (isset($search_params['status']) ? $search_params['status'] : 'all') ? 'checked' : '' }}>
                                <label for="status_all" class="switch-on">すべて</label>  
                                @foreach(config('const.user_status') as $key => $value)
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
                <input type="button" class="btn-clear" data-url="{{ route('admin.users.index') }}" value="クリア">
            </div>
            {{ Form::close() }}
        </div> 

        {{ $users->appends(['search_params' =>$search_params])->links('vendor.pagination.admin-pagination') }}
        <table class="normal type3">
            <thead>
            <tr>
                <th>詳細</th>
                <th>UID</th>
                <th>名前</th>  
                <th>メールアドレス</th>
                <th>性別</th> 
                <th>誕生日</th>  
                <th>ステータス</th>
                <th>最終ログイン日時</th> 
                <th>登録日時</th>
            </tr>
            </thead>
            <tbody>
            @if ($users->count() > 0)
                @foreach ($users as $record)
                    <tr>
                        <td>  
                            <p><a href="{{ route("admin.users.edit", ["user"=>$record->id]) }}">編集</a></p>
                            <p><a href="{{ route("admin.users.show", ["user"=>$record->id]) }}">詳細</a></p>
                            <p><a href="#" class="delete_record" cidx="{{ $record->id }}">削除</a></p>

                            <form id="frm_delete_{{ $record->id }}" action="{{ route('admin.users.destroy', ['user'=> $record->id]) }}" method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </td>
                        <td>{{ $record->member_no }}</td>
                        <td>{{ $record->name }}</td>
                        <td>{{ $record->email }}</td>
                        <td>{{ config('const.gender.' . $record->gender) }}</td>
                        <td>{{ isset($record->birthday) ? Carbon::parse($record->birthday)->format('Y年m月d日') : '' }}</td>  
                        <td>{{ config('const.user_status.' . $record->status) }}</td>
                        <td>{{ $record->last_login_at ? Carbon::parse($record->last_login_at)->format('Y.m.d H:i') : '' }}</td>
                        <td>{{ $record->created_at ? Carbon::parse($record->created_at)->format('Y.m.d H:i') : '' }}</td>
 
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="9">データが存在しません。</td>
                </tr>
            @endif
            </tbody>
        </table>
        {{ $users->appends(['search_params' =>$search_params])->links('vendor.pagination.admin-pagination') }}
    </div>
@endsection