<?php if (!defined('THINK_PATH')) exit();?><table class="dataTable" cellpadding="0" cellspacing="0">
    <tr class="info">
        <th>序号</th>
        <th>渠道类型</th>
        <th>渠道名称</th>
        <th>手机号</th>
        <th>奖励条件</th>
        <th>奖励方案</th>
        <th>创建时间</th>
        <th>商务人</th>
        <th>推荐人</th>
        <th>渠道简码</th>
        <th>渠道码</th>
        <th class="authAll">操作</th>
    </tr>

    <?php
 if(count($result)>0){ foreach($result as $key=>$val) { $contact=$val['contact']; if($contact==""){ $contact="--"; } $id_number=$val['id_number']; if($id_number==""){ $id_number="--"; } $payment_mode=$val['payment_mode']; if($payment_mode==""){ $payment_mode="--"; } $recv_bank=$val['recv_bank']; $recv_account_name=$val['recv_account_name']; $recv_account_code=$val['recv_account_code']; if($recv_bank==""){ $recv_bank="--"; } if($recv_account_name==""){ $recv_account_name="--"; } if($recv_account_code==""){ $recv_account_code="--"; } $qr_link=$val['qr_link']; if($qr_link==""){ $qr_link="--"; } $urlQ="http://117.121.21.173:8086/miningaccount_manager/file"; $qr_code=$val['qr_code']; if($qr_code==""){ $qr_code="--"; }else{ $qr_code=$urlQ.$qr_code; } if($val['reward_plan']==""){ $reward_plan="--"; }else{ $reward_plan=$urlQ.$val['reward_plan']; } ?>
    <!--个人基本资料-->
    <div class="addQA" id="channel_infor_<?php echo ($key); ?>" style="display: none;">
        <div class="addMouule">
            <section class="panel">
                <header class="panel-heading">
                    个人基本资料
                    <a class="fa fa-times close" onclick="getChannelInfor('infor','<?php echo ($key); ?>','close')"></a>
                </header>
                <div class="panel-body">

                    <div class="form-group">
                        <label class="label-width">姓名:</label>
                        <input type="email" class="form-control inputDiv" value="<?php echo ($contact); ?>" readonly/>
                    </div>
                    <div class="form-group">
                        <label class="label-width">证件号:</label>
                        <input type="email" class="form-control  inputDiv" value="<?php echo ($id_number); ?>" readonly/>
                    </div>
                    <div class="form-group">
                        <label class="label-width">支付:</label>
                        <input type="email" class="form-control inputDiv" value="<?php echo ($payment_mode); ?>" readonly/>
                    </div>
                    <div class="form-group">
                        <label class="label-width">银行:</label>
                        <input type="email" class="form-control inputDiv" value="<?php echo ($recv_bank); ?>" readonly/>
                    </div>
                    <div class="form-group">
                        <label class="label-width">账户名称:</label>
                        <input type="email" class="form-control inputDiv" value="<?php echo ($recv_account_name); ?>" readonly/>
                    </div>
                    <div class="form-group">
                        <label class="label-width">收款账号:</label>
                        <input type="email" class="form-control inputDiv" value="<?php echo ($recv_account_code); ?>" readonly/>
                    </div>

                </div>
            </section>
        </div>
        <div class="meng"></div>
    </div>

    <!--渠道码-->
    <div class="addQA" id="channel_code_<?php echo ($key); ?>" style="display: none;">
        <div class="addMouule">
            <section class="panel">
                <header class="panel-heading">
                    渠道码
                    <a class="fa fa-times close" onclick="getChannelInfor('code','<?php echo ($key); ?>','close')"></a>
                </header>
                <div class="panel-body">

                    <div class="form-group">
                        <label class="label-width">渠道链接:</label>
                        <input type="email" id="copy<?php echo ($key); ?>" class="form-control inputDiv" value="<?php echo ($qr_link); ?>" readonly/>
                        <label class="label-width blue pointer" onclick="setCopy('copy<?php echo ($key); ?>')">复制链接:</label>
                    </div>

                    <div class="form-group">
                        <label class="label-width">二维码:</label>
                        <img src="<?php echo ($qr_code); ?>"/>
                    </div>

                </div>
            </section>
        </div>
        <div class="meng"></div>
    </div>

    <!--奖励方案-->
    <div class="addQA" id="channel_plan_<?php echo ($key); ?>" style="display: none;">
        <div class="addMouule">
            <section class="panel">
                <header class="panel-heading">
                    奖励方案
                    <a class="fa fa-times close" onclick="getChannelInfor('plan','<?php echo ($key); ?>','close')"></a>
                </header>
                <div class="panel-body">
                    <div class="form-group">
                        <img src="<?php echo ($reward_plan); ?>" style="width: 100%;"/>
                    </div>
                </div>
            </section>
        </div>
        <div class="meng"></div>
    </div>


    <tr>
        <td><?php echo ($count++); ?></td>
        <td><?php echo ($val["channel_type"]); ?></td>
        <td>
            <div class="whiteSpace" style="min-width: 200px"><?php echo ($val["channel_name"]); ?></div>
        </td>
        <td><?php echo ($val["phone_number"]); ?><span class="material-icons pointer"
                                        onclick="getChannelInfor('infor','<?php echo ($key); ?>')">...</span>
        </td>
        <td>
            <div class="whiteSpace"><?php echo ($val["reward_detail"]); ?></div>
        </td>
        <?php if(empty($val["reward_plan"])){?>
        <td>--</td>
        <?php }else{ ?>
        <td><a class="blue pointer" onclick="getChannelInfor('plan','<?php echo ($key); ?>')">查看</a></td>
        <?php } ?>
        <td><?php echo ($val["create_time"]); ?></td>
        <td><?php echo ($val["manager"]); ?></td>
        <td><?php echo ($val["parent_name"]); ?></td>
        <td><?php echo ($val["ch_code"]); ?></td>
        <td><a onclick="getChannelInfor('code','<?php echo ($key); ?>')" class="blue pointer">查看</a></td>
        <td class="authAll"><a class="blue pointer" onclick="getChannelInfor('update','<?php echo ($key); ?>')">编辑</a></td>
    </tr>


    <?php
 } }else{ ?>
    <tr>
        <td colspan="12" align="center">暂无数据</td>
    </tr>
    <?php } $pageCount=ceil($resultCount/50); ?>
</table>


<?php
 foreach($result as $key=>$val) { $conversionNameArr=explode(",",$val["conversion_name"]); $conversionArr=explode(",",$val["conversion"]); ?>

<!--修改渠道/经纪人-->
<div class="addQA" id="channel_update_<?php echo ($key); ?>" style="display:none ;">
    <div class="addMouule" style="left: 29%;width: 60%;min-width: 800px">
        <section class="panel">
            <header class="panel-heading">
                修改渠道/经纪人
                <a class="fa fa-times close" onclick="getChannelInfor('update','<?php echo ($key); ?>','close')"></a>
            </header>
            <form id="update_new_channel<?php echo ($key); ?>">
                <input type="hidden" value="<?php echo ($val['ch_code']); ?>" name="ch_code"/>
                <div class="panel-body">
                    <table class="layTable">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">姓名：</label>
                                    <div class="col-lgJ">
                                        <input type="text" class="form-control" name="contact" id="update_ontact<?php echo ($key); ?>"
                                               value="<?php echo ($val['contact']); ?>">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">证件号：</label>
                                    <div class="col-lgJ">
                                        <input type="text" class="form-control" name="id_number"
                                               value="<?php echo ($val['id_number']); ?>">

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
                                               value="<?php echo ($val['phone_number']); ?>">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">账户名称：</label>
                                    <div class="col-lgJ">
                                        <input type="text" class="form-control" name="recv_account_name"
                                               value="<?php echo ($val['recv_account_name']); ?>">
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
                                               value="<?php echo ($val['recv_bank']); ?>">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">收款账号：</label>
                                    <div class="col-lgJ">
                                        <input type="text" class="form-control" name="recv_account_code"
                                               value="<?php echo ($val['recv_account_code']); ?>">
                                    </div>
                                </div>
                            </td>
                        </tr>


                        <tr>
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">商务：</label>
                                    <div class="col-lgJ">
                                        <select name="manager" class="form-control" id="manager_select<?php echo ($key); ?>">
                                            <?php foreach($manger as $vald) { ?>
                                            <option value="<?php echo ($vald['name']); ?>"><?php echo ($vald["name"]); ?></option>
                                            <?php } ?>
                                        </select>

                                        <input type="hidden" class="form-control" id="manager<?php echo ($key); ?>"
                                               value="<?php echo ($val['manager']); ?>">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">推荐人：</label>
                                    <div class="col-lgJ">
                                        <input type="text" class="form-control" name="parent_id"
                                               value="<?php echo ($val['parent_name']); ?>">
                                    </div>
                                </div>
                            </td>
                        </tr>


                        <tr>
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">渠道类型：</label>
                                    <div class="col-lgJ">

                                        <select name="channel_type" class="form-control" id="channel_type_select<?php echo ($key); ?>">
                                            <?php foreach($channel as $vals) { ?>
                                            <option value="<?php echo ($vals['channel_type']); ?>"><?php echo ($vals["channel_type"]); ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" class="form-control" id="channel_type<?php echo ($key); ?>"
                                               value="<?php echo ($val['channel_type']); ?>">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">渠道名称：</label>
                                    <div class="col-lgJ">
                                        <input type="text" class="form-control" name="full_name"
                                               id="update_full_name<?php echo ($key); ?>"
                                               value="<?php echo ($val['channel_name']); ?>">
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">渠道简称：</label>
                                    <div class="col-lgJ">
                                        <input type="text" class="form-control" name="short_name"
                                               id="update_short_name<?php echo ($key); ?>"
                                               value="<?php echo ($val['short_name']); ?>">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">奖励条件：</label>
                                    <div class="col-lgJ">
                                        <input type="text" class="form-control" name="reward_detail"
                                               value="<?php echo ($val['reward_detail']); ?>">
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">奖励方案：</label>


                                    <div class="col-lgJ">

                                        <a class="blue pointer" id="reward_plan_span" onclick="setFileClick('reward_plan<?php echo ($key); ?>')">上传文件</a>
                                        <input type="file" class="form-control hide" name="reward_plan" id="reward_plan<?php echo ($key); ?>_file"/>
                                    </div>
                                </div>
                            </td>
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
                        </tr>

                        <tr>
                            <td>
                                <button class="btn btn-warning btn-xs" type="button" onclick="addConversion('<?php echo ($key); ?>')"
                                        id="addconversion<?php echo ($key); ?>" num_id="2">增加转化人
                                </button>
                                <button class="btn btn-danger  btn-xs" type="button"
                                        onclick="closeConversion('<?php echo ($key); ?>')">重设
                                </button>
                            </td>
                        </tr>
                        <?php foreach($conversion as $conKey=>$val){ ?>
                        <tr id="con<?php echo ($conKey); echo ($key); ?>" class="conver<?php echo ($key); ?>">
                            <td>
                                <div class="form-group">
                                    <label class="col-lg-2 col-sm-2 control-label">转化人</label>
                                    <div class="col-lgJ">

                                        <select name="conversion[]" class="form-control" id="coverName_select<?php echo ($conKey); echo ($key); ?>">
                                            <option></option>
                                            <?php foreach($conversion as $val) { ?>
                                            <option value="<?php echo ($val['name']); ?>"><?php echo ($val["name"]); ?></option>
                                            <?php } ?>
                                        </select>

                                        <input type="hidden" value="<?php echo ($conversionNameArr[$conKey]); ?>" id="conversionNameArr<?php echo ($conKey); echo ($key); ?>"/>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <div class="col-lgJ">
                                        <input type="text" class="form-control conversion_bai<?php echo ($key); ?>" name="conversion_bai[]" value="<?php echo ($conversionArr[$conKey]); ?>" style="display: inline-block">%
                                    </div>
                                </div>


                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                    <div class="btn btn-primary" onclick="submitNewChanne('<?php echo ($key); ?>')">&nbsp;确&nbsp;&nbsp;定&nbsp;</div>
                </div>
            </form>
        </section>
    </div>
    <div class="meng"></div>
</div>
<?php }?>
<input value="<?php echo ($new_page); ?>" id="page" type="hidden"/>
<div id="kkpager" style="width:70%"></div>

<script type="text/javascript">
    $(document).ready(function () {
        //设置显示数据结构名称与数据
        var dataCount = <?php echo ($resultCount); ?>;
        $(".newsNum").html(dataCount);
        var pageCount = <?php echo ($pageCount); ?>;
        if (dataCount > 0) {
            limitPage(pageCount, dataCount, "../Channel/getChannelData");
        }

        var user_auth=$("#user_auth").val();
        if (user_auth=="转化人员"){
            $(".authAll").hide();
        }
    })
</script>