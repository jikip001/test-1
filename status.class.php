<?php
/*
	Status Ragnarok Class (mysql_crud only)
	Create by Aummua
*/

class Status
{

	public function __construct($mysql){
		$this->mysql = $mysql;
		$this->mysql->connect();
	}

	public function count_char() {
		$this->mysql->select('char');
		$res = $this->mysql->getNumrows();
		return $res;
	}
	
	public function count_allid() {
		$this->mysql->select('login');
		$res = $this->mysql->getNumrows();
		return $res;
	}
	
	/*public function count_useronline($command = 1) {
		$real_users = $this->get_real_users();
		$custom_users = $this->get_custom_users();
		/$res = ($real_users*$command)+$custom_users;
		return $res;
	}*/
	
	public function get_real_users() {
		$this->mysql->select('char', 'online', null, 'online=1');
		$real_users = $this->mysql->getNumrows();
		return $real_users;
	}
	
	public function get_custom_users() {
		$this->mysql->select('custom_users');
		$res = $this->mysql->getResult();
		return $res['users'];
	}
	
	public function get_statusserver($IPPORT) {
		$check_ipport = $this->check_ipport($IPPORT);
		$interval = time()+$IPPORT['interval'];
		$chk_cookie = $_COOKIE['status_checked'];
		$status_server = $_COOKIE['status_server'];
		if($chk_cookie != 'true'){
			if(!$check_ipport['login'] || !$check_ipport['char'] || !$check_ipport['map']){
				$res = 'offline';
				setcookie('status_server', 'offline', $interval);
				setcookie('status_checked', 'true', $interval);
			}else{
				$res = 'online';
				setcookie('status_server', 'online', $interval);
				setcookie('status_checked', 'true', $interval);
			}
		}else if($chk_cookie == 'true'){
			if($status_server == 'offline'){
				$res = 'offline';
			}else{
				$res = 'online';
			}
		}
		return $res;
	}

	private function check_ipport($IPPORT){	
		$res['login'] = fsockopen($IPPORT['ip_server'], $IPPORT['login_port'], $errno, $errstr, 1);
		$res['char'] = fsockopen($IPPORT['ip_server'], $IPPORT['char_port'], $errno, $errstr, 1);
		$res['map'] = fsockopen($IPPORT['ip_server'], $IPPORT['map_port'], $errno, $errstr, 1);
		return $res;
	}


	public function get_login($IPPORT) {
		$check_ipport = $this->check_ipport($IPPORT);
		$interval = time()+$IPPORT['interval'];
		$chk_cookie = $_COOKIE['status_checked1'];
		$status_server = $_COOKIE['status_server1'];
		if($chk_cookie != 'true'){
			if(!$check_ipport['login']){
				$res = 'offline';
				setcookie('status_server1', 'offline', $interval);
				setcookie('status_checked1', 'true', $interval);
			}else{
				$res = 'online';
				setcookie('status_server1', 'online', $interval);
				setcookie('status_checked1', 'true', $interval);
			}
		}else if($chk_cookie == 'true'){
			if($status_server == 'offline'){
				$res = 'offline';
			}else{
				$res = 'online';
			}
		}
		return $res;
	}

	public function get_char($IPPORT) {
		$check_ipport = $this->check_ipport($IPPORT);
		$interval = time()+$IPPORT['interval'];
		$chk_cookie = $_COOKIE['status_checked2'];
		$status_server = $_COOKIE['status_server2'];
		if($chk_cookie != 'true'){
			if(!$check_ipport['char']){
				$res = 'offline';
				setcookie('status_server2', 'offline', $interval);
				setcookie('status_checked2', 'true', $interval);
			}else{
				$res = 'online';
				setcookie('status_server2', 'online', $interval);
				setcookie('status_checked2', 'true', $interval);
			}
		}else if($chk_cookie == 'true'){
			if($status_server == 'offline'){
				$res = 'offline';
			}else{
				$res = 'online';
			}
		}
		return $res;
	}

	public function get_map($IPPORT) {
		$check_ipport = $this->check_ipport($IPPORT);
		$interval = time()+$IPPORT['interval'];
		$chk_cookie = $_COOKIE['status_checked3'];
		$status_server = $_COOKIE['status_server3'];
		if($chk_cookie != 'true'){
			if(!$check_ipport['map']){
				$res = 'Offline';
				setcookie('status_server3', 'offline', $interval);
				setcookie('status_checked3', 'true', $interval);
			}else{
				$res = 'online';
				setcookie('status_server3', 'online', $interval);
				setcookie('status_checked3', 'true', $interval);
			}
		}else if($chk_cookie == 'true'){
			if($status_server == 'offline'){
				$res = 'Offline';
			}else{
				$res = 'Online';
			}
		}
		return $res;
	}


}

?>