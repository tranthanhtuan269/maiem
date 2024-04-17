
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

$width = 98;
?>
<script src="lib/js/Highcharts-4.0.4/js/highcharts.js"></script>
<script src="lib/js/Highcharts-4.0.4/js/highcharts-3d.js"></script>
<script src="lib/js/Highcharts-4.0.4/js/modules/exporting.js"></script>


<style type="text/css">
${demo.css}
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
<thead>

	<tr  height="4">
<td width="70%" colspan="4" valign="top" align="center" height="4">
		<div id="containercolumn" style="min-width: 510px; height: 400px; margin: 0 auto"></div>
</td>
		
	</tr>
	<tr  height="30">
<td width="70%" colspan="4" valign="top" align="center" height="30">
&nbsp;
</td>
		
	</tr>
</thead>
</table>


<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0"  >
<thead>

	<tr  height="4">
<td width="48%" colspan="4" valign="top" align="center" height="4">
		<div id="containerpie" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		
		</td>
<td width="20" valign="top">&nbsp;&nbsp;&nbsp;</td>		
		
		<td width="48%" colspan="4" valign="top" align="center" height="4">
		<div id="containerpie1" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
		
		</td>
		
	</tr>
</thead>
</table>


<?php
echo '<!--';
$view_topvalueCust_lastmonth=$mysqlIns->view_topvalueCust_lastmonth();
$total_amount = $view_topvalueCust_lastmonth[0]['total_amount'];

//
$view_topreturnCust_lastmonth=$mysqlIns->view_topreturnCust_lastmonth();
$total_id = $view_topreturnCust_lastmonth[0]['total_id'];

echo '-->';
?>
<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0"  >
<thead>

	<tr  height="4">
		<td width="50%" colspan="4" align="center" height="4" valign="top">
<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>

<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding-left:6px;padding-right:6px;" width="5%"><b>STT</b></td>
	<td align="left" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="20%"><b>Tên KH</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="30%"><b>Tên Cty</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="15%"><b>Email</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="15%"><b>Số ĐT</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="15%"><b>Doanh số</b></td>
	
</tr>
</thead>
<tbody id="body_other">
<?php

for($i=0;$i<count($view_topvalueCust_lastmonth);$i++)
{
$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
$stt = $i+ 1;

echo '
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'">
		<td nowrap="nowrap" style="padding-left:6px;padding-right:6px;" >'.$stt.'</td>
		<td style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_topvalueCust_lastmonth[$i]['cust_name'].'</td>
		<td align="right" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_topvalueCust_lastmonth[$i]['cust_company'].'</td>
		<td nowrap="" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_topvalueCust_lastmonth[$i]['cust_email'].'</td>
		<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_topvalueCust_lastmonth[$i]['cust_phone'].'</td>
		<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_topvalueCust_lastmonth[$i]['total_amount_complete'].'</td>
		
</tr>';
if ($i == 9) $i = count($view_topvalueCust_lastmonth);
}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>

</tbody>
</table>


</td>
<td width="20" valign="top">&nbsp;&nbsp;&nbsp;</td>		
		
		<td width="50%" colspan="4" valign="top" align="center" height="4">

<table width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #c2c2c2; border-collapse:none;" class="tbl_shadow">
<thead>

<tr bgcolor="#eeedfb">
	<td nowrap="nowrap" align="left" style="padding-left:6px;padding-right:6px;" width="5%"><b>STT</b></td>
	<td align="left" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="20%"><b>Tên KH</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="30%"><b>Tên Cty</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="15%"><b>Email</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="15%"><b>Số ĐT</b></td>
	<td nowrap="nowrap" align="right" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;" width="15%"><b>Số HĐ</b></td>
</tr>
</thead>
<tbody id="body_other">
<?php
for($i=0;$i<count($view_topreturnCust_lastmonth);$i++)
{
$color =($i%2==0) ? "#F8F8F5" : "#FFFFFF";
$stt = $i+ 1;

echo '
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>
<tr height="22" bgcolor="'.$color.'">
		<td nowrap="nowrap" style="padding-left:6px;padding-right:6px;" >'.$stt.'</td>
		<td style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_topreturnCust_lastmonth[$i]['cust_name'].'</td>
		<td align="right" style="padding-left:6px;padding-right:6px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_topreturnCust_lastmonth[$i]['cust_company'].'</td>
		<td nowrap="" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_topreturnCust_lastmonth[$i]['cust_email'].'</td>
		<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_topreturnCust_lastmonth[$i]['cust_phone'].'</td>
		<td nowrap="nowrap" align="right" valign="center" style="padding-left:6px;padding-right:8px;border-left: 1px solid #c2c2c2; border-collapse:none;">'.$view_topreturnCust_lastmonth[$i]['total_id_complete'].'</td>
		
</tr>';
if ($i == 9) $i = count($view_topreturnCust_lastmonth);
}

echo '<tr><td colspan=17 width="100%"><div id="content" height="1"></div></td></tr>'; 
?>
<tr height="1" bgcolor="gray">
		<td width="100%" colspan="10" align="left" valign="middle">
		</td>
</tr>

</tbody>
</table>

<table width="<?php echo $width; ?>%" cellspacing="0" cellpadding="0" >
<thead>
<tr height="7">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>

</thead>
</table>
		
		</td>
		
	</tr>
</thead>
</table>

<table width="98%" cellspacing="0" cellpadding="0"  >

	
<tr height="10">
	
		<td width="10%" colspan="4" align="center" height="2"></td>
		
	</tr>
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
if ($total_amount >0)
{
?>
		<script type="text/javascript">
		
$(function () {
    $('#containercolumn').highcharts({
        chart: {
            type: 'column',
			marginTop: 40,
			height: 400
        },
        title: {
            text: 'THỐNG KÊ KHÁCH HÀNG QUAY LẠI'
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
                text: '<?php if($_REQUEST['grp'] =='SALE' || $_REQUEST['grp'] =='') echo 'Amount x1000 (VND)'; else echo 'Mark (POINT)'; ?>'
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
            y: -10,
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
						$view_report_timeline_staff=$mysqlIns->view_report_timeline_customer();
						$view_report_timeline_staff_old=$mysqlIns->view_report_timeline_customer_old();
							
						echo '	{
									name: \'Khách mới\',
									data: ['; 

						for($j=0;$j<count($view_report_timeline_staff);$j++) {
							if ($j < count($view_report_timeline_staff)) {
									echo round(($view_report_timeline_staff[$j]['total_amount'] - $view_report_timeline_staff_old[$j]['total_amount'])/1000).',';
							} else {
									echo round(($view_report_timeline_staff[$j]['total_amount'] - $view_report_timeline_staff_old[$j]['total_amount'])/1000);
							}
						} echo ']
								},';

						
						echo '	{
									name: \'Khách cũ\',
									data: ['; 

						for($j=0;$j<count($view_report_timeline_staff);$j++) {
							if ($j < count($view_report_timeline_staff)) {
									echo round($view_report_timeline_staff_old[$j]['total_amount']/1000).',';
							} else {
									echo round($view_report_timeline_staff_old[$j]['total_amount']/1000);
							}
						} echo ']
								}';

		?>
		]
    });
});



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
            text: 'TOP 10 CUSTOMER (<?php echo $_SESSION['report_month']; ?>)'
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
			<?php 	
			$sumper = 0;
			$othersumper = 0;
			for($i=0;$i<count($view_topvalueCust_lastmonth);$i++) {
						$sumper = $sumper + $view_topvalueCust_lastmonth[$i]['per_amount_complete'];
						$total_amount = $total_amount - $view_topvalueCust_lastmonth[$i]['total_amount_complete'];
						//echo '\''.$view_topvalueCust_lastmonth[$i]['monthreport'].'\',';
						echo '[\''.$view_topvalueCust_lastmonth[$i]['cust_name'].'<br>('.$view_topvalueCust_lastmonth[$i]['total_amount_complete'].'k)\', '.$view_topvalueCust_lastmonth[$i]['per_amount_complete'].'],';
						if ($i == 10) $i = count($view_topvalueCust_lastmonth);
					}
			$othersumper = 100 - $sumper;
			echo '[\'OTHER<br>('.$total_amount.'k)\','.$othersumper.']';	
			?>
				
			
            ]
        }]
    });
});
<?php 
} 

if ($total_id >0)
{
?>

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
            text: 'TOP 10 PARTNER (<?php echo $_SESSION['report_month']; ?>)'
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
			<?php 	
			$sumper = 0;
			$othersumper =0;
			for($i=0;$i<count($view_topreturnCust_lastmonth);$i++) {
						$sumper = $sumper + $view_topreturnCust_lastmonth[$i]['per_id_complete'];
						$total_id = $total_id - $view_topreturnCust_lastmonth[$i]['total_id_complete'];
						//echo '\''.$view_topreturnCust_lastmonth[$i]['monthreport'].'\',';
						echo '[\''.$view_topreturnCust_lastmonth[$i]['cust_name'].'<br>('.$view_topreturnCust_lastmonth[$i]['total_id_complete'].')\', '.$view_topreturnCust_lastmonth[$i]['per_id_complete'].'],';
						if ($i == 10) $i = count($view_topreturnCust_lastmonth);
					}
			$othersumper = 100 - $sumper;
			echo '[\'OTHER<br>('.$total_id.')\','.$othersumper.']';	
			?>
				
			
            ]
        }]
    });
});
<?php
}
?>
		</script>

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
						if ($_REQUEST['mode'] == 'ranklist') {
							$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],0);
						} elseif ($_REQUEST['mode'] == 'ranklistf' || $_REQUEST['mode'] == 'rankcustf') { 
							$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],1);
						}
						
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
						if ($_REQUEST['mode'] == 'ranklist') {
							$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],0);
						} elseif ($_REQUEST['mode'] == 'ranklistf' || $_REQUEST['mode'] == 'rankcustf') {
							$view_report_product_by_timeline=$mysqlIns->view_report_product_by_timeline($view_report_product[$i]['prd_code'],1);
						}
						
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
	
