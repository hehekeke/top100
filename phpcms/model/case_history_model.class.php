<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('model', '', 0);
class case_history_model extends model {
	public function __construct() {
		$this->db_config = pc_base::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'case_history';
		parent::__construct();
	}
	public function getCases($catid=20, $num=10){
		return $this->select(' courseid>0 AND catid='.$catid, 'courseid',$num, 'listorder desc, id desc' );
	}
	public function getCasesData($num=10){
		$table = $this;
		$table->table_name .= '_data';
		return $table->select('', 'title',$num, 'id desc' );

	}
}
?>