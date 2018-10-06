<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>今日奇观后台-登录</title>

    <link href="/product/qiguan/Public/Home/css/login.css" rel="stylesheet" type="text/css">
    <script src="/product/qiguan/Public/Home/js/jquery.js" type="text/javascript"></script>


</head>
<body class="login-body">
<div class="container">

    <div class="form-signin" id="signForm">
        <div class="form-signin-heading text-center">
            <h1 class="sign-title">Sign In</h1>
            <img src="/product/qiguan/Public/Home/images/login-logo.png" alt="">
        </div>

        <form class="login-wrap" action="login" method="post">

            <input type="text" class="form-control" name="user"/>
            <input type="password" class="form-control" name="pwd"/>
            <button class="btn btn-lg btn-login btn-block" >登录</button>
        </form>

    </div>

</div>
</body>
</html>