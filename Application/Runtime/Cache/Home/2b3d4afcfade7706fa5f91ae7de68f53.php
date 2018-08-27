<?php if (!defined('THINK_PATH')) exit();?><table class="dataTable">
    <tr>
        <th>时间</th>
        <th>出/入金(金额)</th>

    </tr>
<?php
 foreach($floweResult as $val){ ?>
    <tr>
        <td><?php echo ($val["info_date"]); ?></td>
        <td><?php echo ($val["asset"]); ?></td>
    </tr>
<?php } if(count($floweResult)==0 || empty($floweResult)) { ?>
    <tr>
        <td colspan="2" align="center">暂无数据</td>
    </tr>
<?php } ?>
</table>