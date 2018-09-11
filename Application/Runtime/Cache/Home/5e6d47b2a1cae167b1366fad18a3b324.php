<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/odcrm/Public/Home/images/favicon.png" type="image/png">

    <title>运营CRM后台</title>

    <link href="/odcrm/Public/Home/css/style.default.css" rel="stylesheet"/>
    <script src="/odcrm/Public/Home/js/jquery-1.11.1.min.js"></script>

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
            <img src="/odcrm/Public/Home/images/logo.png"/>
        </div><!-- logopanel -->

        <div class="leftpanelinner">

            <div class="visible-xs hidden-sm hidden-md hidden-lg">
                <div class="media userlogged">
                    <img alt="" src="/odcrm/Public/Home/images/photos/loggeduser.png" class="media-object">
                    <div class="media-body">
                        <h4><?php echo ($result["name"]); ?></h4>
                    </div>
                </div>
                <h5 class="sidebartitle actitle">Account</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket mb30">
                    <li><a href=""><i class="fa fa-cog"></i> <span>设置</span></a></li>
                    <li><a href="signout.html"><i class="fa fa-sign-out"></i> <span>退出</span></a></li>
                </ul>
            </div>

            <ul class="nav nav-pills nav-stacked nav-bracket">
                <?php if(($check) != $Channel): ?><li id="channel"><a href="/odcrm/Channel/channel.html"><i class="fa fa-list"></i>
                        <span>渠道管理</span></a></li><?php endif; ?>
                <?php if(($check) != $trade): ?><li id="trade"><a href="/odcrm/Trade/trade.html"><i class="fa fa-usd"></i> <span>客户管理</span></a>
                    </li><?php endif; ?>
                <?php if(($check) != $glod): ?><li id="glod"><a href="/odcrm/Trade/glod.html"><i class="fa fa-signal"></i> <span>出入金管理</span></a>
                    </li><?php endif; ?>
                <?php if(($check) != $Sms): ?><li id="sms"><a href="/odcrm/Sms/sms.html"><i class="fa fa-envelope-o"></i> <span>短信管理</span></a>
                    </li><?php endif; ?>
                <?php if(($check) != $Data): ?><li class="nav-parent" id="Data"><a><i class="fa fa-edit"></i> <span>数据统计</span></a>
                        <ul class="children">
                            <?php if(($check) != $channellist): ?><li id="channellist"><a href="/odcrm/Data/channellist.html"><i class="fa fa-caret-right"></i> 渠道列表</a></li><?php endif; ?>
                            <?php if(($check) != $performance): ?><li id="performance"><a href="/odcrm/Data/performance.html"><i class="fa fa-caret-right"></i> 绩效分析</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?>
                <?php if(($check) != $Auth): ?><li id="user"><a href="/odcrm/Auth/user.html"><i class="fa fa-users"></i> <span>角色管理</span></a>
                    </li><?php endif; ?>
            </ul>


        </div>
    </div>

    <div class="mainpanel">
        <div class="headerbar">
            <a class="menutoggle"><i class="fa fa-bars"></i></a>
            <div class="header-right">
                <ul class="headermenu">
                    <li>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <img src="/odcrm/Public/Home/images/photos/loggeduser.png" alt=""/>
                                <?php echo ($arr["auth"]["name"]); ?>
                                <span class="caret"></span>
                            </button>
                            <input type="hidden" value='<?php echo ($arr["auth"]["manager"]); ?>' id="user_auth"/>
                            <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                                <li><a href="#"><i class="glyphicon glyphicon-cog"></i> 设置</a></li>
                                <li><a href="/odcrm/Login/login.html"><i class="glyphicon glyphicon-log-out"></i>
                                    退出</a></li>
                            </ul>
                        </div>
                    </li>

                </ul>
            </div>
            <!-- header-right -->
        </div><!-- headerbar -->
        <div class="lineSpilt">&nbsp;</div>
        <div class="contentBox">
            

    <ul class="breadcrumb panel headTop">
        <li><i class="fa fa-list"></i> 出金管理</li>
    </ul>

    <section class="panel divBox">
        <header class="panel-heading">
            <div class="selectArea">


                <div class="serachDate">
                    <div class="col-md-4">
                        <div class="input-group input-large custom-date-range">
                            <span class="input-group-addon">姓名</span>
                            <input type="text" class="searchInput" id="channelName" placeholder="姓名">

                        </div>
                    </div>
                </div>


                <div class="serachDate">
                    <div class="col-md-4">
                        <div class="input-group input-large custom-date-range">
                                <span class="input-group-addon" data-toggle="dropdown">
                                    <span id="selectTime">出金时间</span>

                                </span>

                            <input type="text" class="searchInput" id="dateStart" value="<?php echo ($arr['startDate']); ?>">
                            <span class="input-group-addon">To</span>
                            <input type="text" class="searchInput" id="dateEnd" value="<?php echo ($arr['endDate']); ?>">
                        </div>
                    </div>
                </div>

                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-primary  dropdown-toggle btn-sm" type="button">
                        <span id="selectChannel" name="selectChannel">全部渠道类型</span>
                        <span class="caret"></span>
                    </button>
                    <ul role="menu" class="dropdown-menu">
                        <?php foreach($channel as $val) { ?>
                        <li class="pointer" onclick="sheChannelType('<?php echo ($val["channel_type"]); ?>')"><?php echo ($val["channel_type"]); ?></li>
                        <?php } ?>
                        <li class="divider"></li>
                        <li class="pointer" onclick="sheChannelType('全部渠道类型')">全部渠道类型</li>
                    </ul>
                </div>



                <div class="btn-group authAll">
                    <button data-toggle="dropdown" class="btn btn-primary  dropdown-toggle btn-sm" type="button">
                        <span id="selectParent">全部转化人</span>
                        <input type="hidden" id="parentHidden" value="全部转化人"/>
                        <span class="caret"></span>
                    </button>
                    <ul role="menu" class="dropdown-menu">
                        <?php foreach($conversion as $val) { ?>
                        <li class="pointer" onclick="sheParentType('<?php echo ($val["name"]); ?>','<?php echo ($val["name"]); ?>')"><?php echo ($val["name"]); ?></li>
                        <?php } ?>
                        <li class="divider"></li>
                        <li class="pointer" onclick="sheParentType('全部转化人')">全部转化人</li>
                    </ul>
                </div>

                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-primary  dropdown-toggle btn-sm" type="button">
                        <span id="selectProcess">服务进度</span>
                        <span class="caret"></span>
                    </button>
                    <ul role="menu" class="dropdown-menu">
                        <?php foreach($process as $key=>$val) { ?>
                        <li class="pointer" onclick="sheProcessType('<?php echo ($val); ?>','<?php echo ($key); ?>')"><?php echo ($val); ?></li>
                        <input type="hidden" id="processHidden" value="服务进度"/>
                        <?php } ?>
                        <li class="divider"></li>
                        <li class="pointer" onclick="sheProcessType('服务进度','服务进度')">服务进度</li>
                    </ul>
                </div>
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-success btn-sm pad25" type="button" id="search"
                            onclick="getData('../Trade/getgetGlodData')">
                        <span>查&nbsp;询</span>
                    </button>
                </div>

            </div>

        </header>

        <div class="dataAll">

            <div class="loading" style="display: none;width: 100%;text-align: center;">
                <img src="/odcrm/Public/Home/images/loading.gif"/>
            </div>

            <div id="get_data_area"></div>

        </div>

    </section>



        </div>
    </div><!-- mainpanel -->


</section>


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
<script src="/odcrm/Public/Home/js/jquery.form.min.js"></script>
<script src="/odcrm/Public/Home/js/bootstrap-datetimepicker.min.js"></script>
<script src="/odcrm/Public/Home/js/common.js"></script>
<link href="/odcrm/Public/Home/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
<script src="/odcrm/Public/Home/js/AES.js"></script>
<script src="/odcrm/Public/Home/js/pad-zeropadding-min.js"></script>
<!--分页插件-->
<script src="/odcrm/Public/Home/js/kkpager.min.js" type="text/javascript"></script>
<link href="/odcrm/Public/Home/css/kkpager_orange.css" rel="stylesheet" type="text/css"/>

</body>
</html>