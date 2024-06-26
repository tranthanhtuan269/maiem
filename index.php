<?php
ob_start();
header('Content-type: text/html; charset=utf-8'); 
require_once('./global.php'); 
$trans_fixdate_red_min = -9999;
$trans_fixdate_red_max = -1;

$trans_fixdate_yellow_min = 0;
$trans_fixdate_yellow_max = 3;

if (!isset($_REQUEST['vw'])) $_REQUEST['vw'] = "";
if ($_REQUEST['vw'] == "") {
	$_REQUEST['vw'] = "pending";
}

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

//if (!isset($_GET['page']) || $_GET['page'] == null) {
	$splitarr = explode("&page","http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	$_SESSION['req'] = $splitarr[0];
	
//}
function utf8convert($str) {
	if(!$str) return '';
	$utf8 = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'd'=>'đ|Đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'i'=>'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
			);
	foreach($utf8 as $ascii=>$uni) $str = preg_replace("/($uni)/i",$ascii,$str);
return $str;
}

if (!isset($_REQUEST['act'])) $_REQUEST['act'] = "";
if ($_REQUEST['act'] == "logout") {
	$_SESSION['MM_Username'] = null;
    $_SESSION['MM_UserGroup'] = null;
	$_SESSION['MM_Isadmin'] = null;
	$_SESSION['MM_group'] = null;
	$_SESSION['MM_img'] = null;
	$_SESSION['step'] = null;
	$_SESSION['steprow'] = null;
	$_SESSION['limitrow'] = null;
	$_SESSION['report_month'] = null;
	$_SESSION['popup_birthday'] = null;
}

if (!isset($_REQUEST['do'])) $_REQUEST['do'] = "";
if ($_REQUEST['do'] == "xuly") {
	// khong co quyen thi out
	if (!isset($_REQUEST['status'])) $_REQUEST['status'] = "";
	if ($_REQUEST['status'] == "12") 
		$sql = "UPDATE tbl_trans SET prg_status ='21', 
											 prg_step2_dt1 = SYSDATE(), 
											 prg_pending_by = prg_step2_by,
											 prg_pending_from_dt = SYSDATE()
						WHERE trn_id = '".$_REQUEST['id']."'
						";
	if ($_REQUEST['status'] == "21") 
		$sql = "UPDATE tbl_trans SET prg_status ='22', 
											 prg_step2_dt2 = SYSDATE(), 
											 prg_pending_by = prg_step2_by,
											 prg_pending_from_dt = SYSDATE()
						WHERE trn_id = '".$_REQUEST['id']."'
						";
	if ($_REQUEST['status'] == "22") 
		$sql = "UPDATE tbl_trans SET prg_status ='23', 
											 prg_step2_dt3 = SYSDATE(), 
											 prg_pending_by = prg_step3_by,
											 prg_pending_from_dt = SYSDATE()
						WHERE trn_id = '".$_REQUEST['id']."'
						";
	if ($_REQUEST['status'] == "23") 
		$sql = "UPDATE tbl_trans SET prg_status ='31', 
											 prg_step3_dt1 = SYSDATE(), 
											 prg_pending_by = prg_step3_by,
											 prg_pending_from_dt = SYSDATE()
						WHERE trn_id = '".$_REQUEST['id']."'
						";
	if ($_REQUEST['status'] == "31") 
		$sql = "UPDATE tbl_trans SET prg_status ='32', 
											 prg_step3_dt2 = SYSDATE(), 
											 prg_pending_by = prg_step4_by,
											 prg_pending_from_dt = SYSDATE()
						WHERE trn_id = '".$_REQUEST['id']."'
						";
	if ($_REQUEST['status'] == "32") 
		$sql = "UPDATE tbl_trans SET prg_status ='42', 
											 prg_step4_dt2 = SYSDATE(), 
											 prg_pending_by = prg_step4_by,
											 prg_pending_from_dt = SYSDATE()
						WHERE trn_id = '".$_REQUEST['id']."'
						";
	if ($_REQUEST['status'] == "41") 
		$sql = "UPDATE tbl_trans SET prg_status ='42', 
											 prg_step4_dt2 = SYSDATE(), 
											 prg_pending_by = null,
											 prg_pending_from_dt = SYSDATE()
						WHERE trn_id = '".$_REQUEST['id']."'
						";
	if ($_REQUEST['status'] == "42") {
		mysql_select_db($dbhost, $db);
		$query_total_amount = "select sum(t.trn_amount) as total_amount from tbl_trans t where t.trn_ref = '".$_REQUEST['ref']."'";
		$ds_total_amount = mysql_query($query_total_amount, $db) or die(mysql_error());
		$row_total_amount = mysql_fetch_assoc($ds_total_amount);
		
		$sql = "UPDATE tbl_trans SET 		 prg_step5_dt1 = SYSDATE(), 
											 prg_step5_by = '".$_SESSION['MM_Username']."',
											 trn_payment = ".$row_total_amount["total_amount"]." ,
											 trn_payment_remain = 0
						WHERE trn_ref = '".$_REQUEST['ref']."'
						";
		//echo $sql;
		
		$updateSQL = sprintf("INSERT INTO tbl_payment_his(
									payment_id,
									payment_status,
									trn_id,
									trn_ref,
									trn_payment,
									trn_auth_by,
									trn_auth_date) 
									VALUES (
									(select max(t.payment_id) + 1 from tbl_payment_his t),
									'1',
									'%s',
									'%s',
									'%s',
									UCASE('%s'),
									SYSDATE())",
							   $_REQUEST['id'],
							   $_REQUEST['ref'],
							   str_replace(',','',$row_total_amount["total_amount"]),
							   $_SESSION['MM_Username']
							   );
		//echo $updateSQL;
		mysql_select_db($dbhost, $db);
		$Result1 = mysql_query($updateSQL, $db) or die(mysql_error());
	}
	if ($_REQUEST['status'] == "51")
		$sql = "UPDATE tbl_trans SET 		 prg_step5_dt2 = SYSDATE(), 
											 prg_step5_by2 = '".$_SESSION['MM_Username']."',
											 prg_note = '&nbsp;'
						WHERE trn_id = '".$_REQUEST['id']."'
						";
	if ($_REQUEST['status'] == "err")
		$sql = "UPDATE tbl_trans SET 		 prg_issue_by ='".$_SESSION['MM_Username']."',
											 prg_issue_dt = SYSDATE(), 
											 prg_pending_from_dt = SYSDATE(),
											 prg_issue_value = '".$_GET['err_val']."'
						WHERE trn_id = '".$_REQUEST['id']."'
						";
	if (!isset($_SESSION['MM_Isadmin'])) $_SESSION['MM_Isadmin'] = "";
	if ($_REQUEST['status'] == "del" && $_SESSION['MM_Isadmin'] == 1) {
		
		//$ncludeQuery = sprintf("SELECT 	* from tbl_trans limit 0,2");
		//$include = mysql_query($ncludeQuery, $db) or die(mysql_error());
		//$row_include = mysql_fetch_assoc($include);
		//$totalRows_include = mysql_num_rows($include);
		//echo "<script type=\"text/javascript\">alert('a:".$totalRows_include."')</script>";
		//if ($totalRows_include > 1) {
			$sql = "delete from tbl_trans 
							WHERE trn_id = '".$_REQUEST['id']."'
							";
			mysql_select_db($dbhost, $db);
			$Result1 = mysql_query($sql, $db) or die(mysql_error());
			
			$sql = "delete from tbl_payment_hisv2 
							WHERE trn_id = '".$_REQUEST['id']."'
							";
			mysql_select_db($dbhost, $db);
			$Result1 = mysql_query($sql, $db) or die(mysql_error());
			
		//} ELSE {
		//	echo "<script type=\"text/javascript\">alert('Vui lòng giữ lại ít nhất 1 bản ghi')</script>";
		//	header("Location: /");
		//}
	}
  mysql_select_db($dbhost, $db);
  $Result1 = mysql_query($sql, $db) or die(mysql_error());
} else {
	if (!isset($_REQUEST['do'])) $_REQUEST['do'] = "";
	if ($_REQUEST['do'] == "revert") {
		// khong co quyen thi out
		if (!isset($_REQUEST['status'])) $_REQUEST['status'] = "";
		if ($_REQUEST['status'] == "21") 
			$sql = "UPDATE tbl_trans SET prg_status ='12', 
												 prg_step2_dt1 = null, 
												 prg_pending_by = prg_step1_by,
												 prg_pending_from_dt = SYSDATE()
							WHERE trn_id = '".$_REQUEST['id']."'
							";
		if ($_REQUEST['status'] == "22") 
			$sql = "UPDATE tbl_trans SET prg_status ='21', 
												 prg_step2_dt2 = null, 
												 prg_pending_by = prg_step2_by,
												 prg_pending_from_dt = SYSDATE()
							WHERE trn_id = '".$_REQUEST['id']."'
							";
		if ($_REQUEST['status'] == "23") 
			$sql = "UPDATE tbl_trans SET prg_status ='22', 
												 prg_step2_dt3 = null, 
												 prg_pending_by = prg_step2_by,
												 prg_pending_from_dt = SYSDATE()
							WHERE trn_id = '".$_REQUEST['id']."'
							";
		if ($_REQUEST['status'] == "31") 
			$sql = "UPDATE tbl_trans SET prg_status ='23', 
												 prg_step3_dt1 = null, 
												 prg_pending_by = prg_step3_by,
												 prg_pending_from_dt = SYSDATE()
							WHERE trn_id = '".$_REQUEST['id']."'
							";
		if ($_REQUEST['status'] == "32") 
			$sql = "UPDATE tbl_trans SET prg_status ='31', 
												 prg_step3_dt2 = null, 
												 prg_pending_by = prg_step3_by,
												 prg_pending_from_dt = SYSDATE()
							WHERE trn_id = '".$_REQUEST['id']."'
							";
		if ($_REQUEST['status'] == "42") 
			$sql = "UPDATE tbl_trans SET prg_status ='32', 
												 prg_step4_dt2 = null, 
												 prg_pending_by = prg_step4_by,
												 prg_pending_from_dt = SYSDATE()
							WHERE trn_id = '".$_REQUEST['id']."'
							";
		if ($_REQUEST['status'] == "41") 
			$sql = "UPDATE tbl_trans SET prg_status ='32', 
												 prg_step4_dt2 = null, 
												 prg_pending_by = prg_step4_by,
												 prg_pending_from_dt = SYSDATE()
							WHERE trn_id = '".$_REQUEST['id']."'
							";
		if ($_REQUEST['status'] == "42") {
			mysql_select_db($dbhost, $db);
			$query_total_amount = "select sum(t.trn_amount) as total_amount from tbl_trans t where t.trn_ref = '".$_REQUEST['ref']."'";
			$ds_total_amount = mysql_query($query_total_amount, $db) or die(mysql_error());
			$row_total_amount = mysql_fetch_assoc($ds_total_amount);
			
			$sql = "UPDATE tbl_trans SET 		 prg_step5_dt1 = SYSDATE(), 
												 prg_step5_by = '".$_SESSION['MM_Username']."',
												 trn_payment = ".$row_total_amount["total_amount"]." ,
												 trn_payment_remain = 0
							WHERE trn_ref = '".$_REQUEST['ref']."'
							";
			//echo $sql;
		}
		if ($_REQUEST['status'] == "51")
			$sql = "UPDATE tbl_trans SET 		 prg_step5_dt2 = SYSDATE(), 
												 prg_step5_by2 = '".$_SESSION['MM_Username']."',
												 prg_note = '&nbsp;'
							WHERE trn_id = '".$_REQUEST['id']."'
							";
		if ($_REQUEST['status'] == "err")
			$sql = "UPDATE tbl_trans SET 		 prg_issue_by ='".$_SESSION['MM_Username']."',
												 prg_issue_dt = SYSDATE(), 
												 prg_pending_from_dt = SYSDATE(),
												 prg_issue_value = '".$_GET['err_val']."'
							WHERE trn_id = '".$_REQUEST['id']."'
							";
		if ($_REQUEST['status'] == "del" && $_SESSION['MM_Isadmin'] == 1) {
			$ncludeQuery = sprintf("SELECT 	* from tbl_trans limit 0,1");
			$include = mysql_query($ncludeQuery, $db) or die(mysql_error());
			$row_include = mysql_fetch_assoc($include);
			$totalRows_include = mysql_num_rows($include);
			//echo "<script type=\"text/javascript\">alert('b:".$totalRows_include."')</script>";
			if ($totalRows_include > 1) {
				$sql = "delete from tbl_trans 
								WHERE trn_id = '".$_REQUEST['id']."'
								";
				mysql_select_db($dbhost, $db);
				$Result1 = mysql_query($sql, $db) or die(mysql_error());
				
				$sql = "delete from tbl_payment_hisv2 
								WHERE trn_id = '".$_REQUEST['id']."'
								";
				mysql_select_db($dbhost, $db);
				$Result1 = mysql_query($sql, $db) or die(mysql_error());
				
			} ELSE {
				echo "<script type=\"text/javascript\">alert('Vui lòng giữ lại ít nhất 1 bản ghi')</script>";
				header("Location: /");
			}
		}
	  mysql_select_db($dbhost, $db);
	  $Result1 = mysql_query($sql, $db) or die(mysql_error());
	}

}

$sBasePath = $_SERVER['PHP_SELF'] ;
$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "content" )) ;

if (!isset($_REQUEST['act'])) $_REQUEST['act'] = "";
if ($_REQUEST['act'] != "" && $_SESSION['step'] != $_REQUEST['act']) {	
	//echo 'dsddddddddddddddddddddddddddd';
	$_SESSION['limitrow'] = 0;
}

$_SESSION['step'] = $_REQUEST['act'] != ""? $_REQUEST['act'] : $_SESSION['step'];

if (!isset($_REQUEST['ovv'])) $_REQUEST['ovv'] = "";
if ($_REQUEST['ovv'] == "y") {
	if ($_SESSION['MM_Isadmin'] == "1") {
		$_SESSION['step']  = null;
	} else {
		$MM_group = explode(',',$_SESSION['MM_group']);
		$_SESSION['step'] = $MM_group[0];
	}
}
//echo $_SESSION['MM_group'];
if (!isset($_REQUEST['search'])) $_REQUEST['search'] = "";
$_SESSION['search'] = $_REQUEST['search'];

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
	ob_start();
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php 
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
	
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue =  mysql_escape_string($theValue);

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
?>
<?PHP

if (!(isset($_SESSION['MM_Username']) && $_SESSION['MM_Username'] != '')) 
	{
		ob_start();
		header ("Location: 		./login.php");
		exit();
	}
$sBasePath = $_SERVER['PHP_SELF'] ;
$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "content" )) ;

require("src/mysql_function.php");
$mysqlIns=new mysql(); $mysqlIns->link=$db;
if(!isset($_REQUEST['page'])) $_REQUEST['page']=1;
$page=$_REQUEST['page'];
if(!isset($_REQUEST['mode'])) $_REQUEST['mode']="";
$mode=$_REQUEST['mode'];
$RECORD_IN_PAGES=30;
if(!isset($_REQUEST['submit'])) $_REQUEST['submit']='';
$submit=$_REQUEST['submit'];
if(!isset($_GET['method'])) $_GET['method']="";
$method=$_GET['method'];
//config upload
$uploaddata="../upload_data/ebook/";
$upload_min="../Upload_min/";

if ($mode=='logout')
{
	$_SESSION['MM_Username'] = null;
}

mysql_select_db($dbhost, $db);
$ncludeQuery = sprintf("SELECT 	a.*,
								b.*
								from tbl_grp_action a, tbl_action b, tbl_user c, tbl_user_group d
								WHERE UCASE(c.user_name)  = UCASE(%s)
								and UCASE(a.gat_grp) = UCASE(d.urg_grp_code)
								and c.user_name = d.urg_user_name
								and a.gat_act_code = b.act_code  
								and a.gat_act_code  ='".$mode."'
								", GetSQLValueString($_SESSION['MM_Username'], "text"));
$include = mysql_query($ncludeQuery, $db) or die(mysql_error());
$row_include = mysql_fetch_assoc($include);
$totalRows_include = mysql_num_rows($include);

?>

<head><title>Hệ thống quản lý sản xuất</title>
	<link type="text/css" rel="stylesheet" href="lib/css/template_css.css" >
	<link type="text/css" rel="stylesheet" href="lib/css/niceforms-default.css" />
	<script type="text/javascript" src="lib/js/jquery-1.9.1.min.js"></script>

	<link type="text/css" rel="stylesheet" href="lib/css/dialog.css" />
	<link type="text/css" rel="stylesheet" href="lib/css/styles.css" />
	
	<!--Start Popup-->
	<script type="text/javascript" src="lib/js/jquery-ui/jquery-ui.js"></script>
	<link type="text/css" rel="stylesheet" href="lib/js/jquery-ui/jquery-ui.css" />
	<link type="text/css" rel="stylesheet" href="lib/js/datetimepicker/jquery.datetimepicker.css"/>
	<script type="text/javascript" src="lib/js/datetimepicker/jquery.datetimepicker.js"></script>

	<!--End Popup-->
	<script type="text/javascript" src="lib/js/jquery.blockUI.js"></script>
	<script type="text/javascript" src="lib/js/jquery.blockUI.min.js"></script>

	<link rel="stylesheet" href="lib/css/flatnav.css" media="screen" />
	<script type="text/javascript" src="lib/js/prefixfree-1.0.7.js"></script>
	
	<script type="text/javascript" src="lib/js/jquery.growl.js"></script>
	<link rel="stylesheet" href="lib/css/jquery.growl.css" media="screen" />
	
	<script type="text/javascript" src="lib/js/ohsnap.js"></script>
	

	<script type="text/javascript" src="lib/js/jquery.easy-confirm-dialog.js"></script>
	<script type="text/javascript" src="lib/js/jquery.easy-confirm-dialog.min.js"></script>
	
	<script type="text/javascript" src="lib/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="lib/js/bootstrap-confirmation.js"></script>
	<link rel="stylesheet" href="lib/css/bootstrap.min.css" media="screen" />
	
	<script type="text/javascript" src="lib/js/fancyapps-fancyBox-18d1712/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="lib/js/fancyapps-fancyBox-18d1712/source/jquery.fancybox.css?v=2.1.5" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="lib/js/fancyapps-fancyBox-18d1712/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="lib/js/fancyapps-fancyBox-18d1712/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
	
	
    <script type="text/javascript">
		
        function makeBlockUI() {
            $.blockUI({ css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            }
            });
			
        }
		
		function return_comas(obj) {
			thisobj = $('#' + obj.id);
			var num = thisobj.val().replace(/,/g, '');
			thisobj.val(num.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
		}
		
		function replace_comas(obj) {
			thisobj = $('#' + obj.id);
			var num = thisobj.val().replace(/,/g, '');
			thisobj.val(num);
		}
		
		
		function onlynumber(evt) {
		  var theEvent = evt || window.event;
		  var key = theEvent.keyCode || theEvent.which;
		  key = String.fromCharCode( key );
		  var regex = /[0-9]/;
		  if( !regex.test(key) ) {
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		  }
		}
		
		function onlynumber1(evt) {
		  var theEvent = evt || window.event;
		  var key = theEvent.keyCode || theEvent.which;
		  key = String.fromCharCode( key );
		  var regex = /[-\0-9]/;
		  if( !regex.test(key) ) {
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		  }
		}
		
		function onlydate(evt) {
		  var theEvent = evt || window.event;
		  var key = theEvent.keyCode || theEvent.which;
		  key = String.fromCharCode( key );
		  var regex = /[0-9\-]/;
		  if( !regex.test(key) ) {
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		  }
		}
		
		function onlyusername(evt) {
		  var theEvent = evt || window.event;
		  var key = theEvent.keyCode || theEvent.which;
		  key = String.fromCharCode( key );
		  var regex = /[A-Za-z0-9_]/;
		  if( !regex.test(key) ) {
			theEvent.returnValue = false;
			if(theEvent.preventDefault) theEvent.preventDefault();
		  }
		}
		function submit_sort_col(col,derect) {
			//$('#hid_parm_sort_col').val(col);
			makeBlockUI();
			
			if (derect == 'DESC') {
				derect = 'ASC';
			} else {
				derect = 'DESC';
			}
			
			<?php
			if (strpos($_SESSION['req'],'?') !== false) {
			?>
			var path = '<?=$_SESSION['req']?>&hid_parm_sort_col=' + col + '&derect=' + derect;
			<?php
			} else {
			?>
			var path = '<?=$_SESSION['req']?>?hid_parm_sort_col=' + col + '&derect=' + derect;
			<?php
			}
			?>
			
			//alert(path);
			window.location=path;
			//document.getElementById("hid_parm").submit();
		}
		
		function submitLock(id,val) {
		
			$("#post_type").val("lock");
			$('#post_id').val(id);
			$('#post_value').val(val);
			makeBlockUI();
			document.getElementById("hid_parm").submit();
		}
		
		function submitDelAcc(id) {
		
			$("#post_type").val("deluser");
			$('#post_id').val(id);
			makeBlockUI();
			document.getElementById("hid_parm").submit();
		}
		
		function submitDelGrp(id,val) {
		
			$("#post_type").val("delgrp");
			$('#post_id').val(id);
			$('#post_value').val(val);
			makeBlockUI();
			document.getElementById("hid_parm").submit();
		}

		function submitDelOpt(id,tp,name) {
		
			$("#post_type").val("delopt");
			$('#post_id').val(id);
			$('#post_value').val(tp);
			$('#post_name').val(name);
			makeBlockUI();
			document.getElementById("hid_parm").submit();
		}
		
		$( document ).ready(function() {
			//ohSnap('Oh Snap! I cannot process your card...', 'red');
			//$.growl({ title: "Growl", message: "The kitten is awake!" });
			//$.growl.error({ message: "The kitten is attacking!" });
			//$.growl.notice({ message: "The kitten is cute!" });
			//$.growl.warning({ message: "The kitten is ugly!" });
			//$('[data-toggle="confirmation"]').confirmation()
			//$('[data-toggle="confirmation-singleton"]').confirmation({singleton:true});
			//$('[data-toggle="confirmation-popout"]').confirmation({popout: true});
		});
		
		function confirmClick(id){
			//alert(id);
			//return false;
			$("#"+id).easyconfirm({locale: { title: 'Select Yes or No', button: ['No','Yes']}});
			$("#"+id).click(function() {
				return true;
			});
			return false;
		}
		
		<?php $rowdata=$mysqlIns->get_birthday();
		if (count($rowdata) > 0 && ($_SESSION['popup_birthday'] == null)) { 
		$_SESSION['popup_birthday'] = count($rowdata); ?>
		
		$(document).ready(function() {
			$("#fancybox_hidden").fancybox(
				{

					maxWidth: 700,
					maxHeight: 550,
					'onComplete': function() {
					  $("#fancybox-wrap").css({'top':'20px', 'bottom':'auto'});
				   },
				   padding:0, margin:0
					
				}
			).trigger('click');
		});
		
		
		<?php } ?>
		$(document).ready(function() {
			$(".fancybox1").fancybox(
			
			{

					maxWidth: 700,
					maxHeight: 550,
					'onComplete': function() {
					  $("#fancybox-wrap").css({'top':'20px', 'bottom':'auto'});
				   },
				   padding:0, margin:0
					
				}
			);
		});
		
		
    </script>
<style type="text/css">

body {
  font-family: 'Roboto',sans-serif;
}	

</style>
</head><body background="images/bgdot.png">
<div style="display:none;">
<a class="fancybox" id="fancybox_hidden" href="birthday.php" data-fancybox-type="iframe"></a>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr>




<td>
<table bgcolor="#ed1c24" border="0" cellpadding="0" cellspacing="0" width="100%" >


<?php
if ($_SESSION['MM_Isadmin'] == 1) {
	$url = "index.php?ovv=y";
} else {
	$url = "index.php?act=SALE&vw=pending";
}
?>

<tr height="2" width="80%" >
<td width="10%"><a onclick="makeBlockUI();" href="<?=$url?>"><img src="images/logo_2711.png" height=26></a>
</td>
<td width="90%" align="center" valign="middle" height="35" style="padding-left:30px;padding-top:2px;padding-bottom:5px;">

<div class="breadcrumb flat">
	<a onclick="makeBlockUI();" href="?act=SALE&vw=pending" <?php if ($_SESSION['step']=="SALE") echo "class='active'";?>>Kinh doanh</a>
	<a onclick="makeBlockUI();" href="?act=DESIGN&vw=pending" <?php if ($_SESSION['step']=="DESIGN") echo "class='active'";?>>Thiết kế</a>
	<a onclick="makeBlockUI();" href="?act=BUILD&vw=pending" <?php if ($_SESSION['step']=="BUILD") echo "class='active'";?>>Sản xuất</a>
	<a onclick="makeBlockUI();" href="?act=DELIVER&vw=pending" <?php if ($_SESSION['step']=="DELIVER") echo "class='active'";?>>Giao hàng</a>
    <a onclick="makeBlockUI();" href="?act=CARE&vw=pending" <?php if ($_SESSION['step']=="CARE") echo "class='active'";?>>Chăm sóc</a>
</div>
</td>

<td id="td_help" onmouseover="$('#td_help').attr('bgcolor','#fc989d');" onmouseout="$('#td_help').attr('bgcolor','');" style="padding-left:5px;padding-right:5px;" align="center" width="60" class="settingfocus" nowrap="nowrap">
<span id="help_bar">

</span>
</td>

<td id="td_username" onmouseover="$('#td_username').attr('bgcolor','#fc989d');" onmouseout="$('#td_username').attr('bgcolor','');" 
style="padding-left:5px;padding-top:0px;padding-bottom:0px;padding-right:5px;border-left:1px " align="center" width="1">
<b><img src="<?php echo $_SESSION['MM_img']; ?>" height=12><br><?php echo $_SESSION['MM_Username']; if ($_SESSION['MM_Isadmin'] == 1) echo '<font color="#ffffff">+</font>'?></b>

<ul class="nav" class="tbl_shadow1" onmouseover="$('#td_username').attr('bgcolor','#fc989d');" onmouseout="$('#td_username').attr('bgcolor','');">

		<li>
			<ul class="subuser tbl_shadow1" >
				<li><a href="?mode=changeinfo"><table border="0" cellpadding="4" cellspacing="5" width="100%" ><tr height="30"><td width=1 ></td><td  width=5><img src="images/typeicon1.png" height=30></td><td style="padding-left:5px;padding-right:5px;"><b>Sửa thông tin cá nhân</b></td></tr></table></a></li>
				<li><a href="?mode=changepass"><table border="0" cellpadding="4" cellspacing="5" width="100%" ><tr height="30"><td width=1 ></td><td  width=5><img src="images/uniticon1.png" height=30></td><td style="padding-left:5px;padding-right:5px;"><b>Đổi mật khẩu</b></td></tr></table></a></li>
			</ul>
		</li>
	</ul>

</td>

<td id="td_logout" onmouseover="$('#td_logout').attr('bgcolor','#fc989d');" onmouseout="$('#td_logout').attr('bgcolor','');" style="padding-left:5px;padding-right:5px;" align="center" width="1%" class="settingfocus" ><b><a href="?act=logout"><img src="images/exit.png" height=12><br>Logout</a></b></td>
<td id="td_setting" onmouseover="$('#td_setting').attr('bgcolor','#fc989d');" onmouseout="$('#td_setting').attr('bgcolor','');" align="center" width="1" style="padding-left:5px;padding-top:0px;padding-bottom:0px;padding-right:5px;" >
<a  href="#" ><img src="images/listmain.png" height=30></a>
<ul class="nav" class="tbl_shadow1" >

		<li >
			
			<ul class="subnav tbl_shadow1" >
				<li><a href="?mode=prdlist"><table border="0" cellpadding="4" cellspacing="5" width="100%" ><tr height="30"><td width=1 ></td><td  width=5><img src="images/typeicon1.png" height=30></td><td style="padding-left:5px;padding-right:5px;"><b>Thuộc tính sản phẩm</b></td></tr></table></a></li>
				<li><a href="?mode=settingmark"><table border="0" cellpadding="4" cellspacing="5" width="100%" ><tr height="30"><td width=1 ></td><td  width=5><img src="images/uniticon1.png" height=30></td><td style="padding-left:5px;padding-right:5px;"><b>Đơn vị tính điểm</b></td></tr></table></a></li>
				<li><a href="?mode=userlist"><table border="0" cellpadding="4" cellspacing="5" width="100%" ><tr height="30"><td width=1 ></td><td  width=5><img src="images/usericon1.png" height=30></td><td style="padding-left:5px;padding-right:5px;"><b>Quản lý user</b></td></tr></table></a></li>
			</ul>
		</li>
	</ul>

</td>
<td align="center" width="10" >

</td>
</tr>




<tr width="100%" background="images/border_header.jpg" height="1"><td colspan="8">
</td>

</tr>
<tr height="1" width="100%" bgcolor="#ffffff"><td colspan="8">
</td>

</tr>

<tr valign="top" bgcolor="#ffffff">
	<td valign="top" colspan="8">
<table border="0" width="100%" valign="top" background="images/bgdot.png">
		<tbody><tr>
		<td valign="top">
		<table class="tbl_shadow" width=1>
	<tr><td>
	<?php 
	include('quickmenu.php');
	?>
	</td></tr>
	</table>
		</td>
			<td valign=top width="100%" align="center">
			
			<?php 
			if($mode=="")
			{
				include('body.php');
			}
			else{
				switch ($mode)
				{
					case '111':
						include('includes/111.php');
						break;
					default:
						if ($totalRows_include > 0 )
						{	
							include($row_include['act_path']);
						} elseif ($_SESSION['MM_Isadmin'] == 1) {
							$ncludeQuery = sprintf("SELECT 	a.*
							from tbl_action a
							WHERE a.act_code  ='".$mode."'
							");
							$include = mysql_query($ncludeQuery, $db) or die(mysql_error());
							$row_include = mysql_fetch_assoc($include);
							$totalRows_include = mysql_num_rows($include);
							
							include($row_include['act_path']);
						}
						else
						{
							echo "<table width='100%'><tr><td align='center' width='100%'>
								 <font color='red'><b>Bạn không có quyền ở chức năng này</b></font>
								 </td>
								 </tr>
								 </table>";
						}
				}
			}
			
			?>
			</td>
			
</tr></table></td></tr></table>
</td></tr></table>
<table width="90%" cellspacing="0" cellpadding="0">
<thead>
<tr height="43" >

		<td width="40%" colspan="8" align="center" valign="middle"></td>
		
	</tr>
</thead>
<form name="hid_parm" id="hid_parm" method="POST">
	<input type="hidden" id="hid_parm_act" name="hid_parm_act" value="">
	<input type="hidden" id="hid_parm_vw" name="hid_parm_vw" value="">
	<input type="hidden" id="hid_parm_search" name="hid_parm_search" value="">
	<input type="hidden" id="hid_parm_sort_col" name="hid_parm_sort_col" value="">
	
	<input type="hidden" id="post_type" name="post_type" value="">
	<input type="hidden" id="post_id" name="post_id" value="">
	<input type="hidden" id="post_value" name="post_value" value="">
	<input type="hidden" id="post_name" name="post_name" value="">

</form>
</table>
</body>
	<html>
<?php mysql_close($db); ?>
