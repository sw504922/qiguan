<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/qiguan/Public/Home/images/favicon.png" type="image/png">

    <title>今日奇观后台</title>

    <link href="/qiguan/Public/Home/css/style.default.css" rel="stylesheet"/>
    <script src="/qiguan/Public/Home/js/jquery-1.11.1.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="/qiguan/Public/Home/js/html5shiv.js"></script>
    <script src="/qiguan/Public/Home/js/respond.min.js"></script>
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
            <img src="/qiguan/Public/Home/images/logo.png"/>
        </div><!-- logopanel -->

        <div class="leftpanelinner">

            <div class="visible-xs hidden-sm hidden-md hidden-lg">
                <div class="media userlogged">
                    <img alt="" src="/qiguan/Public/Home/images/photos/loggeduser.png" class="media-object">
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
                <?php if(($check) != $Channel): ?><li id="channel"><a href="/qiguan/Channel/channel.html"><i class="fa fa-list"></i>
                        <span>渠道管理</span></a></li><?php endif; ?>
                <?php if(($check) != $trade): ?><li id="trade"><a href="/qiguan/Trade/trade.html"><i class="fa fa-usd"></i> <span>客户管理</span></a>
                    </li><?php endif; ?>
                <?php if(($check) != $glod): ?><li id="glod"><a href="/qiguan/Trade/glod.html"><i class="fa fa-signal"></i> <span>出入金管理</span></a>
                    </li><?php endif; ?>
                <?php if(($check) != $Sms): ?><li id="sms"><a href="/qiguan/Sms/sms.html"><i class="fa fa-envelope-o"></i> <span>短信管理</span></a>
                    </li><?php endif; ?>
                <?php if(($check) != $Data): ?><li class="nav-parent" id="Data"><a><i class="fa fa-edit"></i> <span>数据统计</span></a>
                        <ul class="children">
                            <?php if(($check) != $channellist): ?><li id="channellist"><a href="/qiguan/Data/channellist.html"><i class="fa fa-caret-right"></i> 渠道列表</a></li><?php endif; ?>
                            <?php if(($check) != $performance): ?><li id="performance"><a href="/qiguan/Data/performance.html"><i class="fa fa-caret-right"></i> 绩效分析</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?>
                <?php if(($check) != $Auth): ?><li id="user"><a href="/qiguan/Auth/user.html"><i class="fa fa-users"></i> <span>角色管理</span></a>
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
                                <img src="/qiguan/Public/Home/images/photos/loggeduser.png" alt=""/>
                                <?php echo ($arr["auth"]["name"]); ?>
                                <span class="caret"></span>
                            </button>
                            <input type="hidden" value='<?php echo ($arr["auth"]["manager"]); ?>' id="user_auth"/>
                            <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                                <li><a href="#"><i class="glyphicon glyphicon-cog"></i> 设置</a></li>
                                <li><a href="/qiguan/Login/login.html"><i class="glyphicon glyphicon-log-out"></i>
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
            

    <ul class="breadcrumb panel">
        <li><i class="fa fa-list"></i> 渠道管理</li>
    </ul>

    <section class="panel divBox">
        <header class="panel-heading">
            <div class="selectArea">

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

                <input type="text" class="searchInput" id="channelName" placeholder="渠道名称">

                <div class="serachDate">
                    <div class="col-md-4">
                        <div class="input-group input-large custom-date-range">
                            <span class="input-group-addon">创建时间</span>
                            <input type="text" class="searchInput" id="dateStart" value="<?php echo ($arr['startDate']); ?>">
                            <span class="input-group-addon">To</span>
                            <input type="text" class="searchInput" id="dateEnd" value="<?php echo ($arr['endDate']); ?>">
                        </div>
                    </div>
                </div>
                <div class="btn-group authAll">
                    <button data-toggle="dropdown" class="btn btn-primary  dropdown-toggle btn-sm" type="button">
                        <span id="selectParent">全部推荐人</span>
                        <input type="hidden" id="parentHidden" value="全部推荐人"/>
                        <span class="caret"></span>
                    </button>
                    <ul role="menu" class="dropdown-menu">
                        <?php foreach($parent as $val) { ?>
                        <li class="pointer" onclick="sheParentType('<?php echo ($val["full_name"]); ?>','<?php echo ($val["channel_id"]); ?>')"><?php echo ($val["full_name"]); ?></li>
                        <?php } ?>
                        <li class="divider"></li>
                        <li class="pointer" onclick="sheParentType('全部推荐人','全部推荐人')">全部推荐人</li>
                    </ul>
                </div>

                <div class="btn-group authAll authSWAll">
                    <button data-toggle="dropdown" class="btn btn-primary  dropdown-toggle btn-sm" type="button">
                        <span id="selectManager">全部商务人员</span>
                        <span class="caret"></span>
                    </button>
                    <ul role="menu" class="dropdown-menu">
                        <?php foreach($manger as $val) { ?>
                        <li class="pointer" onclick="sheManagerType('<?php echo ($val["name"]); ?>')"><?php echo ($val["name"]); ?></li>
                        <?php } ?>
                        <li class="divider"></li>
                        <li class="pointer" onclick="sheManagerType('全部商务人员')">全部商务人员</li>
                    </ul>
                </div>
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-success btn-sm pad25" type="button" id="search"
                            onclick="getData('../Channel/getChannelData')">
                        <span>查&nbsp;询</span>
                    </button>
                </div>

            </div>

        </header>

        <div class="dataAll">

            <button class="btn btn-info btn-xs authAll" type="button" onclick="addLayer('addChannel')"><i
                    class="fa fa-plus-square-o"></i> 增加渠道/经纪人
            </button>

            <div class="loading" style="display: none;width: 100%;text-align: center;">
                <img src="/qiguan/Public/Home/images/loading.gif"/>
            </div>

            <div id="get_data_area"></div>

        </div>

    </section>

    <!--蒙层-->
    <div class="addQA" id="addChannel" style="display:none ;">
        <div class="addMouule" style="left: 29%;width: 60%; position: absolute;top: 5%;">
            <section class="panel">
                <header class="panel-heading">
                    新增渠道/经纪人
                    <a class="fa fa-times close" onclick="addLayer('addChannel','close')"></a>
                </header>
                <form id="add_new_channel" >
                    <div class="panel-body">
                        <table class="layTable">
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">姓名：</label>
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control" name="contact" id="addcontact"
                                                   placeholder="请输入渠道/经纪人姓名">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">证件号：</label>
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control" name="id_number"
                                                   placeholder="请输入证件号">

                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">手机号：</label>
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control" name="phone_number"
                                                   placeholder="请输入手机号">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">账户名称：</label>
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control" name="recv_account_name"
                                                   placeholder="请输入账户名称">
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">银行：</label>
                                        <div class="col-lgJ">

                                            <input type="text" class="form-control" name="recv_bank"
                                                   placeholder="请输入银行名称">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">收款账号：</label>
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control" name="recv_account_code"
                                                   placeholder="请输入银行卡账号">
                                        </div>
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">商务：</label>
                                        <div class="col-lgJ">
                                            <select name="manager" class="form-control">
                                                <?php foreach($manger as $val) { ?>
                                                <option value="<?php echo ($val['name']); ?>"><?php echo ($val["name"]); ?></option>
                                                <?php } ?>
                                            </select>


                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">推荐人：</label>
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control" name="parent_id"
                                                   placeholder="请录入推荐人">
                                        </div>
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">渠道类型：</label>
                                        <div class="col-lgJ">

                                            <select name="channel_type" class="form-control">
                                                <?php foreach($channel as $val) { ?>
                                                <option value="<?php echo ($val['channel_type']); ?>"><?php echo ($val["channel_type"]); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">渠道名称：</label>
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control" id="addfull_name" name="full_name"
                                                   placeholder="请输入渠道名称">
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">渠道简称：</label>
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control" id="addshort_name" name="short_name"
                                                   placeholder="请输入渠道简称">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">奖励条件：</label>
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control" name="reward_detail"
                                                   placeholder="请输入奖励条件">
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">奖励方案：</label>
                                        <div class="col-lgJ">
                                            <a class="blue pointer" id="reward_plan_span" onclick="setFileClick('reward_plan')">上传文件</a>
                                            <input type="file" class="form-control hide" name="reward_plan" id="reward_plan_file"/>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label" >渠道码：</label>
                                        <div class="col-lgJ">

                                            <input type="text" class="form-control " name="ch_code" id="addch_code"  placeholder="请输入渠道码"/>

                                        </div>
                                    </div>
                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">支付方式：</label>
                                        <div class="col-lgJ">

                                            <select name="payment_mode" class="form-control">
                                                <option value="个人">个人</option>
                                                <option value="公司">公司</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">活动ID：</label>
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control " id="activity"  value="" placeholder="活动id，可为空"/>
                                        </div>
                                    </div>
                                </td>

                            </tr>

                            <tr>
                                <td >
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">渠道链接：</label>
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control" name="qr_link" id="addqr_link" placeholder="请输入渠道码链接" readonly/>

                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-lgJ">
                                        <span onclick="setCopy('addqr_link')" class="pointer blue" style="margin-right: 60px">复制链接</span>
                                        <a class="blue pointer" id="qr_code_span" onclick="setFileClick('qr_code')">请上传渠道二维码</a>
                                        <input type="file" class="form-control hide" name="qr_code" id="qr_code_file"/>

                                    </div>
                                </td>
                            </tr>



                            <tr>
                                <td>
                                    <button class="btn btn-warning btn-xs" type="button" onclick="addConversion()" id="addconversion" num_id="2">增加转化人</button>
                                    <button class="btn btn-danger  btn-xs" type="button" onclick="closeConversion()" >重设</button>
                                </td>
                            </tr>
                            <?php foreach($conversion as $conKey=>$val){ ?>
                            <tr id="con<?php echo ($conKey); ?>" class="conver">
                                <td>
                                    <div class="form-group">
                                        <label class="col-lg-2 col-sm-2 control-label">转化人</label>
                                        <div class="col-lgJ">

                                            <select name="conversion[]" class="form-control">
                                                <option value="">默认为空</option>
                                                <?php foreach($conversion as $val) { ?>
                                                <option value="<?php echo ($val['name']); ?>"><?php echo ($val["name"]); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <div class="col-lgJ">
                                            <input type="text" class="form-control conversion_bai" name="conversion_bai[]"
                                                   placeholder="请输入转化比例"
                                                   style="display: inline-block">%
                                        </div>
                                    </div>


                                </td>
                            </tr>
                            <?php } ?>
                        </table>


                        <div class="btn btn-primary" onclick="submitNewChanne()">&nbsp;确&nbsp;&nbsp;定&nbsp;</div>
                    </div>
                </form>
            </section>
        </div>
        <div class="meng"></div>
    </div>

    <script>
        $("#addch_code").bind("input propertychange",function(){
            $("#addqr_link").val("");
            var channelCode = $("#addch_code").val().trim();
            if(channelCode){
                generateH5Path();
            }

        });
    </script>

        </div>
    </div><!-- mainpanel -->


</section>


<script src="/qiguan/Public/Home/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/qiguan/Public/Home/js/jquery-ui-1.10.3.min.js"></script>
<script src="/qiguan/Public/Home/js/bootstrap.min.js"></script>
<script src="/qiguan/Public/Home/js/modernizr.min.js"></script>
<script src="/qiguan/Public/Home/js/jquery.sparkline.min.js"></script>
<script src="/qiguan/Public/Home/js/toggles.min.js"></script>
<script src="/qiguan/Public/Home/js/retina.min.js"></script>
<script src="/qiguan/Public/Home/js/jquery.cookies.js"></script>

<script src="/qiguan/Public/Home/js/morris.min.js"></script>

<script src="/qiguan/Public/Home/js/raphael-2.1.0.min.js"></script>

<script src="/qiguan/Public/Home/js/custom.js"></script>
<script src="/qiguan/Public/Home/js/jquery.form.min.js"></script>
<script src="/qiguan/Public/Home/js/bootstrap-datetimepicker.min.js"></script>
<script src="/qiguan/Public/Home/js/common.js"></script>
<link href="/qiguan/Public/Home/css/bootstrap-datetimepicker.css" rel="stylesheet"/>
<script src="/qiguan/Public/Home/js/AES.js"></script>
<script src="/qiguan/Public/Home/js/pad-zeropadding-min.js"></script>
<!--分页插件-->
<script src="/qiguan/Public/Home/js/kkpager.min.js" type="text/javascript"></script>
<link href="/qiguan/Public/Home/css/kkpager_orange.css" rel="stylesheet" type="text/css"/>

</body>
</html>