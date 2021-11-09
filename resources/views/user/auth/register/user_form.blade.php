@extends('user.layouts.auth')

@section('title', '会員登録')

@section('header')
    <h1 id="title">会員登録</h1>
@endsection

@section('content')
    <main>
        {{ Form::open(['route' => ['user.register.set_user', ['user'=>$user->id]], 'id' => 'frm_user_data', 'name' => 'frm_user_data', 'method' => 'POST']) }}
            <div id="wrapper" class="contents">
                <table class="table">
                    <tr>
                        <th>お名前<span>※</span></th>
                        <td>
                            <input type="text" name="name" value="{{ old('name') }}">
                            @error('name')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>生年月日<span>※</span></th>
                        <td>
                            <select name="birth_year" id="birth_year" onchange="setLeapMonth()">
                                <option value="">--</option>
                                @foreach(range(date('Y'), date('Y')-100) as $year )
                                    <option value="{{ $year }}" {{ $year==old('birth_year') ? 'selected' : '' }}>{{ $year }}年</option>
                                @endforeach
                            </select>

                            <select name="birth_month" id="birth_month" onchange="setLeapMonth()">
                                <option value="">--</option>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ $month==old('birth_month') ? 'selected' : '' }}>{{ $month }}月</option>
                                @endforeach
                            </select>
                            @php
                                $year = old('birth_year') ? old('birth_year') : date('Y');
                                $month = old('birth_month') ?  old('birth_month') : 1;
                                $month_last_day = Carbon::parse("{$year}-{$month}-1")->endOfMonth()->format('d');
                            @endphp
                            <select name="birth_day" id="birth_day">
                                <option value="">--</option>
                                @foreach(range(1, $month_last_day) as $day)
                                    <option value="{{ $day }}" {{ $day==old('birth_day') ? 'selected' : '' }}>{{ $day }}日</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="birthday">
                            @error('birthday')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>

                    <tr>
                        <th>性別<span>※</span></th>
                        <td>
                            <ul class="line">
                                @foreach(config('const.gender') as $key=>$value)
                                    <li>
                                        <input type="radio" name="gender" id="gender{{ $key }}" value="{{ $key }}" {{ old('gender') == $key ? 'checked' : '' }}>
                                        <label for="gender{{ $key }}">{{ $value }}</label>
                                    </li>
                                @endforeach
                            </ul>
                            @error('gender')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>ログインID（メールアドレス）<span>※</span></th>
                        <td><input type="text" readonly value="{{ $user->email }}"></td>
                    </tr>
                    <tr>
                        <th>ログインパスワード<span>※</span></th>
                        <td>
                            <input type="password" name="password" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$">
                            @error('password')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>ログインパスワード確認<span>※</span></th>
                        <td>
                            <input type="password" name="password_confirmation" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$">
                        </td>
                    </tr>
                    <tr>
                        <th>プライバシーポリシー</th>
                        <td><div class="privacy">
                                <h2>タイトルタイトルタイトルタイトル</h2>
                                <p>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
                                <p>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
                            </div>
                            <div class="ok">
                                <input type="checkbox" id="agree_privacy" name="agree_privacy" value="ok"  {{ old('agree_privacy') == 'ok' ? 'checked' : '' }}>
                                <label for="agree_privacy">同意する</label>
                            </div></td>
                    </tr>
                </table>
                <input type="submit" id="btn_submit" class="{{ old('agree_privacy') == 'ok' ? '' : 'disabled' }}" value="登録する" {{ old('agree_privacy') == 'ok' ? '' : 'disabled' }}>
            </div>
        {{ Form::close()}}
    </main>
@endsection

@section('page_css')

@endsection

@section('page_js')
    <script>
        $(document).ready(function(){
            $("#agree_privacy").change(function() {
                if ($(this).is(':checked')) {
                    $('#btn_submit').removeClass('disabled');
                    $('#btn_submit').prop( "disabled", false );
                } else {
                    $('#btn_submit').addClass('disabled');
                    $('#btn_submit').prop( "disabled", true );
                }
            });
        });

        function setLeapMonth() {
            var year = parseInt($('#birth_year').val());
            var month =  parseInt($('#birth_month').val());
            var leap_year = false;
            if((year % 400==0 || year%100!=0) &&(year%4==0)) {
                leap_year = true;
            }
            var end_day = 31;
            switch (month) {
                case 2:
                    if (leap_year) {
                        end_day = 29;
                    } else {
                        end_day = 28;
                    }
                    break;
                case 4:
                case 6:
                case 9:
                case 11:
                    end_day = 30;
                    break;
            }
            var day_content = '<option value="">--</option>';
            for (let i = 1; i <= end_day; i++) {
                day_content += '<option value="' + i + '">' + i + '日</option>';
            }
            $("#birth_day").empty().html(day_content);
        }
    </script>
@endsection

