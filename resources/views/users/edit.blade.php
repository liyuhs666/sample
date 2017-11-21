@extends('layouts.default')
@section('title', '更新个人资料')

@section('content')
<div class="col-md-offset-2 col-md-8">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4>更新个人资料</h4>
      <p style="color: #707070;font-size: 12px">用注册邮箱到 <a href="http://gravatar.com/emails" target="_blank">gravatar</a>官网注册一个帐号,即可修改头像</p>
    </div>
      <div class="panel-body">

        @include('shared._errors')

        <div class="gravatar_edit">
          <a href="http://gravatar.com/emails" target="_blank">
            <img src="{{ $user->gravatar('200') }}" alt="{{ $user->name }}" class="gravatar"/>
          </a>
        </div>

        <form method="POST" action="{{ route('users.update', $user->id )}}">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="form-group">
              <label for="name">名称：</label>
              <input type="text" name="name" class="form-control" value="{{ $user->name }}">
            </div>

            <div class="form-group">
              <label for="email">邮箱：</label>
              <input type="text" name="email" class="form-control" value="{{ $user->email }}" disabled>
            </div>
              


            <div id="changpasswd" style="display: none">
              <div class="form-group">
                <label for="password">密码：</label>
                <input type="password" name="password" class="form-control" value="{{ old('password') }}">
              </div>

              <div class="form-group">
                <label for="password_confirmation">确认密码：</label>
                <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}">
              </div>
            </div>

            <button type="submit" class="btn btn-primary">更新</button>
            <input type="hidden" name="changepsd" value="0" id="postvalue">
            <div id="cdpsd" style="" onclick="show(this)" type="submit" >修改密码</div>
        </form>
    </div>
  </div>
</div>

<script type="text/javascript">
function show(e){
  e.style.display="none";
  document.getElementById("changpasswd").style.display="inline";
  document.getElementById("postvalue").value=1;
}
</script>
@stop
