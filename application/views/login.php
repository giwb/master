<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>BtoB - ログイン</title>
    <link rel="shortcut icon" href="/public/images/favicon.ico">
    <link href="/public/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/public/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/public/css/style.css" rel="stylesheet" type="text/css">
  </head>
  <body id="page-top" class="bg-dark">

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

    <script src="/public/js/jquery.min.js" type="text/javascript"></script>
    <script src="/public/js/jquery.easing.min.js" type="text/javascript"></script>
    <script src="/public/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/public/js/script.js" type="text/javascript"></script>

  </body>
</html>
