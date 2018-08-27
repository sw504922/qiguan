<?php if (!defined('THINK_PATH')) exit();?><table class="dataTable" cellpadding="0" cellspacing="0">
    <tr class="info">
        <th>序号</th>
        <th>姓名</th>
        <th>手机号</th>
        <th>资金账号</th>
        <th>微信号</th>
        <th>注册时间</th>
        <th>开户时间</th>
        <th>首次入金时间</th>
        <th>服务进度</th>
        <th>渠道类型</th>
        <th>渠道名称</th>
        <th>商务人员</th>
        <th>转化人员</th>
        <th>当前总资产(港元)</th>
        <th>首次入金金额(港元)</th>
        <th>首次转仓金额</th>
        <th>备注</th>
        <th>操作</th>
    </tr>

    <?php
 if(count($result)>0){ foreach($result as $key=>$val) { $golde_time=$val["golde_time"]; if($golde_time==""){ $golde_time="--"; } $register_time=$val["register_time"]; if($register_time==""){ $register_time="--"; } $stat_time=$val["stat_time"]; if($stat_time==""){ $stat_time="--"; } $all_asset_sum+=$val["all_asset"]; $first_asset_sum+=$val["first_asset"]; $first_transfer_sum+=$val["first_transfer"]; ?>
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
                        <input type="text" id="editWechat<?php echo ($key); ?>" class="form-control " value="<?php echo ($val['wechat']); ?>" />
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



    <div class="addQA" id="channel_water_<?php echo ($key); ?>" style="display: none;">
        <div class="addMouule">
            <section class="panel">
                <header class="panel-heading">
                    资金流水
                    <a class="fa fa-times close" onclick="getChannelInfor('infor','<?php echo ($key); ?>','close')"></a>
                </header>
                <div class="panel-body">
                    <div id="floewwater"></div>
                </div>
            </section>
        </div>
        <div class="meng"></div>
    </div>
    <tr>
        <td><?php echo ($count++); ?></td>
        <td><?php echo ($val["name"]); ?></td>
        <td><?php echo ($val["phone_number"]); ?></td>
        <td id="fund_account<?php echo ($key); ?>"><?php echo ($val["funds_account"]); ?></td>

        <td><?php echo ($val["wechat"]); ?></td>
        <td><?php echo ($register_time); ?></td>
        <td><?php echo ($stat_time); ?></td>
        <td><?php echo ($golde_time); ?></td>


        <td>

            <select id="process<?php echo ($key); ?>" onchange="updateProcess('<?php echo ($key); ?>','updateProcess')">
                <?php foreach($process as $processkey=>$valus) { ?>
                <option value="<?php echo ($processkey); ?>"><?php echo ($valus); ?></option>
                <?php } ?>
            </select>
            <input type="hidden" value="<?php echo ($val['process']); ?>" id="process_hide_<?php echo ($key); ?>" class="processHide"/>

        </td>

        <td><?php echo ($val["channel_type"]); ?></td>
        <td><?php echo ($val["channel_name"]); ?></td>
        <td><?php echo ($val["manager"]); ?></td>
        <td><?php echo ($val["conversion_name"]); ?></td>
        <td><?php echo ($val["all_asset"]); ?></td>
        <td><?php echo ($val["first_asset"]); ?></td>
        <td><?php echo ($val["first_transfer"]); ?></td>
        <td>
            <div class="whiteSpace"><?php echo ($val["desc"]); ?></div>
        </td>
        <td>
            <a  onclick="getChannelInfor('infor','<?php echo ($key); ?>')" class="blue pointer">编辑</a>
            <a onclick="getChannelInfor('infor','<?php echo ($key); ?>')" class="blue pointer">资金流水</a>
            <a>发短信</a>
        </td>
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
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php echo ($all_asset_sum); ?></td>
        <td><?php echo ($first_asset_sum); ?></td>
        <td><?php echo ($first_transfer_sum); ?></td>
        <td></td>
        <td></td>

    </tr>
    <?php
 }else{ ?>
    <tr>
        <td colspan="18" align="center">暂无数据</td>
    </tr>
    <?php } $pageCount=ceil($resultCount/50); ?>
</table>


<input value="<?php echo ($new_page); ?>" id="page" type="hidden"/>
<div id="kkpager" style="width:70%"></div>

<script type="text/javascript">
    $(document).ready(function () {
        //设置显示数据结构名称与数据
        var dataCount = <?php echo ($resultCount); ?>;
        $(".newsNum").html(dataCount);
        var pageCount = <?php echo ($pageCount); ?>;
        if (dataCount > 0) {
            limitPage(pageCount, dataCount, "../Trade/getTradeData");
        }


        $(".processHide").each(function () {
            var key = $(this).attr("id").replace("process_hide_", "");
            var values = $(this).val();
            if (values == null || values=="") {
                values = 0;
            }
            $("#process" + key).val(values);

        })
    })
</script>