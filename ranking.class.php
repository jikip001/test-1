<?php

class Ranking
{
	
	public function __construct($mysql){
		$this->mysql = $mysql;
		$this->mysql->connect();
		return $this;
	}

	public function useronacc($account_id) {
		$this->mysql->select('login', 'userid', null, 'account_id="'.$account_id.'" LIMIT 1');
		$res = $this->mysql->getResult();
		if( $res ) {
			return $res['userid'];
		}else{
			return 'NULL';
		}
	}

	public function get_digrank($limit = 10){
		$this->mysql->select('duckdig', 'account_id,total_point as point_total', null, '', 'total_point DESC LIMIT '. $limit);
		$res = $this->mysql->getResult();
		$numrows = $this->mysql->getNumrows();
		if($numrows > 1){
			return $res;
		}else{
			return $res = array($res);
		}
	}		public function get_sharerank($limit = 10){		$this->mysql->select('ducklike', 'account_id,total_point as point_total', null, '', 'total_point DESC LIMIT '. $limit);		$res = $this->mysql->getResult();		$numrows = $this->mysql->getNumrows();		if($numrows > 1){			return $res;		}else{			return $res = array($res);		}	}

	public function get_pvprank($limit = 10){
		$this->mysql->select('char', 'char.char_id,char.name,char_rank.pvp_kill_score', 'char_rank', 'char_rank.char_id = char.char_id and pvp_kill_score > 0' , 'pvp_kill_score DESC LIMIT '. $limit);
		$res = $this->mysql->getResult();
		$numrows = $this->mysql->getNumrows();
		if($numrows > 1){
			return $res;
		}else{
			return $res = array($res);
		}
	}
	public function get_pvprank_char($limit = 10){
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
		$this->mysql->select('char', 'char.char_id,char.name,char_rank.mvp_score', 'char_rank', 'char_rank.char_id = char.char_id and mvp_score > 0', 'mvp_score DESC LIMIT '. $limit);
		$res = $this->mysql->getResult();
		$numrows = $this->mysql->getNumrows();
		if($numrows > 1){
			return $res;
		}else{
			return $res = array($res);
		}
	}
	
	public function get_mvprank_char($limit = 10){
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

}

?>