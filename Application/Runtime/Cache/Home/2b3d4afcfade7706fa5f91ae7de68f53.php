<?php if (!defined('THINK_PATH')) exit();?><table class="dataTable" width="100%">
    <tr>
        <th>时间</th>
        <th>出/入金</th>
        <th>金额</th>

    </tr>
    <?php
 foreach($floweResult as $val){ if($val["asset"]>0){ $nameS="入金"; }else{ $nameS="出金"; } ?>
    <tr>
        <td><?php echo ($val["info_date"]); ?></td>
        <td><?php echo ($nameS); ?></td>
        <td><?php echo ($val["asset"]); ?></td>
    </tr>
    <?php } if(count($floweResult)==0 || empty($floweResult)) { ?>
    <tr>
        <td colspan="3" align="center">暂无数据</td>
    </tr>
    <?php } ?>
</table>