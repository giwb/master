<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
  <div class="card card-login mx-auto mt-5">
    <div class="card-header">ログイン</div>
    <div class="card-body">
      <form>
        <div class="form-group">
          <div class="form-label-group">
            <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required="required" autofocus="autofocus">
            <label for="inputEmail">メールアドレス</label>
          </div>
        </div>
        <div class="form-group">
          <div class="form-label-group">
            <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="required">
            <label for="inputPassword">パスワード</label>
          </div>
        </div>
        <div class="form-group">
          <div class="checkbox">
            <label>
              <input type="checkbox" value="remember-me">
              パスワードを保存する
            </label>
          </div>
        </div>
        <a class="btn btn-primary btn-block" href="index.html">ログイン</a>
      </form>
      <div class="text-center">
        <a class="d-block small mt-3 btn-ready" href="javascript:;">パスワードをお忘れの方</a>
      </div>
    </div>
  </div>
</div>
