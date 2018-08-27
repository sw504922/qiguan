<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/odcrm/Public/Home/images/favicon.png" type="image/png">

    <title>运营CRM后台</title>

    <link href="/odcrm/Public/Home/css/style.default.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/odcrm/Public/Home/js/html5shiv.js"></script>
    <script src="/odcrm/Public/Home/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>



<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<section>

    <div class="leftpanel">

        <div class="logopanel">
            <img src="/odcrm/Public/Home/images/logo.png" />
        </div><!-- logopanel -->

        <div class="leftpanelinner">

            <div class="visible-xs hidden-sm hidden-md hidden-lg">
                <div class="media userlogged">
                    <img alt="" src="/odcrm/Public/Home/images/photos/loggeduser.png" class="media-object">
                    <div class="media-body">
                        <h4>John Doe</h4>
                    </div>
                </div>
                <h5 class="sidebartitle actitle">Account</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket mb30">
                    <li><a href=""><i class="fa fa-cog"></i> <span>设置</span></a></li>
                    <li><a href="signout.html"><i class="fa fa-sign-out"></i> <span>退出</span></a></li>
                </ul>
            </div>

            <ul class="nav nav-pills nav-stacked nav-bracket">
                <li class="active"><a href="/odcrm/channel/channel"><i class="fa fa-list"></i> <span>渠道管理</span></a></li>
                <li><a href="/odcrm/trade/trade"><i class="fa fa-usd"></i> <span>入金管理</span></a></li>
                <li><a href="/odcrm/trade/glod"><i class="fa fa-signal"></i> <span>出金管理</span></a></li>
                <li class="nav-parent"><a href=""><i class="fa fa-edit"></i> <span>数据统计</span></a>
                    <ul class="children">
                        <li><a href="/odcrm/data/channellist"><i class="fa fa-caret-right"></i> 渠道列表</a></li>
                        <li><a href="/odcrm/data/performance"><i class="fa fa-caret-right"></i> 绩效分析</a></li>
                    </ul>
                </li>
            </ul>


        </div>
    </div>

    <div class="mainpanel">
        <div class="headerbar">

            <div class="header-right">
                <ul class="headermenu">
                    <li>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <img src="/odcrm/Public/Home/images/photos/loggeduser.png" alt="" />
                                John Doe
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                                <li><a href="#"><i class="glyphicon glyphicon-cog"></i> 设置</a></li>
                                <li><a href="/odcrm/Login/login.html"><i class="glyphicon glyphicon-log-out"></i> 退出</a></li>
                            </ul>
                        </div>
                    </li>

                </ul>
            </div>
            <!-- header-right -->
        </div><!-- headerbar -->
        <div class="lineSpilt">&nbsp;</div>
        <div class="contentBox">

    <ul class="breadcrumb panel">
        <li><i class="fa fa-list"></i> 渠道管理</li>

    </ul>
    <div class="selectArea">


        <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-primary  dropdown-toggle btn-sm" type="button">
                <span id="selectChannel">全部渠道类型</span>
                <span class="caret"></span>
            </button>
            <ul role="menu" class="dropdown-menu">
                <li class="pointer" onclick="sheChannelType('商务渠道')">商务渠道</li>
                <li class="pointer" onclick="sheChannelType('市场渠道')">市场渠道</li>
                <li class="pointer" onclick="sheChannelType('官方渠道')">官方渠道</li>
                <li class="pointer" onclick="sheChannelType('历史渠道')">历史渠道</li>
                <li class="divider"></li>
                <li class="pointer" onclick="sheChannelType('全部渠道类型')">全部渠道类型</li>
            </ul>
        </div>
        <input type="text" class="searchInput" id="keyword" placeholder="渠道名称">

        <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-primary  dropdown-toggle btn-sm" type="button">
                <span id="selectParent">全部推荐人</span>
                <span class="caret"></span>
            </button>
            <ul role="menu" class="dropdown-menu">
                <li class="pointer" onclick="sheParentType('商务渠道')">商务渠道</li>
                <li class="pointer" onclick="sheParentType('市场渠道')">市场渠道</li>
                <li class="pointer" onclick="sheParentType('官方渠道')">官方渠道</li>
                <li class="pointer" onclick="sheParentType('历史渠道')">历史渠道</li>
                <li class="divider"></li>
                <li class="pointer" onclick="sheParentType('全部推荐人')">全部推荐人</li>
            </ul>
        </div>

        <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-primary  dropdown-toggle btn-sm" type="button">
                <span id="selectManager">全部商务人员</span>
                <span class="caret"></span>
            </button>
            <ul role="menu" class="dropdown-menu">
                <li class="pointer" onclick="sheParentType('商务渠道')">商务渠道</li>
                <li class="pointer" onclick="sheParentType('市场渠道')">市场渠道</li>
                <li class="pointer" onclick="sheParentType('官方渠道')">官方渠道</li>
                <li class="pointer" onclick="sheParentType('历史渠道')">历史渠道</li>
                <li class="divider"></li>
                <li class="pointer" onclick="sheParentType('全部商务人员')">全部商务人员</li>
            </ul>
        </div>
        <div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-success btn-sm" type="button">
                <span>查&nbsp;&nbsp;询</span>
            </button>
        </div>
    </div>

</div>
    </div><!-- mainpanel -->



</section>

<script src="/odcrm/Public/Home/js/common.js"></script>
<script src="/odcrm/Public/Home/js/jquery-1.11.1.min.js"></script>
<script src="/odcrm/Public/Home/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/odcrm/Public/Home/js/jquery-ui-1.10.3.min.js"></script>
<script src="/odcrm/Public/Home/js/bootstrap.min.js"></script>
<script src="/odcrm/Public/Home/js/modernizr.min.js"></script>
<script src="/odcrm/Public/Home/js/jquery.sparkline.min.js"></script>
<script src="/odcrm/Public/Home/js/toggles.min.js"></script>
<script src="/odcrm/Public/Home/js/retina.min.js"></script>
<script src="/odcrm/Public/Home/js/jquery.cookies.js"></script>

<script src="/odcrm/Public/Home/js/morris.min.js"></script>
<script src="/odcrm/Public/Home/js/raphael-2.1.0.min.js"></script>

<script src="/odcrm/Public/Home/js/custom.js"></script>


</body>
</html>