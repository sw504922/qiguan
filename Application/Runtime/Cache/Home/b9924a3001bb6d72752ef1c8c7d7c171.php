<?php if (!defined('THINK_PATH')) exit();?><div class="panel-footer">
    <div class="alert alert-info nomargin">
        <span>出金次数:<?php echo ($resultCount); ?></span>
        <?php
 if(empty($resultSum)) { $resultSum="--"; } ?>
        <span class="marginLeftTwenty">出金总金额:<?php echo ($resultSum); ?>港元</span>
    </div>
</div>


<div class='table-cont' id='table-cont'>
    <table class="dataTable" cellpadding="0" cellspacing="0">
        <thead>
        <tr class="info">
            <th>序号</th>
            <th>姓名</th>
            <th>手机号</th>
            <th>资金账号</th>
            <th>微信号</th>
            <th>出金时间</th>
            <th>出金金额(港元)</th>
            <th>剩余资产</th>
            <th>服务进度</th>
            <th width="60px">备注</th>
            <th>操作</th>
            <th>渠道类型</th>
            <th>渠道名称</th>
            <th>商务人员</th>
            <th>转化人员</th>

        </tr>
        </thead>
        <tbody>
        <?php
 if(count($result)>0){ foreach($result as $key=>$val) { $stat_time_day=$val["stat_time_day"]; $net_asset_sum+=$val["asset"]; $all_asset_sum+=$val["all_asset"]; ?>
        <!--编辑用户信息-->
        <div class="addQA" id="channel_infor_<?php echo ($key); ?>" style="display: none;">
            <div class="addMouule">
                <section class="panel">
                    <header class="panel-heading">
                        编辑用户信息
                        <a class="fa fa-times close" onclick="getChannelInfor('infor','<?php echo ($key); ?>','close')"></a>
                    </header>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="label-width">微信:</label>
                            <input type="text" id="editWechat<?php echo ($key); ?>" class="form-control " value="<?php echo ($val['wechat']); ?>"/>
                        </div>
                        <div class="form-group">
                            <label class="label-width">备注:</label>
                            <textarea id="editDesc<?php echo ($key); ?>" class="form-control"><?php echo ($val['desc']); ?></textarea>
                        </div>
                        <div class="btn btn-primary" onclick="updateProcess('<?php echo ($key); ?>','updateWeChatAndDesc')">&nbsp;确&nbsp;&nbsp;定&nbsp;</div>
                    </div>
                </section>
            </div>
            <div class="meng"></div>
        </div>
        <!--备注-->
        <div class="addQA" id="channel_reward_detail_<?php echo ($key); ?>" style="display: none;">
            <div class="addMouule">
                <section class="panel">
                    <header class="panel-heading">
                        备注
                        <a class="fa fa-times close" onclick="getChannelInfor('reward_detail','<?php echo ($key); ?>','close')"></a>
                    </header>
                    <div class="panel-body">
                        <div class="form-group">
                            <?php echo ($val["desc"]); ?>
                        </div>
                    </div>
                </section>
            </div>
            <div class="meng"></div>
        </div>
        <!--发送短信-->
        <div class="addQA" id="channel_send_<?php echo ($key); ?>" style="display: none;">
            <div class="addMouule">
                <section class="panel">
                    <header class="panel-heading">
                        发送短信
                        <a class="fa fa-times close" onclick="getChannelInfor('send','<?php echo ($key); ?>','close')"></a>
                    </header>
                    <div class="panel-body">

                        <div class="form-group">
                            <label class="label-width">发送用户:</label>
                            <input type="text" id="user<?php echo ($key); ?>" class="form-control " value="<?php echo ($val['user_name']); ?>"/>
                        </div>
                        <div class="form-group">
                            <label class="label-width">发送短信主题:</label>
                            <select name="payment_mode" id="smsTem<?php echo ($key); ?>" class="form-control"
                                    onclick="getSMSTemplet('<?php echo ($key); ?>')">
                                <?php foreach($smstemplet as $smstempletVal){ ?>
                                <option value="<?php echo ($smstempletVal['sms_content']); ?>" sms_id<?php echo ($key); ?>="<?php echo ($smstempletVal['id']); ?>">
                                    <?php echo ($smstempletVal["sms_title"]); ?>
                                </option>
                                <?php } ?>
                            </select>

                        </div>
                        <div class="form-group">
                            <label class="label-width">发送短信内容:</label>
                            <textarea id="sms_content<?php echo ($key); ?>"
                                      class="form-control"><?php echo ($smstemplet[0]["sms_content"]); ?></textarea>
                        </div>
                        <div class="btn btn-primary" onclick="sendSMS('<?php echo ($key); ?>','sendSMSInfor')">&nbsp;发送短信&nbsp;</div>
                    </div>
                </section>
            </div>
            <div class="meng"></div>
        </div>

        <div class="addQA" id="channel_water_<?php echo ($key); ?>" style="display: none;">
            <div class="addMouule">
                <section class="panel">
                    <header class="panel-heading">
                        资金流水
                        <a class="fa fa-times close" onclick="getChannelInfor('water','<?php echo ($key); ?>','close')"></a>
                    </header>
                    <div class="panel-body">
                        <div id="flowerwater<?php echo ($key); ?>"></div>
                    </div>
                </section>
            </div>
            <div class="meng"></div>
        </div>

        <input type="hidden" id="user_id<?php echo ($key); ?>" value="<?php echo ($val['user_id']); ?>"/>
        <input type="hidden" id="area_code<?php echo ($key); ?>" value="<?php echo ($val['area_country_code']); ?>"/>
        <input type="hidden" id="phone_number<?php echo ($key); ?>" value="<?php echo ($val['phone_mobile']); ?>"/>
        <tr>
            <td><?php echo ($count++); ?></td>
            <td><?php echo ($val["user_name"]); ?></td>
            <td><?php echo ($val["phone_mobile"]); ?></td>
            <td><?php echo ($val["funds_account"]); ?></td>

            <td><?php echo ($val["wechat"]); ?></td>
            <td><?php echo ($stat_time_day); ?></td>
            <td><?php echo ($val["asset"]); ?></td>
            <td><?php echo ($val["all_asset"]); ?></td>


            <td>

                <select id="process<?php echo ($key); ?>" onchange="updateProcess('<?php echo ($key); ?>','updateGlodProcess')">
                    <?php foreach($process as $processkey=>$valus) { ?>
                    <option value="<?php echo ($processkey); ?>"><?php echo ($valus); ?></option>
                    <?php } ?>
                </select>
                <input type="hidden" value="<?php echo ($val['glod_process']); ?>" id="process_hide_<?php echo ($key); ?>" class="processHide"/>

            </td>
            <td>
                <span class="desc"><?php echo ($val["desc"]); ?></span>
                <span class="material-icons pointer hide reward_detail_hide"
                      onclick="getChannelInfor('reward_detail','<?php echo ($key); ?>')">...</span>
            </td>
            <td>
                <a onclick="getChannelInfor('infor','<?php echo ($key); ?>')" class="blue pointer">编辑</a>
                <a onclick="getChannelInfor('water','<?php echo ($key); ?>')" class="blue pointer">资金流水</a>
                <a onclick="getChannelInfor('send','<?php echo ($key); ?>')" class="blue pointer">发短信</a>
            </td>
            <td><?php echo ($val["channel_type"]); ?></td>
            <td><?php echo ($val["channel_name"]); ?></td>
            <td><?php echo ($val["manager"]); ?></td>
            <td><?php echo ($val["conversion_name"]); ?></td>


        </tr>


        <?php
 } ?>
        <tr>
            <td>汇总</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?php echo ($net_asset_sum); ?></td>
            <td><?php echo ($all_asset_sum); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>


        </tr>
        <?php
 }else{ ?>
        <tr>
            <td colspan="18" align="center">暂无数据</td>
        </tr>
        <?php } $pageCount=ceil($resultCount/$viewCount); ?>
        </tbody>
    </table>
</div>

<input value="<?php echo ($new_page); ?>" id="page" type="hidden"/>
<div id="kkpager" style="width:70%"></div>

<script type="text/javascript">
    $(document).ready(function () {
        //设置显示数据结构名称与数据
        var dataCount = <?php echo ($resultCount); ?>;
        $(".newsNum").html(dataCount);
        var pageCount = <?php echo ($pageCount); ?>;
        if (dataCount > 0) {
            limitPage(pageCount, dataCount, "../Trade/getgetGlodData");
        }

        var user_auth = $("#user_auth").val();
        if (user_auth == "18") {
            $(".authAll").hide();
        }
        $(".desc").each(function () {
            var desc = $(this).text();
            if (desc.length > 4) {
                $(this).text(desc.substr(0, 5));
                $(this).next().removeClass("hide")
            }

        })

        $(".processHide").each(function () {
            var key = $(this).attr("id").replace("process_hide_", "");
            var values = $(this).val();
            if (values == null || values == "") {
                values = 0;
            }
            $("#process" + key).val(values);

        })
    })
</script>