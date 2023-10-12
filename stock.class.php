<?php

include 'db.class.php';

class Stock extends DB {
	
	//插入數據
	function ins($tb,$arr) {
		//先補全一下字段的默認值
		$keys = $this->tbkeys($tb);
		foreach($keys as $k => $v) {
			if(!isset($arr[$k])) {
				$arr[$k] = $v;
			}
		}
		//組成插入語句
		$key = array();
		$val = array();
		foreach($arr as $k => $v) {
			$key[] = '`'.$k.'`';
			$val[] = '\''.$this->f($v).'\'';
		}
		$sql = "INSERT INTO `$tb` (".implode(',',$key).") VALUE (".implode(',',$val).")";
		$res = $this->query($sql);
		return $this->insert_id();
	}
	//更新數據
	function upd($tb,$id,$arr) {
		$tmp = array();
		foreach($arr as $k => $v) {
			$tmp[] = '`'.$k.'`=\''.$this->f($v).'\'';
		}
		$sql = "UPDATE `$tb` SET ".implode(',',$tmp)." WHERE `id`='$id'";
		$this->query($sql);
	}
	//獲取數據庫表的字段 並判斷默認值
	function tbkeys($tb) {
		if(!isset($GLOBALS['tbkeys'])) $GLOBALS['tbkeys'] = array();
		if(isset($GLOBALS['tbkeys'][$tb])) return $GLOBALS['tbkeys'][$tb];
		$sql = "SHOW FULL COLUMNS FROM `$tb`";
		$result = $this->get_rows($sql);
		$re = array();
		foreach($result as $k => $v) {
			$type = $v['Type'];
			if($v['Field'] == 'id') {
				continue;
			} elseif(strpos($type,'int(') !== false || strpos($type,'tinyint(') !== false || $type == 'tinyint' || $type == 'int' || $type == 'float') {
				$re[$v['Field']] = 0;
			} elseif($type == 'datetime') {
				$re[$v['Field']] = '0000-00-00 00:00:00';
			} elseif($type == 'date') {
				$re[$v['Field']] = '0000-00-00';
			} else {
				$re[$v['Field']] = '';
			}
		}
		$GLOBALS['tbkeys'][$tb] = $re;
		return $re;
	}
	//
	function get($tb,$a,$ob=array(),$limit=0) {
		$w = array();
		foreach($a as $k => $v) {
			$w[] = "`$k`='$v'";
		}
		$wstr = implode(" AND ",$w);
		$sql = "SELECT * FROM `$tb` WHERE $wstr";
		if(!empty($ob)) $sql .= " ORDER BY `".$ob[0]."` ".$ob[1];
		if($limit > 0) $sql .= " LIMIT $limit";
		return $this->get_rows($sql);
	}
	
	//登錄
	function login($username,$password) {
		$user = $this->user_get_by_username($username);
		if(empty($user)) {
			return false;
		}
		$pw = $user['password'];
		//判斷密碼是否匹配 驗證規則是:crypt(輸入的密碼,數據庫密文) == 數據庫密文
		if(password_verify($password,$pw)) {
			return $user;
		}
		return false;
	}
	
	//根據username獲取賬戶信息
	function user_get_by_username($username) {
		$username = $this->f($username);
		$sql = "SELECT * FROM `users` WHERE `username`='$username' LIMIT 1";
		return $this->get_row($sql);
	}
	
	function user_get_by_id($id) {
		$id = $this->f($id);
		$sql = "SELECT * FROM `users` WHERE `id`='$id'";
		return $this->get_row($sql);
	}
	
	function user_update($id,$a) {
		//if(isset($a['password'])) $a['password'] = crypt($a['password']);//密碼加密
		if(isset($a['password'])) $a['password'] = password_hash($a['password'],PASSWORD_DEFAULT);//密碼加密
		$this->upd('users',$id,$a);
	}

	function user_log_add($a) {
		if(!isset($a['ctime'])) $a['ctime'] = date('Y-m-d H:i:s');
		if(isset($a['content'])) $a['content'] = json_encode($a['content']);
		$this->ins('cad_user_logs',$a);
	}
	
	function user_get_permission_by_uid($uid,$permission='') {
		$uid = $this->f($uid);
		$permission = $this->f($permission);
		if(empty($permission)) {
			$sql = "SELECT * FROM `user_permissions` WHERE `uid`='$uid'";
			return $this->get_rows($sql);
		} else {
			$sql = "SELECT * FROM `user_permissions` WHERE `uid`='$uid' AND `permission`='$permission'";
			return $this->get_row($sql);
		}
	}
	
	//==================== 货架信息 ==================
	//查询货架列表
	function shelf_get($shelf,$layer) {
		$w = array();
		if(strlen($shelf) > 0) $w[] = "`shelf`='$shelf'";
		if(strlen($layer) > 0) $w[] = "`layer`='$layer'";
		if(!empty($w)) {
			$wstr = implode(" AND ",$w);
		} else {
			$wstr = 1;
		}
		$sql = "SELECT * FROM `stock_shelf` WHERE $wstr ORDER BY `shelf` ASC,`layer` ASC";
		return $this->get_rows($sql);
	}
	//添加货架信息
	function shelf_add($a) {
		return $this->ins('stock_shelf',$a);
	}
	//删除货架信息
	function shelf_del_by_id($id) {
		$sql = "DELETE FROM `stock_shelf` WHERE `id`='$id'";
		$this->query($sql);
	}
	//根据ID查询
	function shelf_get_by_id($id) {
		$sql = "SELECT * FROM `stock_shelf` WHERE `id`='$id'";
		return $this->get_row($sql);
	}
	//
	function stock_shelf_data_get_one($pjno,$name,$shelfid) {
		$sql = "SELECT * FROM `stock_shelf_data` WHERE `pjno`='$pjno' AND `name`='$name' AND `shelfid`='$shelfid'";
		return $this->get_rows($sql);
	}
	//按条件搜索货架数据
	function stock_shelf_data_get_by_condition($condition) {
		$w = array();
		if(isset($condition['shelf']) && !empty($condition['shelf'])) $w[] = "`shelf`='".$condition['shelf']."'";
		if(isset($condition['layer']) && !empty($condition['layer'])) $w[] = "`layer`='".$condition['layer']."'";
		if(isset($condition['keyword']) && strlen($condition['keyword']) > 0) $w[] = "`name` LIKE '%".$condition['keyword']."%'";
		if(empty($w)) return array();
		$wstr = implode(' AND ',$w);
		$sql = "SELECT * FROM `stock_shelf_data` WHERE $wstr";
		return $this->get_rows($sql);
	}
	//添加货架记录
	function stock_shelf_data_add($a) {
		return $this->ins('stock_shelf_data',$a);
	}
	//更新货架记录
	function stock_shelf_data_update_num($id,$addition) {
		$sql = "UPDATE `stock_shelf_data` SET `num`+='$addition' WHERE `id`='$id'";
		$this->query($sql);
	}
	//添加出入货架记录
	function stock_log_add($a) {
		return $this->ins('stock_logs',$a);
	}
	//添加库存
	function stock_kucun_add($a) {
		return $this->ins('stock_kucun',$a);
	}
	//
	function stock_kucun_get_one($pjno,$name) {
		$sql = "SELECT * FROM `stock_kucun` WHERE `pjno`='$pjno' AND `name`='$name'";
		return $this->get_rows($sql);
	}
	//更新库存
	function stock_kucun_update_num($id,$addition) {
		$sql = "UPDATE `stock_kucun` SET `num`+='$addition' WHERE `id`='$id'";
		$this->query($sql);
	}
}

