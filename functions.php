<?php

//================ Common Configs ================//

//报告模板列表
$cfg['tpl_lists'] = array();
$cfg['tpl_lists'][] = array('name'=>'鋁板檢驗報告','tpl'=>'aluminium_panel');
$cfg['tpl_lists'][] = array('name'=>'鋁型材檢驗報告','tpl'=>'aluminium_profile');
$cfg['tpl_lists'][] = array('name'=>'玻璃檢驗報告','tpl'=>'glass');
//各模板对应的报告表字段
$cfg['report_segments'] = array();
$cfg['report_segments']['aluminium_panel'] = array(
	array('key'=>'order_no','cn'=>'訂單編號','en'=>'Order#/P.O.#'),
	array('key'=>'delivery_date','cn'=>'返貨日期','en'=>'Delivery Date','inputtype'=>'date'),
	array('key'=>'inspection_standard','cn'=>'檢驗標準','en'=>'Inspection Standard'),
	array('key'=>'supplier','cn'=>'供應商','en'=>'Supplier'),
	array('key'=>'date_of_inspection','cn'=>'檢驗日期','en'=>'Date of Inspection','inputtype'=>'date'),
	array('key'=>'date_of_report','cn'=>'報告日期','en'=>'Date of Report','inputtype'=>'date'),
	array('key'=>'report_no','cn'=>'報告單號','en'=>'Report No.'),
);
$cfg['report_segments']['aluminium_profile'] = array(
	array('key'=>'project','cn'=>'地盤名稱','en'=>'Pproject'),
	array('key'=>'order_no','cn'=>'訂單編號','en'=>'Order#/P.O.#'),
	array('key'=>'date_of_survey','cn'=>'檢驗日期','en'=>'Date of Survey','inputtype'=>'date'),
	array('key'=>'inspection_standard','cn'=>'檢驗標準','en'=>'Inspection Standard'),
	array('key'=>'supplier','cn'=>'供應商','en'=>'Supplier'),
	array('key'=>'delivery_date','cn'=>'返貨日期','en'=>'Delivery Date','inputtype'=>'date'),
	array('key'=>'date_of_report','cn'=>'報告日期','en'=>'Date of Report','inputtype'=>'date'),
	array('key'=>'report_no','cn'=>'報告單號','en'=>'Report No.'),
);
$cfg['report_segments']['glass'] = array(
	array('key'=>'project','cn'=>'地盤名稱','en'=>'Pproject'),
	array('key'=>'order_no','cn'=>'訂單編號','en'=>'Order#/P.O.#'),
	array('key'=>'container_no','cn'=>'貨櫃編號','en'=>'Container No.'),
	array('key'=>'inspection_standard','cn'=>'檢驗標準','en'=>'Inspection Standard'),
	array('key'=>'supplier','cn'=>'供應商','en'=>'Supplier'),
	array('key'=>'batch_no_of_delivery','cn'=>'返貨批次','en'=>'Batch No. of Delivery'),
	array('key'=>'date_received','cn'=>'返貨日期','en'=>'Date Received','inputtype'=>'date'),
	array('key'=>'date_of_report','cn'=>'報告日期','en'=>'Date of Report','inputtype'=>'date'),
	array('key'=>'glass_type','cn'=>'玻璃種類[此欄以英文填寫]','en'=>'Glass Type'),
	array('key'=>'report_no','cn'=>'報告單號','en'=>'Report No.'),
);
//各模板對應的內容表字段
$cfg['content_segments'] = array();
$cfg['content_segments']['aluminium_panel'] = array(
	array('key'=>'delivery_no','cn'=>'返貨單編號','en'=>'Delivery No.'),
	array('key'=>'size','cn'=>'規格','en'=>'Size'),
	array('key'=>'grade_level','cn'=>'牌號-等級','en'=>'Grade Level'),
	array('key'=>'project_no','cn'=>'工程編號','en'=>'Project No.'),
	array('key'=>'delivery_qty','cn'=>'返貨數','en'=>'Delivery Qty'),
	array('key'=>'checked_qty','cn'=>'抽檢數','en'=>'Checked Qty'),
	array('key'=>'ir_mechanical_properties','cn'=>'拉伸測試','en'=>'Mechanical Properties'),
	array('key'=>'ir_size','cn'=>'尺寸','en'=>'Size'),
	array('key'=>'ir_thickness','cn'=>'厚度','en'=>'Thickness'),
	array('key'=>'ir_hardness','cn'=>'硬度','en'=>'Hardness'),
	array('key'=>'ir_chemical_composition','cn'=>'成份檢測','en'=>'Chemical Composition'),
	array('key'=>'passed_qty','cn'=>'合格數','en'=>'Passed Qty'),
	array('key'=>'failed_reason_qty','cn'=>'不合格數量&原因','en'=>'Failed Reason & Qty'),
	array('key'=>'remarks','cn'=>'備註','en'=>'Remarks'),
);
$cfg['content_segments']['aluminium_profile'] = array(
	array('key'=>'delivery_no','cn'=>'返貨單編號','en'=>'Delivery No.'),
	array('key'=>'section_no','cn'=>'鋁型材號','en'=>'Section No.'),
	array('key'=>'length','cn'=>'長度(m)','en'=>'Length'),
	array('key'=>'alloy','cn'=>'材質','en'=>'Alloy'),
	array('key'=>'finish','cn'=>'表面處理','en'=>'Finish'),
	array('key'=>'coating','cn'=>'膜厚(um)','en'=>'Coating'),
	array('key'=>'delivery_qty','cn'=>'返貨數','en'=>'Delivery Qty'),
	array('key'=>'checked_qty','cn'=>'抽檢數','en'=>'Checked Qty'),
	array('key'=>'ir_length','cn'=>'長度','en'=>''),
	array('key'=>'ir_surface','cn'=>'表面','en'=>''),
	array('key'=>'ir_hue','cn'=>'色澤','en'=>''),
	array('key'=>'ir_mohou','cn'=>'膜厚','en'=>''),
	array('key'=>'ir_bihou','cn'=>'壁厚','en'=>''),
	array('key'=>'ir_hardness','cn'=>'硬度','en'=>'Hardness'),
	array('key'=>'ir_flatness','cn'=>'平整度','en'=>'Flatness'),
	array('key'=>'ir_passed_qty','cn'=>'合格數','en'=>'Passed Qty'),
	array('key'=>'remarks','cn'=>'備註','en'=>'Remarks'),
);
$cfg['content_segments']['glass'] = array(
	array('key'=>'case_no','cn'=>'箱號','en'=>'Case No.'),
	array('key'=>'glass_tag_no','cn'=>'玻璃編號','en'=>'Glass Tag No.'),
	array('key'=>'size_wh','cn'=>'規格','en'=>'Size(W×H)'),
	array('key'=>'delivery_qty','cn'=>'返貨數','en'=>'Delivery Qty'),
	array('key'=>'checked_qty','cn'=>'抽檢數','en'=>'Checked Qty'),
	array('key'=>'ir_size','cn'=>'尺寸','en'=>'Size'),
	array('key'=>'ir_thickness','cn'=>'厚度','en'=>'Thickness'),
	array('key'=>'ir_flatness','cn'=>'平整度','en'=>'Flatness'),
	array('key'=>'ir_color','cn'=>'顏色','en'=>'Color'),
	array('key'=>'ir_tempered','cn'=>'強化','en'=>'Tempered'),
	array('key'=>'ir_heat_soaked','cn'=>'熱浸','en'=>'HeatSoaked'),
	array('key'=>'ir_coating','cn'=>'鍍膜','en'=>'Coating'),
	array('key'=>'ir_complexity','cn'=>'組合度','en'=>'Complexity'),
	array('key'=>'ir_sealant','cn'=>'結構膠','en'=>'Sealant'),
	array('key'=>'ir_packing','cn'=>'包裝','en'=>'Packing'),
	array('key'=>'ir_others','cn'=>'其它','en'=>'Others'),
	array('key'=>'passed_qty','cn'=>'合格數','en'=>'Passed Qty'),
);

//================ Common Functions ================//


//獲取請求參數
function req($key,$val='') {
	return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $val;
}

//獲取當前登錄的uid
function current_uid() {
	return isset($_COOKIE['caduid']) ? $_COOKIE['caduid'] : 0;
}

//
function login_check($uid='') {
	global $db;
	if(empty($uid)) $uid = current_uid();
	if(!empty($uid)) {
		$uinfo = $db->user_get_by_id($uid);
		if(isset($uinfo['password'])) unset($uinfo['password']);
		$tmp = $db->user_get_permission_by_uid($uid);
		$permissions = array();
		foreach($tmp as $k => $v) {
			if(!in_array($v['permission'],$permissions)) $permissions[] = $v['permission'];
		}
		$uinfo['permissions'] = $permissions;
		return $uinfo;
	}
	return null;
}


//判斷是否有權限
function check_permissions($permissions) {
	global $uinfo;
	if(!empty($uinfo)) {
		if($uinfo['username'] == 'admin') return true;
		if($uinfo['user_type'] == 1) return true;
		if(!is_array($permissions)) $permissions = array($permissions);
		foreach($permissions as $k => $v) {
			if(in_array($v,$uinfo['permissions'])) return true;
		}
	}
	return false;
}


//扫描数据分解
function scandata_check($scandata) {
	if(strlen($scandata) == 24 && substr($scandata,0,2) == '02') {
		//RFID格式
		$s1 = hexdec(substr($scandata,2));
		return array('sub_unique',$s1);
	} else if(preg_match('/,/',$scandata,$m)) {
		//新二维码内容结构
		return explode('.',$scandata);//type,pjno,part_no,idx
	} else if(preg_match('/plid-(\d+)$/',$scandata,$m)) {
		return array('plid',$m[1]);
	} else if(preg_match('/\.[c|C][i|I][f|F]$/',$scandata,$m)) {
		$part_no = str_replace($m[0],'',$scandata);
		return array('fab',$part_no);
	} else if(preg_match('/Project: (.*?)\r\n/',$scandata,$m1) && preg_match('/Unit: (.*?)\r\n/',$scandata,$m2)) {
		return array('sub',$m1[1],$m2[1]);
	}
	return false;
}