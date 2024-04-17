
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if (isset($_POST['hid_report_month'])) {
	$_SESSION['report_month'] = $_POST['hid_report_month'];
} else {
	//$_SESSION['report_month'] = date("mY");
}

if (!isset($_SESSION['report_month'])) {
	$date = trim(date('Ym')).'';
	$_SESSION['report_month'] = $date;
}

if ($_REQUEST['do'] == 'unerr') {
	$id=$_REQUEST['id'];
	echo '<!--';
	$result = $mysqlIns->update_unerr_trn_trans($id);
	echo '-->';
}

$width = 98;
?>
<script src="lib/js/Highcharts-4.0.4/js/highcharts.js"></script>
<script src="lib/js/Highcharts-4.0.4/js/highcharts-3d.js"></script>
<script src="lib/js/Highcharts-4.0.4/js/modules/exporting.js"></script>


<style type="text/css">
td {
    font-size: 9px;
}	
</style>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<thead>
<tr height="4">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>
</thead>
</table>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0">
<thead>

<tr>
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/typeicon2.png" height=25></td>
		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=ranklist">Sản phẩm</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/usericon2.png" height=25></td>
		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=rankuser">Nhân viên</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		
		
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/user.png" height=20></td>
		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=rankcustf">Khách hàng</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		
		<!--<td align="center" valign="middle" nowrap="nowrap"><img src="images/customersicon.png" height=20></td>

		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=custlist">DS Khách hàng</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		-->
		<td align="center" valign="middle" nowrap="nowrap"><img src="images/92586_excel_512x512.png" height=20></td>

		<td align="center" valign="middle" nowrap="nowrap"><font size="2"><b>&nbsp;<a onclick="makeBlockUI();" href="?mode=custlist">Export &#273;&#417;n h&#224;ng</a>&nbsp;&nbsp;&nbsp;</b></font> </td>
		
		<td align="center" valign="middle" nowrap="nowrap" width="80%"></td>
		<td align="center" valign="middle" nowrap="nowrap" width="10%"><font size="2"><b>Xem tháng </b></font>
<select onchange="changeMonth();" name="report_month" id="report_month" style="border:1px solid #DADADA;">
<?php 	//echo "<!--";
		$view_report_timeline=$mysqlIns->view_report_month();
		//echo "-->";
		for($i=0;$i<count($view_report_timeline);$i++) {
				if ($_SESSION['report_month'] == $view_report_timeline[$i]['timeline']) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
				
				echo '<option '.$selected.' value="'.$view_report_timeline[$i]['timeline'].'">'.$view_report_timeline[$i]['monthreport'].'</option>';
		}
?>
</select>
		</td>
		
	</tr>
	<tr  height="4">
	
		<td width="10%" colspan="4" align="center" height="4"></td>
		
	</tr>
</thead>
</table>
<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0"  >

	
<tr height="10">
	
		<td width="1%" colspan="4" align="left" height="2" style="padding-left:5px;padding-right:5px;padding-bottom:5px;">
		<img src="images/chart01.png" height=25>
		</td>
		
		<td width="100%" colspan="4" align="left" height="2">
		<font size="2"><b>Thống kê doanh số sản phẩm theo đơn hàng đã đặt</b></font>
		</td>
		
	</tr>
</table>

<?php
echo '<!--';

//if ($_REQUEST['mode'] == 'ranklist') {
//	$view_report_product_by_lastmonth=$mysqlIns->view_report_product_by_lastmonth(0);
//} elseif ($_REQUEST['mode'] == 'ranklistf') { 
	$view_report_product_by_lastmonth=$mysqlIns->view_report_product_by_lastmonth(1);
//}
echo '-->';
?>
<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0"  >
<thead>

	<tr  height="4">
		<td width="10%" colspan="4" align="center" height="4" valign="top">
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>

<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding-left:6px;padding-right:6px;" width="1%"><b>STT</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="1%"><b>Sản phẩm</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="1%"><b>S&#7889; l&#432;&#7907;ng</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="2%"><b>Doanh số</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="2%"><b>Thiệt hại</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="2%"><b>Speed(h)</b></td>
</tr>
</thead>
<tbody id="body_other">
<?php
$total = 0;
$quantity = 0;
$issue = 0;
$speeds = 0;
$total_item = 0;
for($i=0;$i<count($view_report_product_by_lastmonth);$i++)
{
$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
$stt = $i+ 1;
$total = $total + str_replace(',','',$view_report_product_by_lastmonth[$i]['total_amount_f']);
$quantity = $quantity + str_replace(',','',$view_report_product_by_lastmonth[$i]['total_quantity_f']);
$issue = $issue + str_replace(',','',$view_report_product_by_lastmonth[$i]['total_issue_f']);
$title_speed = "Thời gian thiết kế trung bình = Ngày bắt đầu thiết kế - ngày nhập đơn hàng (Đơn vị: giờ)";

$speeds = $speeds + str_replace(',','',$view_report_product_by_lastmonth[$i]['speed_f']);
if ($view_report_product_by_lastmonth[$i]['speed_f'] > 0) {
	$total_item = $total_item + 1;
}
echo '
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'">
		<td nowrap="nowrap" style="padding-left:6px;padding-right:6px;" >'.$stt.'</td>
		<td nowrap="nowrap" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_report_product_by_lastmonth[$i]['prd_code'].'</td>
		<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_report_product_by_lastmonth[$i]['total_quantity_f'].'</td>
		<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_report_product_by_lastmonth[$i]['total_amount_f'].'</td>
		<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_report_product_by_lastmonth[$i]['total_issue_f'].'</td>
		<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" title="'.$title_speed.'">'.$view_report_product_by_lastmonth[$i]['speed_f'].'<img src="images/market-faster1.png" height="13"></td>
</tr>';

}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr bgcolor="#fafae0" height="25">
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;padding-right:15px;" width="5%" colspan=2>TỔNG SỐ (<?php echo $_SESSION['report_month']; ?>)</td>
	<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;"><b><?php echo number_format($quantity, 0, '.', ','); ?></b></td>
	<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;"><b><?php echo number_format($total, 0, '.', ','); ?></b></td>
	<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;"><b><?php echo number_format($issue, 0, '.', ','); ?></b></td>
	<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8;border-left: 1px solid #c2c2c2; border-collapse:none;" title="<?=$title_speed?>"><b><?php echo number_format($speeds/$total_item, 0, '.', ','); ?><img src="images/market-faster1.png" height="13"></b></td>
</tr>
</tbody>
</table>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<thead>
<tr height="7">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>
<tr height="4">
	
		<td width="10%" colspan="4" align="center" height="2">
		
		<div id="containerpie" style="height: 250px"></div>
	</body>
		</td>
		
	</tr>
</thead>
</table>
</td>
<td width="20" valign="top">&nbsp;&nbsp;&nbsp;</td>		
		
		<td width="90%" colspan="4" valign="top" align="center" height="4">
		<div id="container" style="min-width: 350px; height: 400px; margin: 0 auto"></div>
		
		</td>
		
	</tr>
</thead>
</table>

<?php
echo '<!--';

//if ($_REQUEST['mode'] == 'ranklist') {
//	$view_report_product_by_lastmonth=$mysqlIns->view_report_product_by_lastmonth(0);
//} elseif ($_REQUEST['mode'] == 'ranklistf') { 
	$view_report_product_by_lastmonth=$mysqlIns->view_report_product_by_lastmonth(1);
//}
echo '-->';
?>
<table width="100%" cellspacing="0" cellpadding="0"  >

	
<tr height="10">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>
</table>



<form name="hid_post" id="hid_post" method="POST">
	<input type="hidden" id="hid_report_month" name="hid_report_month" value="">
</form>

<?php
echo '<!--';
//if ($_REQUEST['mode'] == 'ranklist') {
//	$view_orderstatus_lastmonth=$mysqlIns->view_orderstatus_lastmonth(0);
//} elseif ($_REQUEST['mode'] == 'ranklistf') { 
	$view_orderstatus_lastmonth=$mysqlIns->view_orderstatus_lastmonth(1);
//}


$complete = $view_orderstatus_lastmonth[0]['total_amount_complete_f'];
$remain_all = $view_orderstatus_lastmonth[0]['payment_remain_amount_all_f'];
$remain_notyet = $view_orderstatus_lastmonth[0]['payment_remain_amount_notyet_f'];
$issue_amount = $view_orderstatus_lastmonth[0]['issue_amount_f'];

echo '-->';
$total = $complete + $remain_all + $remain_notyet + $issue_amount;
if ($total >0)
{
	$complete_per = round(str_replace(',','',$complete) * 100 / $total);
	$remain_all_per = round(str_replace(',','',$remain_all) * 100 / $total);
	$remain_notyet_per = round(str_replace(',','',$remain_notyet) * 100 / $total);
	$issue_amount_per = round(str_replace(',','',$issue_amount) * 100 / $total);
?>
		<script type="text/javascript">
$(function () {
    $('#containerpie').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'ORDER STATUS (<?php echo $_SESSION['report_month']; ?>)'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Tỷ lệ',
            data: [
                ['Chưa xong<br>(<?php echo $remain_notyet; ?>k)',       <?php echo $remain_notyet_per; ?>],
				
				
				['Thiệt hại<br>(<?php echo $issue_amount; ?>k)',       <?php echo $issue_amount_per; ?>],
				{
					name: 'Đã thu tiền<br>(<?php echo $complete; ?>k)',   
					y: <?php echo $complete_per; ?>,
					sliced: true,
                    selected: true
				},
				['Khách nợ<br>(<?php echo $remain_all; ?>k)', <?php echo $remain_all_per; ?>]
				
            ]
        }]
    });
});
		</script>
<?php
}
?>
<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column',
			height: 450
        },
        title: {
            text: 'RESULT BY CATEGORY'
        },
        xAxis: {
            categories: [<?php 	//echo "<!--";
					$view_report_timeline=$mysqlIns->view_report_timeline();
					//echo "-->";
					for($i=0;$i<count($view_report_timeline);$i++) {
						if ($i < count($view_report_timeline)) {
							echo '\''.$view_report_timeline[$i]['monthreport'].'\',';
						} else {
							echo '\''.$view_report_timeline[$i]['monthreport'].'\'';
						}
					}
			?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Amount x1000 (VND)'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: 0,
            verticalAlign: 'top',
            y: -4,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black, 0 0 3px black'
                    }
                }
            }
        },
        series: [<?php 	//echo "<!--";
				$view_report_product=$mysqlIns->view_report_product();
				//echo "-->";
				for($i=0;$i<count($view_report_product);$i++) {
					if ($i < count($view_report_product)) {
						echo '	{
									name: \''.$view_report_product[$i]['prd_code'].'\',
									data: ['; //echo "<!--";
						//if ($_REQUEST['mode'] == 'ranklist') {
						//	$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],0);
						//} elseif ($_REQUEST['mode'] == 'ranklistf') { 
							$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],1);
						//}
						
						//echo "-->";
						for($j=0;$j<count($view_report_product_by_timeline);$j++) {
							if ($j < count($view_report_product_by_timeline)) {
								echo round($view_report_product_by_timeline[$j]['total_amount']/1000).',';
							} else {
								echo round($view_report_product_by_timeline[$j]['total_amount']/1000);
							}
						} echo ']
								},';
					} else {
						
						echo '	{
									name: \''.$view_report_product[$i]['prd_code'].'\',
									data: [
									'; //echo "<!--";
						//if ($_REQUEST['mode'] == 'ranklist') {
						//	$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],0);
						//} elseif ($_REQUEST['mode'] == 'ranklistf') {
							$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],1);
						//}
						
						//echo "-->";
						for($j=0;$j<count($view_report_product_by_timeline);$j++) {
							if ($j < count($view_report_product_by_timeline)) {
								echo round($view_report_product_by_timeline[$j]['total_amount']/1000).',';
							} else {
								echo round($view_report_product_by_timeline[$j]['total_amount']/1000);
							}
						}echo ']
								}';
					}
				}
		?>
		]
    });
});

		</script>
	

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0"  >

	
<tr height="10">
	
		<td width="1%" colspan="4" align="left" height="2" style="padding-left:5px;padding-right:5px;padding-bottom:5px;">
		<img src="images/chart01.png" height=25>
		</td>
		
		<td width="100%" colspan="4" align="left" height="2">
		<font size="2"><b>Thống kê doanh số sản phẩm theo đơn hàng đã hoàn thành</b></font>
		</td>
		
	</tr>
</table>
	
<?php
echo '<!--';

//if ($_REQUEST['mode'] == 'ranklist') {
	$view_report_product_by_lastmonth=$mysqlIns->view_report_product_by_lastmonth(0);
//} elseif ($_REQUEST['mode'] == 'ranklistf') { 
//	$view_report_product_by_lastmonth=$mysqlIns->view_report_product_by_lastmonth(1);
//}
echo '-->';
?>
<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0"  >
<thead>

	<tr  height="4">
		<td width="30%" colspan="4" align="center" height="4" valign="top">
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>

<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding-left:6px;padding-right:6px;" width="5%"><b>STT</b></td>
	<td nowrap="nowrap" align="left" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="40%"><b>Sản phẩm</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="20%"><b>S&#7889; l&#432;&#7907;ng</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="20%"><b>Doanh số</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="20%"><b>Thiệt hại</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="20%"><b>Speed(h)</b></td>
</tr>
</thead>
<tbody id="body_other">
<?php
$total = 0;
$quantity = 0;
$issue = 0;
$speeds = 0;
$total_item = 0;
for($i=0;$i<count($view_report_product_by_lastmonth);$i++)
{
$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
$stt = $i+ 1;
$total = $total + str_replace(',','',$view_report_product_by_lastmonth[$i]['total_amount_f']);
$quantity = $quantity + str_replace(',','',$view_report_product_by_lastmonth[$i]['total_quantity_f']);
$issue = $issue + str_replace(',','',$view_report_product_by_lastmonth[$i]['total_issue_f']);

$title_speed = "Thời gian thiết kế trung bình = Ngày bắt đầu thiết kế - ngày nhập đơn hàng (Đơn vị: giờ)";

$speeds = $speeds + str_replace(',','',$view_report_product_by_lastmonth[$i]['speed_f']);
if ($view_report_product_by_lastmonth[$i]['speed_f'] > 0) {
	$total_item = $total_item + 1;
}

echo '
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'">
		<td nowrap="nowrap" style="padding-left:6px;padding-right:6px;" >'.$stt.'</td>
		<td nowrap="nowrap" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_report_product_by_lastmonth[$i]['prd_code'].'</td>
		<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_report_product_by_lastmonth[$i]['total_quantity_f'].'</td>
		<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_report_product_by_lastmonth[$i]['total_amount_f'].'</td>
		<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_report_product_by_lastmonth[$i]['total_issue_f'].'</td>
		<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" title="'.$title_speed.'">'.$view_report_product_by_lastmonth[$i]['speed_f'].'<img src="images/market-faster1.png" height="13"></td>
</tr>';

}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr bgcolor="#fafae0" height="25">
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;padding-right:15px;" width="5%" colspan=2>TỔNG SỐ (<?php echo $_SESSION['report_month']; ?>)</td>
	<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;"><b><?php echo number_format($quantity, 0, '.', ','); ?></b></td>
	<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;"><b><?php echo number_format($total, 0, '.', ','); ?></b></td>
	<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;"><b><?php echo number_format($issue, 0, '.', ','); ?></b></td>
	<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8;border-left: 1px solid #c2c2c2; border-collapse:none;" title="<?=$title_speed?>"><b><?php echo number_format($speeds/$total_item, 0, '.', ','); ?><img src="images/market-faster1.png" height="13"></b></td>
</tr>
</tbody>
</table>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<thead>
<tr height="7">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>
<tr height="4">
	
		<td width="10%" colspan="4" align="center" height="2">
		
		<div id="containerpie1" style="height: 250px"></div>
	</body>
		</td>
		
	</tr>
</thead>
</table>
</td>
<td width="20" valign="top">&nbsp;&nbsp;&nbsp;</td>		
		
		<td width="70%" colspan="4" valign="top" align="center" height="4">
		<div id="container1" style="min-width: 350px; height: 400px; margin: 0 auto"></div>
		
		</td>
		
	</tr>
</thead>
</table>

<?php
echo '<!--';

//if ($_REQUEST['mode'] == 'ranklist') {
	$view_report_product_by_lastmonth=$mysqlIns->view_report_product_by_lastmonth(0);
//} elseif ($_REQUEST['mode'] == 'ranklistf') { 
//	$view_report_product_by_lastmonth=$mysqlIns->view_report_product_by_lastmonth(1);
//}
echo '-->';
?>
<table width="100%" cellspacing="0" cellpadding="0"  >

	
<tr height="10">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>
</table>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0"  >

	
<tr height="10">
	
		<td width="1%" colspan="4" align="left" height="2" style="padding-left:5px;padding-right:5px;padding-bottom:5px;">
		<img src="images/error1.png" height=25>
		</td>
		
		<td width="100%" colspan="4" align="left" height="2">
		<font size="2"><b>Danh sách đơn hàng lỗi trong tháng</b></font>
		</td>
		
	</tr>
</table>

<?php
echo '<!--';
//if ($_REQUEST['mode'] == 'ranklist') {
	$select_tbl_trans_issue=$mysqlIns->select_tbl_trans_issue(0);

//} elseif ($_REQUEST['mode'] == 'ranklistf') {
//	$select_tbl_trans_issue=$mysqlIns->select_tbl_trans_issue(1);

//}
echo '-->';
?>
<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>


<tr bgcolor="#eeedfb" height="22">
	<td align="left" >&nbsp;<b>STT</b>&nbsp;</td>
	<td align="left" >&nbsp;<b>S&#7889; H&#272;</b>&nbsp;</td>
	<td align="left" style="padding-left:2px;" colspan="2" nowrap="nowrap"><b>&#272;&#417;n h&#224;ng&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
	<td align="left" style="padding-left:2px;" colspan="2" nowrap="nowrap"><b>T&#234;n KH&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>S&#7843;n ph&#7849;m</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Ng&#224;y tr&#7843; h&#224;ng</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Th&#224;nh ti&#7873;n</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Thiệt hại</b></td>
	
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Kinh doanh</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Thiết kế</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Sản xuất</b></td>
	
	
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>Giao hàng</b></td>
	<td align="left" style="padding-left:6px;" nowrap="nowrap"><b>&#272;&#225;nh gi&#225; c&#7911;a KH&nbsp;&nbsp;&nbsp;</b></td>
	<td align="left" style="padding-left:6px;padding-right:6px;" nowrap="nowrap" colspan=2><b>X&#7917; l&#253;</b></td>

</tr>
</thead>
<tbody id="body_other">
<?php


$previousref= "";
$displayref= "";
$prg_issue_value = "";
$trn_total_amount = "";
$$trn_payment_remain = "";
$currentref = ' ';
for($i=0;$i<count($select_tbl_trans_issue);$i++)
{
$previousref=$currentref;
$currentref = $select_tbl_trans_issue[$i]["trn_ref"];
$displayref=$select_tbl_trans_issue[$i]["trn_ref"];
$prg_issue_value=$select_tbl_trans_issue[$i]["prg_issue_value"];
$trn_total_amount=$select_tbl_trans_issue[$i]["trn_total_amount"];

echo '<!--';
$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
$persentage = "10";
$status = "Ch&#7901; thi&#7871;t k&#7871;";
$saler = "ManhPD";
$pending = "GiangTT";
$iconsaler = "images/hatman.png";
$iconpending = "images/designer.png";
$xuly = "";


$stt = $i+ 1;

if (strlen($select_tbl_trans_issue[$i]["prg_note"]) < 1)
	$note = '';
else
	$note = trim($select_tbl_trans_issue[$i]["prg_note"]);
echo '-->';
if ($previousref != $currentref) {
	echo '<tr height="1" bgcolor="gray">
		<td width="100%" colspan="16" align="left" valign="middle">
		
		</td>';
} else {
	$displayref = '';
	$prg_issue_value= '';
	$trn_total_amount='';
}


$trn_payment_remain = $trn_total_amount - $prg_issue_value;
if ($trn_payment_remain == 0) $trn_payment_remain = "";


if ($select_tbl_trans_issue[$i]["prg_step4_dt2_f"] != null) {
	$prg_step4_dt2 = $select_tbl_trans_issue[$i]["prg_step4_dt2_f"];
} else {
	$prg_step4_dt2 = $select_tbl_trans_issue[$i]["prg_status_f"];
}

$trn_total_amount_f =  number_format($trn_total_amount, 0, '.', ',');
$trn_payment_remain_f =  number_format($trn_payment_remain, 0, '.', ',');
$prg_issue_value_f =  number_format($prg_issue_value, 0, '.', ',');

$grp_img_step1 = '<img src="images/hatman.png" width="16">';
$grp_img_step2 = '<img src="images/designer.png" width="16">';
$grp_img_step3 = '<img src="images/builder.png" width="16">';
$grp_img_step4 = '<img src="images/deliver.png" width="16">';

echo '

<tr height="22" bgcolor="'.$color.'">
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$stt.'</td>
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$select_tbl_trans_issue[$i]["trn_ref"].'&nbsp;</td>
		<td style=\"padding-left:6px;\" width=\"10\" valign=\"top\"><b><img src="images/viewinfo.png" height="14"></b></td>
		<td style=\"padding-left:0px;\"><b><a onclick="if (event.which != 2) makeBlockUI();" href="index.php?mode=viewdetail&id='.$select_tbl_trans_issue[$i]["trn_id"].'"class="login-window" id="trn_name_'.$select_tbl_trans_issue[$i][0].'" >'.$select_tbl_trans_issue[$i]["trn_name"].'</a></b></td>

		<td style=\"padding-left:6px;\" width=\"10\" valign=\"top\"><b><a onclick="if (event.which != 2) makeBlockUI();" href="?search=user&id='.$select_tbl_trans_issue[$i]["trn_cust_phone"].'"><img src="images/user.png"></a></b></td>
		<td align="left" nowrap=\"nowrap\" style=\"padding-left:0px;\"><b><a onclick="if (event.which != 2) makeBlockUI();" href="?search=user&id='.$select_tbl_trans_issue[$i]["trn_cust_phone"].'" class="login-window" id="congtrinh-'.$select_tbl_trans_issue[$i]["cust_id"].'" >'.$select_tbl_trans_issue[$i]["cust_name"].'<!--nguoigoiden--></a></b></td>
		<td align="left" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$select_tbl_trans_issue[$i]["prd_name"].'<!--tencongtrinh--></a></td>
		
		
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;'.$prg_step4_dt2.'</td>
		<td align="right" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<span id="trn_sub_amount_'.$select_tbl_trans_issue[$i]["trn_id"].'">'.$select_tbl_trans_issue[$i]["trn_amount_f"].'</span></td>
		<td align="right" nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<span id="trn_amount_'.$select_tbl_trans_issue[$i]["trn_id"].'">'.$prg_issue_value_f.'</span></td>
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<b><a onclick="if (event.which != 2) makeBlockUI();" href="index.php?search=staff&id='.$select_tbl_trans_issue[$i]["prg_step1_by"].'"class="login-window" id="congtrinh-'.$select_tbl_trans_issue[$i]["prg_step1_by"].'" >'.$grp_img_step1.' '.$select_tbl_trans_issue[$i]["prg_step1_by"].'</a></b></td>
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<b><a onclick="if (event.which != 2) makeBlockUI();" href="index.php?search=staff&id='.$select_tbl_trans_issue[$i]["prg_step2_by"].'"class="login-window" id="congtrinh-'.$select_tbl_trans_issue[$i]["prg_step2_by"].'" >'.$grp_img_step2.' '.$select_tbl_trans_issue[$i]["prg_step2_by"].'</a></b>&nbsp;</td>
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<b><a onclick="if (event.which != 2) makeBlockUI();" href="index.php?search=staff&id='.$select_tbl_trans_issue[$i]["prg_step3_by"].'"class="login-window" id="congtrinh-'.$select_tbl_trans_issue[$i]["prg_step3_by"].'" >'.$grp_img_step3.' '.$select_tbl_trans_issue[$i]["prg_step3_by"].'</a></b></td>
		<td nowrap=\"nowrap\" style=\"padding-left:6px;\">&nbsp;&nbsp;<b><a onclick="if (event.which != 2) makeBlockUI();" href="index.php?search=staff&id='.$select_tbl_trans_issue[$i]["prg_step4_by"].'"class="login-window" id="congtrinh-'.$select_tbl_trans_issue[$i]["prg_step4_by"].'" >'.$grp_img_step4.' '.$select_tbl_trans_issue[$i]["prg_step4_by"].'</a></b>&nbsp;</td>
		
		<td style=\"padding-left:6px;\"><span '.$styleEditCare.' onclick="setEditNote(this,\''.$select_tbl_trans_issue[$i]["trn_id"].'\')" id="trn_note_'.$select_tbl_trans_issue[$i]["trn_id"].'">'.$note.'</span></td>
		<td align="center" style=\"padding-left:6px;\"><a onclick="javascript:if (confirm(\'Bạn muốn sét đơn hàng ['.$select_tbl_trans_issue[$i]["trn_name"].'] thành không có lỗi ?\')) { makeBlockUI(); } else { return false;}" href="?mode='.$_REQUEST['mode'].'&do=unerr&id='.$select_tbl_trans_issue[$i]["trn_id"].'"><img src="images/refresh.png" height="15"></a></td>
		

		</tr>';

}

echo '<tr><td colspan=16 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
</tbody>
</table>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<thead>
<tr height="20">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>
</thead>
</table>


<form name="hid_post" id="hid_post" method="POST">
	<input type="hidden" id="hid_report_month" name="hid_report_month" value="">
</form>

<?php
echo '<!--';
//if ($_REQUEST['mode'] == 'ranklist') {
	$view_orderstatus_lastmonth=$mysqlIns->view_orderstatus_lastmonth(0);
//} elseif ($_REQUEST['mode'] == 'ranklistf') { 
//	$view_orderstatus_lastmonth=$mysqlIns->view_orderstatus_lastmonth(1);
//}


$complete = $view_orderstatus_lastmonth[0]['total_amount_complete_f'];
$remain_all = $view_orderstatus_lastmonth[0]['payment_remain_amount_all_f'];
$remain_notyet = $view_orderstatus_lastmonth[0]['payment_remain_amount_notyet_f'];
$issue_amount = $view_orderstatus_lastmonth[0]['issue_amount_f'];

echo '-->';
$total = $complete + $remain_all + $remain_notyet + $issue_amount;
if ($total >0)
{
	$complete_per = round(str_replace(',','',$complete) * 100 / $total);
	$remain_all_per = round(str_replace(',','',$remain_all) * 100 / $total);
	$remain_notyet_per = round(str_replace(',','',$remain_notyet) * 100 / $total);
	$issue_amount_per = round(str_replace(',','',$issue_amount) * 100 / $total);
?>
		<script type="text/javascript">
$(function () {
    $('#containerpie1').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'ORDER STATUS (<?php echo $_SESSION['report_month']; ?>)'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Tỷ lệ',
            data: [
                ['Chưa xong<br>(<?php echo $remain_notyet; ?>k)',       <?php echo $remain_notyet_per; ?>],
				
				
				['Thiệt hại<br>(<?php echo $issue_amount; ?>k)',       <?php echo $issue_amount_per; ?>],
				{
					name: 'Đã thu tiền<br>(<?php echo $complete; ?>k)',   
					y: <?php echo $complete_per; ?>,
					sliced: true,
                    selected: true
				},
				['Khách nợ<br>(<?php echo $remain_all; ?>k)', <?php echo $remain_all_per; ?>]
				
            ]
        }]
    });
});
		</script>
<?php
}
?>
<script type="text/javascript">
$(function () {
    $('#container1').highcharts({
        chart: {
            type: 'column',
			height: 450
        },
        title: {
            text: 'RESULT BY CATEGORY'
        },
        xAxis: {
            categories: [<?php 	//echo "<!--";
					$view_report_timeline=$mysqlIns->view_report_timeline();
					//echo "-->";
					for($i=0;$i<count($view_report_timeline);$i++) {
						if ($i < count($view_report_timeline)) {
							echo '\''.$view_report_timeline[$i]['monthreport'].'\',';
						} else {
							echo '\''.$view_report_timeline[$i]['monthreport'].'\'';
						}
					}
			?>]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Amount x1000 (VND)'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: 0,
            verticalAlign: 'top',
            y: -4,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black, 0 0 3px black'
                    }
                }
            }
        },
        series: [<?php 	//echo "<!--";
				$view_report_product=$mysqlIns->view_report_product();
				//echo "-->";
				for($i=0;$i<count($view_report_product);$i++) {
					if ($i < count($view_report_product)) {
						echo '	{
									name: \''.$view_report_product[$i]['prd_code'].'\',
									data: ['; //echo "<!--";
						//if ($_REQUEST['mode'] == 'ranklist') {
							$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],0);
						//} elseif ($_REQUEST['mode'] == 'ranklistf') { 
						//	$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],1);
						//}
						
						//echo "-->";
						for($j=0;$j<count($view_report_product_by_timeline);$j++) {
							if ($j < count($view_report_product_by_timeline)) {
								echo round($view_report_product_by_timeline[$j]['total_amount']/1000).',';
							} else {
								echo round($view_report_product_by_timeline[$j]['total_amount']/1000);
							}
						} echo ']
								},';
					} else {
						
						echo '	{
									name: \''.$view_report_product[$i]['prd_code'].'\',
									data: [
									'; //echo "<!--";
						//if ($_REQUEST['mode'] == 'ranklist') {
							$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],0);
						//} elseif ($_REQUEST['mode'] == 'ranklistf') {
						//	$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],1);
						//}
						
						//echo "-->";
						for($j=0;$j<count($view_report_product_by_timeline);$j++) {
							if ($j < count($view_report_product_by_timeline)) {
								echo round($view_report_product_by_timeline[$j]['total_amount']/1000).',';
							} else {
								echo round($view_report_product_by_timeline[$j]['total_amount']/1000);
							}
						}echo ']
								}';
					}
				}
		?>
		]
    });
});


		function changeMonth() {
			makeBlockUI();
			$("#hid_report_month").val($("#report_month").val());
			document.getElementById("hid_post").submit();
		}
		</script>