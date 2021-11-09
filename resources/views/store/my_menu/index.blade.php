@extends('store.layouts.app')

@section('title', 'マイメニュー')

@section('header')
    <h1 id="title">マイメニュー</h1>
@endsection

@section('content')
    <main> 
            <nav>
                <ul class="user-nav"> 
                    <li> <a href="{{ route('store.bill.total_sales') }}">売上集計</a></li>
                    <li> <a href="{{ route('store.my_menu.transfer_history') }}">振込履歴</a></li>
                    <li> <a href="{{ route('store.my_account.info') }}">基本情報の確認・編集</a></li>
                    <li> <a href="{{ route('store.my_menu.mail_edit') }}">メールアドレス変更</a></li>
                    <li> <a href="{{ route('store.my_menu.password_edit') }}">パスワード変更</a></li> 
                    <li> <a href="{{ route('store.my_menu.bank_transfer_info') }}">銀行振込口座の登録・編集</a></li>
                    <li> <a href="{{ route('store.my_menu.flow_of_use') }}">ご利用の流れ</a></li>
                    <li> <a href="{{ route('store.my_menu.fag') }}">よくあるご質問</a></li>
                    <li> <a href="{{ route('store.my_menu.terms_of_service') }}">ご利用規約</a></li>
                    <li> <a href="{{ route('store.my_menu.contact_us') }}">お問い合わせ</a></li>
                    <li> <a href="{{ route('store.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a></li>
                </ul>
            </nav> 
            <form id="logout-form" action="{{ route('store.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </main>
@endsection