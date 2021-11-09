@extends('user.layouts.app')

@section('title', 'マイメニュー')

@section('header')
    <h1 id="title">マイメニュー</h1>
@endsection

@section('content')
    <main>
            <nav>
                <ul class="user-nav">
                    <li> <a href="{{ route('user.my_account.info') }}">基本情報の確認・編集</a></li>
                    <li> <a href="{{ route('user.my_account.mail_edit') }}">メールアドレス変更</a></li>
                    <li> <a href="{{ route('user.my_account.password_edit') }}">パスワード変更</a></li>
                    <li> <a href="{{ route('user.my_account.card_detail') }}">クレジットカードの登録・編集</a></li>
                    <li> <a href="{{ route('user.stores.imasugu_cond', ['back'=>'account'])  }}">イマスグ表示条件の登録・編集</a></li>
                    <li> <a href="{{ route('user.my_account.flow_of_use') }}">ご利用の流れ</a></li>
                    <li> <a href="{{ route('user.my_account.faq') }}">よくあるご質問</a></li>
                    <li> <a href="{{ route('user.my_account.terms_of_service') }}">ご利用規約</a></li>
                    <li> <a href="{{ route('user.my_account.privacy_police') }}">プライバシーポリシー</a></li>
                    <li> <a href="{{ route('user.my_account.contact_us') }}">お問い合わせ</a></li>
                    <li> <a href="{{ route('user.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a></li>
                </ul>
            </nav>
        <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </main>
@endsection
