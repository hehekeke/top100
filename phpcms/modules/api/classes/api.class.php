<?php 
pc_base::load_app_class('curl','api');
class api {
	public function __construct() {
		$this->curl = new curl();
	}
	/**
	 * 获取字段中是上传附件的值
	 * @param  [string] $fileString 需要被解析的json
	 * @param  [string] $key        获取数组中的哪个字段
	 * @return [type]                [description]
	 */
	public function getAttachUrl($file, $key = 'fileUrl'){
		if (!empty($file)) { 
			$file = json_decode($file, true);
			$file = $file[0][$key];
		} else {
			$file = false;
		}
		return $file;
	}

	public function getPosition($where){
		$positionParams = [
				'mm' => 'kecheng',
				'ms' => 'mc_courseid,mc_title',
				'mp' => 'rand()',
				'mo' => 0,
				'ml' => 4,
				'mw' => $where,

		];
		$position = $this->curl->curl_action('api/index', $positionParams);
		return $position;
	}
}

?>