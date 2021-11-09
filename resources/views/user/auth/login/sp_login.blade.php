@extends('user.layouts.sp')

@section('content')
    <div id="wrapper" class="contents">
        <input type="submit" value="ログインへ" onclick="goToLogin();">
        <ion-button id="btn_to_login" (click)="_self.goToLogin()"></ion-button>
    </div>
@endsection

<script>
    function goToLogin() {
        $('#btn_to_login').click();
    }
</script>
