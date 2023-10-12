<?php

include 'config.php';
include 'stock.class.php';

$task = req('task');



$re = array();
$re['task'] = $task;
$re['success'] = true;
$re['login'] = false;


if(isset($_COOKIE['tmp_user_id'])) {
	$tmp_user_id = $_COOKIE['tmp_user_id'];
	setcookie('tmp_user_id',$tmp_user_id,time()+365*82400);
} else {
	$tmp_user_id = time();
	setcookie('tmp_user_id',$tmp_user_id,time()+365*82400);
}

$ctime = date('Y-m-d H:i:s');
$db = new Stock($cfg);
$uid = current_uid();

$uinfo = $db->user_get_by_id($uid);
if(!empty($uinfo)) {
	if(isset($uinfo['password'])) unset($uinfo['password']);
	$tmp = $db->user_get_permission_by_uid($uid);
	$permissions = array();
	foreach($tmp as $k => $v) {
		if(!in_array($v['permission'],$permissions)) $permissions[] = $v['permission'];
	}
	$uinfo['permissions'] = $permissions;
	$re['login'] = true;
	$re['uinfo'] = $uinfo;
} else {
	$uinfo = array();
}

try {
	switch($task) {
		//==================== 登录 =======================
		case 'login':
		$username = req('username');
		$password = req('password');
		$remember = req('remember');
		if(empty($username)) throw new Exception('工號不能為空');
		if(empty($password)) throw new Exception('密碼不能為空');
		if(is_numeric($username)) $username = str_pad($username,5,"0",STR_PAD_LEFT);
		$user = $db->user_get_by_username($username);
		if(empty($user)) throw new Exception('工號不存在');
		if($user['is_disable'] == 1) throw new Exception('工號已禁用');
		if(!password_verify($password,$user['password'])) throw new Exception('密碼錯誤');
		$uid = $user['id'];
		$default_day = 3;//正常登錄是3天
		//記住登錄 默認30天
		if($remember == 1) $default_day = 365;
		$time1 = strtotime(date('Y-m-d 23:59:59'));//時間增加到半夜才過期
		$expire = $time1+3600*24*$default_day;
		setcookie('caduid',$uid,$expire);
		$re['msg'] = '登錄成功';
		//更新數據-最近登錄時間和登錄ip
		$b = array();
		$b['last_login'] = date('Y-m-d H:i:s');
		$b['login_ip'] = $_SERVER['REMOTE_ADDR'];
		$db->user_update($uid,$b);
		
		//操作記錄
		$c = array();
		$c['uid'] = $uid;
		$c['type'] = 'login';
		$c['content'] = array('ip'=>$b['login_ip']);
		$db->user_log_add($c);
		$re['login'] = true;
		$re['uinfo'] = login_check($uid);
		break;
		
		case 'logout':
		setcookie('caduid','',time()-3600);
		//操作記錄
		$c = array();
		$c['uid'] = $uid;
		$c['type'] = 'logout';
		$db->user_log_add($c);
		break;
		
		case 'login_get':
		$re['uinfo'] = login_check();
		break;

		//================ project 工程 ============
		//
		case 'project_get':
		$projects = $db->project_get();
		break;
		
		//================ shelf 货架 ===============

		//返回货架列表
		case 'shelf_get':
		$shelf = req('shelf');
		$layer = req('layer');
		$re['shelfs'] = $db->shelf_get($shelf,$layer);
		break;
		//添加货架信息
		case 'shelf_add':
		$shelf = req('shelf');
		$layer = req('layer');
		if(strlen($shelf) == 0) throw new Exception('货架号不能为空');
		if(strlen($layer) == 0) throw new Exception('货架层号不能为空');
		$shelfs = $db->shelf_get($shelf,$layer);
		if(!empty($shelfs)) throw new Exception('该货架号('.$shelf.'-'.$layer.')已存在');
		$a = array();
		$a['shelf'] = $shelf;
		$a['layer'] = $layer;
		$a['ctime'] = $ctime;
		$shelfid = $db->shelf_add($a);
		$re['shelfid'] = $shelfid;
		$re['shelf'] = $db->shelf_get_by_id($shelfid);
		break;
		//删除货架信息
		case 'shelf_del_by_id':
		$shelfid = req('shelfid');
		$db->shelf_del_by_id($shelfid);
		break;
		//获取货架数据列表
		case 'shelf_data_get':
		$shelf = req('shelf');
		$layer = req('layer');
		$keyword = req('keyword');
		$keys = array('shelf','layer','keyword');
		$condition = array();
		foreach($keys as $k => $v) {
			$condition[$v] = req($v);
		}
		$shelf_data = $db->stock_shelf_data_get_by_condition($condition);
		$re['shelf_data'] = $shelf_data;
		break;
		//获取货架数据列表
		case 'shelf_data_search':
		$shelf = req('shelf');
		$layer = req('layer');
		if(empty($shelf)) throw new Exception('货架号参数不正确');
		$shelf_data = $db->stock_shelf_data_get_by_condition($shelf,$layer);
		$re['shelf_data'] = $shelf_data;
		break;
		//扫描数据解析
		case 'scandata_decode':
		$scandata = req('scandata');
		$check = scandata_check($scandata);
		switch($check[0]) {
			//登录货架
			case 'shelf':
			$shelfid = $check[1];
			$shelf = $db->shelf_get_by_id($shelfid);
			if(empty($shelf)) {
				throw new Exception('货架信息不存在,条码内容是"'.$scandata.'"');
			} else {
				setcookie('shelfid',$shelfid,3600*24*30);
				$re['shelf'] = $shelf;
			}
			break;
			//扫描型材码
			case 'fabdata2':
			//里面区分入库出库
			$shelfid = isset($_COOKIE['shelfid']) ? $_COOKIE['shelfid'] : '';
			if(empty($shelfid)) throw new Exception('请先扫描货架码');
			$shelf = $db->shelf_get_by_id($shelfid);
			if(empty($shelf)) throw new Exception('货架码对应信息不存在');
			$mode = req('mode');
			if(!in_array($mode,array(1,'1',2,'2'))) throw new Exception('模式参数不正确');
			$num = req('num');
			if(empty($num)) throw new Exception('检测不到数量参数');
			if(!is_numeric($num) || $num <= 0) throw new Exception('数量参数不正确');
			$pjno = $check[1];
			$name = $check[2];
			//查找货架库存
			$shelf_data = $db->stock_shelf_data_get_one($pjno,$name,$shelfid);
			if(empty($shelf_data)) {
				$a = array();
				$a['pjno'] = $pjno;
				$a['name'] = $name;
				$a['shelfid'] = $shelfid;
				$a['shelf'] = $shelf['shelf'];
				$a['layer'] = $shelf['layer'];
				$a['num'] = 0;
				$a['ctime'] = $ctime;
				$shelf_data_id = $db->stock_shelf_data_add($a);
				$shelf_data = $a;
				$shelf_data['id'] = $shelf_data_id;
			} else {
				$shelf_data_id = $shelf_data['id'];
			}
			//默认是入库 如果值为2即出库
			if(in_array($mode,array(2,'2'))) {
				$num = -$num;
			}
			//更新货架库存
			$db->stock_shelf_data_update_num($shelf_data_id,$num);
			$re['shelf'] = $db->shelf_get_by_id($shelf);
			break;
		}
		break;
	}
} catch(Exception $e) {
	$re['msg'] = $e->getMessage();
	$re['success'] = false;
}

print_r(json_encode($re));
exit;