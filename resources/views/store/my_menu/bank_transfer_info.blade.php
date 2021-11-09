@extends('store.layouts.app')

@section('title', '銀行振込口座の保存')

@section('header')
    <h1 id="title">銀行振込口座の保存</h1>
@endsection

@section('content')
    <main>
        {{ Form::open(["route"=>["store.my_menu.bank_transfer_update"], "method"=>"post"]) }}
        <div id="wrapper2">
            @include('store.layouts.flash-message')
            <table class="table">
                <tbody>
                <tr>
                    <th>銀行名</th>
                    <td>
                        <select name="bank" id="bank">
                            <option value="">--</option>
                            @foreach(\App\Service\BankService::getBanks() as $key => $bank)
                                <option value="{{ $bank['id'] }}"
                                        data-bank_code="{{ $bank['bank_code'] }}" {{ $bank['id'] == old('bank', isset($store) ? $store->bank : null)  ? 'selected' :'' }}>{{ $bank['bank_code'] }} {{ $bank['name'] }}</option>
                            @endforeach
                        </select>
                        @error('bank')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <th>支店名</th>
                    <td>
                        <select name="bank_branch" id="bank_branch">
                            <option value="">--</option>
                        </select>
                        @error('bank_branch')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <th>口座種別</th>
                    <td>
                        <select name="account_type" id="account_type">
                            <option value="">--</option>
                            @foreach(config('const.account_type') as $key => $value)
                                <option
                                    value="{{ $key }}" {{ old('account_type', isset($store) && isset($store->account_type) ? $store->account_type : '')  == $key ?  'selected' : '' }} >{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('account_type')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <th>口座番号</th>
                    <td>
                        <input type="text" name="account_no" class="input-text"
                               value="{{ old('account_no', isset($store) && isset($store->account_no) ? $store->account_no : '') }}">
                        @error('account_no')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <th>口座名(半角カタカナ)</th>
                    <td>
                        <input type="text" name="account_name" class="input-text"
                               value="{{ old('account_name', isset($store) && isset($store->account_name) ? $store->account_name : '') }}">
                        @error('account_name')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="submit" value="保存する">
        </div>
        {{ Form::close() }}
    </main>
@endsection

@section('page_js')
    <script>
        var g_bank_branch_id = "{{ old('bank_branch', isset($store) ? $store->bank_branch : null)  }}";
        $(document).ready(function (e) {
            getBranch();
            $('select#bank').change(function () {
                getBranch();
            });
        });

        function getBranch() {
            if ($('select#bank').val() != '') {
                var bank_code = $('select#bank option:selected').data('bank_code');

                $.ajax({
                    type: "post",
                    url: '{{ route("store.my_menu.get_bank_branch") }}',
                    data: {
                        bank_code: bank_code,
                        _token: '{{ csrf_token() }}',
                    },
                    dataType: "json",
                    success: function (result) {
                        if (result.result_code == 'success') {
                            var arr_branchs = result.branch;
                            var branch_element = "<option value=\"\">--</option>";
                            if (Object.keys(arr_branchs).length > 0) {
                                for (var key in arr_branchs) {
                                    branch_element += '<option value="' + arr_branchs[key]['id'] + '" >' + arr_branchs[key]['branch_code'] + '　' + arr_branchs[key]['name'] + '</option>';
                                }
                            }
                            $('#bank_branch').html(branch_element);
                            $('#bank_branch').val(g_bank_branch_id);
                        }
                    },
                });
            }
        }
    </script>
@endsection
