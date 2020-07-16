<?php
	class Member
{

	public $res = array();

	public function __construct($mysql){
		$this->mysql = $mysql;
		return $this;
	}

	public function checklogin($user,$pass) {
		if( !empty($user) || !empty($pass) ){
			$this->mysql->select('login', '*', null, 'userid="'.$this->clean_input($user).'" AND user_pass="'.$this->clean_input($pass).'" LIMIT 1');
			$res = $this->mysql->getResult();
			if($res){
				return true;
			}else{
				return false;
			}
		}
		else return false;
	}

	public function mem_login($user,$pass) {
		$this->mysql->select('login', '*', null, 'userid="'.$this->clean_input($user).'" AND user_pass="'.$this->clean_input($pass).'" LIMIT 1');
		$res = $this->mysql->getResult();
		if($res){
			$_SESSION['user'] = $this->clean_input($user);
			$_SESSION['pass'] = $this->clean_input($pass);
			if( $_SESSION['user'] && $_SESSION['pass'] ){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	public function mem_logout($user,$pass) {
		$st1 =  $_SESSION['user'];
		$st2 =  $_SESSION['pass'];
		$st3 = session_destroy();
		if( $st1 && $st2 && $st3 ){
			return true;
		}else{
			return false;
		}
	}
	public function inc_login($accid, $field, $value) {
		$this->mysql->select('login', '*', null, ''.$field.'="'.$this->clean_input($accid).'" LIMIT 1');
		$res = $this->mysql->getResult();
		if( $res ) {
			return $res[$value];
		}else{
			return 'NULL';
		}
	}
	public function chkpass($accid, $passold) {
		$this->mysql->select('login', 'user_pass', null, 'account_id="'.$this->clean_input($accid).'" LIMIT 1');
		$res = $this->mysql->getResult();
		if( $passold != $res['user_pass'] ) {
			return true;
		}else{
			return false;
		}
	}
	public function chkmail($accid, $mailold) {
		$this->mysql->select('login', 'email', null, 'account_id="'.$this->clean_input($accid).'" LIMIT 1');
		$res = $this->mysql->getResult();
		if( $mailold != $res['email'] ) {
			return true;
		}else{
			return false;
		}
	}
	public function inc_char($accid, $field, $value) {
		$this->mysql->select('char', '*', null, ''.$field.'="'.$this->clean_input($accid).'" LIMIT 1');
		$res = $this->mysql->getResult();
		if( $$res ) {
			return $res[$value];
		}else{
			return false;
		}
	}
	public function inc_hwdig($accid) {
		$this->mysql->select('duckdig', '*', null, 'account_id="'.$this->clean_input($accid).'" LIMIT 1');
		$res = $this->mysql->getResult();
		if( $res ) {
			return number_format($res['point']);
		}else{
			return '0';
		}
	}
	public function inc_hwdig2($accid) {
		$this->mysql->select('duckdig', '*', null, 'account_id="'.$this->clean_input($accid).'" LIMIT 1');
		$res = $this->mysql->getResult();
		if( $res ) {
			return number_format($res['total_point']);
		}else{
			return '0';
		}
	}
	public function inc_share2($accid) {
		$this->mysql->select('ducklike', '*', null, 'account_id="'.$this->clean_input($accid).'" LIMIT 1');
		$res = $this->mysql->getResult();
		if( $res ) {
			return number_format($res['total_point']);
		}else{
			return '0';
		}
	}
	public function inc_share($accid) {
		$this->mysql->select('ducklike', '*', null, 'account_id="'.$this->clean_input($accid).'" LIMIT 1');
		$res = $this->mysql->getResult();
		if( $res ) {
			return number_format($res['point']);
		}else{
			return '0';
		}
	}
	public function inc_cash($accid) {
		$this->mysql->select('acc_reg_num', '*', null, 'account_id="'.$this->clean_input($accid).'" LIMIT 1');
		$res = $this->mysql->getResult();
		if( $res ) {
			$cashpoint = $res['value'];
			if( $cashpoint ) {
				return number_format($cashpoint);
			}else{
				return '0';
			}
		}else{
			return '0';
		}
	}
	public function aidonweb($uid) {
		$this->mysql->select('login', 'account_id', null, 'userid="'.$this->clean_input($uid).'" LIMIT 1');
		$res = $this->mysql->getResult();
		if( $res ) {
			return $res['account_id'];
		}else{
			return 'NULL';
		}
	}
	public function user_account($account_id) {
			$this->mysql->select('login', 'userid', null, 'account_id="'.$account_id.'" LIMIT 1');
			$res = $this->mysql->getResult();
			if( $res ) {
				return $res['userid'];
			}else{
				return 'NULL';
			}
	}
	public function get_pvprank($limit = 10){
		$this->mysql->select('char', 'name,point_pvp', null, '', 'point_pvp DESC LIMIT '. $limit);
		$res = $this->mysql->getResult();
		$numrows = $this->mysql->getNumrows();
		if($numrows > 1){
			return $res;
		}else{
			return $res = array($res);
		}
	}
	public function get_mvprank($limit = 10){
		$this->mysql->select('char', 'name,point_mvp', null, '', 'point_mvp DESC LIMIT '. $limit);
		$res = $this->mysql->getResult();
		$numrows = $this->mysql->getNumrows();
		if($numrows > 1){
			return $res;
		}else{
			return $res = array($res);
		}
	}	
	public function get_emprank($limit = 10){
		$this->mysql->select('char', 'name,point_emp', null, '', 'point_emp DESC LIMIT '. $limit);
		$res = $this->mysql->getResult();
		$numrows = $this->mysql->getNumrows();
		if($numrows > 1){
			return $res;
		}else{
			return $res = array($res);
		}
	}
	public function get_voterank($limit = 10){
		$this->mysql->select('duckdig', 'account_id,total_point as point_total', null, '', 'total_point DESC LIMIT '. $limit);
		$res = $this->mysql->getResult();
		$numrows = $this->mysql->getNumrows();
		if($numrows > 1){
			return $res;
		}else{
			return $res = array($res);
		}
	}
	public function get_sharerank($limit = 10){
		$this->mysql->select('ducklike', 'account_id,total_point as point_total', null, '', 'total_point DESC LIMIT '. $limit);
		$res = $this->mysql->getResult();
		$numrows = $this->mysql->getNumrows();
		if($numrows > 1){
			return $res;
		}else{
			return $res = array($res);
		}
	}
	public function statusID($num){
		if( $num == 0 ){
			return "<span style='color:green'>Active</span>";	}
			else{		return "<span style='color:red'>Disabled</span>";	}
	}
	public function statusOn($num){
		if( $num == 0 ){			
		return '<span style="color:red">OFFLINE</span>';		
		}		
		elseif($num == 1){			
		return '<span style="color:green">ONLINE</span>';
		} 
	}
	public function get_char_uid($uid){
		$this->mysql->select('char', '*', null, 'account_id="'.$this->clean_input($this->aidonweb($uid)).'" LIMIT 9');
		$res = $this->mysql->getResult();
		$numrows = $this->mysql->getNumrows();
		if($numrows > 1){
			return $res;
		}else{
			return $res = array($res);
		}
	}
	public function getIP()
	{
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = @$_SERVER['REMOTE_ADDR'];
		if(filter_var($client, FILTER_VALIDATE_IP))
			$ip = $client;
		elseif(filter_var($forward, FILTER_VALIDATE_IP))
			$ip = $forward;
		else
			$ip = $remote;
		return $ip;
	}
	public function changepass($accid,$passold2,$passnew12,$passnew22,$passold3) {
		global $dbname;
		$passold = trim($passold2);
		$passnew1 = trim($passnew12);
		$passnew2 = trim($passnew22);
		$passold1 = trim($passold3);
		if( empty($passold) || empty($passnew1) || empty($passnew2) || empty($passold1)){
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","กรุณากรอกข้อมูลให้ครบถ้วน ทุกช่อง ?","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'password.php\';';
			echo '});';
			echo '}, 200);  </script>';
		}
		elseif( (strlen($passnew1) < 4) || (strlen($passnew1) > 23) ){
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","กรุณากรอกรหัสผ่านใหม่ 4-23 ตัวอักษรขึ้นไป","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'password.php\';';
			echo '});';
			echo '}, 200);  </script>';
		}
		elseif( $passold != $passold1 ){
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","กรุณาใส่รหัสผ่าน ปัจจุบัน ให้ตรงกัน ?","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'password.php\';';
			echo '});';
			echo '}, 200);  </script>';		
		}
		elseif( $passnew1 != $passnew2 ){
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","กรุณาใส่รหัสผ่าน ใหม่ ให้ตรงกัน ?","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'password.php\';';
			echo '});';
			echo '}, 200);  </script>';			
		}
		elseif( $this->chkpass( $accid, $passold ) ){
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","รหัสผ่าน ปัจจุบัน ไม่ถูกต้อง ?","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'password.php\';';
			echo '});';
			echo '}, 200);  </script>';				
		}
		else{
			$this->mysql->update('login', array( 'user_pass' => $this->clean_input($passnew1) ), 'account_id="'.$this->clean_input($accid).'" LIMIT 1');
			$res = $this->mysql->getResult();
			if( $res ){
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ทำรายการสำเร็จ","เปลี่ยนรหัสผ่านเรียบร้อยแล้ว !","success").then( function(val) {';
			echo 'if (val == true) window.location.href = \'password.php\';';
			echo '});';
			echo '}, 200);  </script>';		
			
			}else{
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","พบปัญหาการใช้งาน โปรดติดต่อทีมงาน","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'password.php\';';
			echo '});';
			echo '}, 200);  </script>';			
			}
		}
	}
	public function changeemail($accid,$mailold2,$mailnew12,$passold2) {
		global $dbname;
		$mailold = trim($mailold2);
		$mailnew1 = trim($mailnew12);
		$passold = trim($passold2);
		if( empty($mailold) || empty($mailnew1) || empty($passold)){
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","กรุณากรอกข้อมูลให้ครบถ้วน ทุกช่อง ?","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'email.php\';';
			echo '});';
			echo '}, 200);  </script>';		
		}
		elseif( (strlen($mailnew1) < 4) || (strlen($mailnew1) > 100) ){
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","โปรดระบุ อีเมล์ 4 ตัวอักษรขึ้นไป","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'email.php\';';
			echo '});';
			echo '}, 200);  </script>';				
		}
		elseif ( !ereg("([a-zA-Z0-9._-]+)@([^[:space:]]*)([[:alnum:]]+)\.([a-z])",$mailnew1) ){
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","รูปแบบอีเมล์ไม่ถูกต้อง (ex. test@gmail.com)","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'email.php\';';
			echo '});';
			echo '}, 200);  </script>';				
		}
		elseif( $this->chkpass( $accid, $passold ) ){
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","Password ไม่ถูกต้อง","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'email.php\';';
			echo '});';
			echo '}, 200);  </script>';				
		}
		elseif( $this->chkmail( $accid, $mailold ) ){
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","Email ปัจจุบัน ไม่ถูกต้อง","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'email.php\';';
			echo '});';
			echo '}, 200);  </script>';		
		}
		else{
			$this->mysql->update('login', array( 'email' => $this->clean_input($mailnew1) ), 'account_id="'.$this->clean_input($accid).'" LIMIT 1');
			$res = $this->mysql->getResult();
			if( $res ){
				
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ทำรายการสำเร็จ","เปลี่ยนอีเมล์ผู้ใช้งานเรียบร้อยแล้ว !","success").then( function(val) {';
			echo 'if (val == true) window.location.href = \'email.php\';';
			echo '});';
			echo '}, 200);  </script>';	
			
			}else{
				
			echo '<script type="text/javascript">';
			echo 'setTimeout(function () {';
			echo 'swal("ไม่สามารถทำรายการได้","พบปัญหาการใช้งาน โปรดติดต่อทีมงาน","error").then( function(val) {';
			echo 'if (val == true) window.location.href = \'email.php\';';
			echo '});';
			echo '}, 200);  </script>';	
			}
		}
	}
	
	// Security
	private function clean_input($input) {
		if(get_magic_quotes_gpc()) {
			$input = stripslashes($input);
		}
		$input = strip_tags($input);
		$conoo = mysql_escape_string($input);
		return $conoo;
	}
}
	
?>