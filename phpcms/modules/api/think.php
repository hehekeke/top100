<?php 
pc_base::load_app_class('api', 'api');
class think  extends api{
	public $curl = '';
	public $pageSize;
	public function __construct(){
		$this->curl = new curl();
		$this->pageSize = 20;

	}

	// 按标题搜索
	public function search(){
		$keyword = addslashes($_POST['keyword']);

		// 组织搜索语句
		$params = $this->initParams();
		$params['mw'] = "(mc_title like '%".$keyword."%' or mc_lecturerId like '%".$keyword."%')and mc_assignToTop100=1";
		$start = $this->getPageStart();
		$params['mo'] = $start;
		$params['ml'] = $this->pageSize;

		$row = $this->curl->curl_action('api/index', $params);
		$data = json_encode($row['data']);
		if (!$_GET['isAjax'] ) {
			include template('api', 'think');
		} else {
			echo $data;
		}
	}
	public function init(){
		// $params = $this->initParams();
		// $start = $this->getPageStart();
		// $response = $this->getCoursesByTag($tagId, $start, $this->pageSize);
		// $data = json_encode($response);
		// if (empty($_GET['isAjax']) || !$_GET['isAjax']) { 
		// 	include template("api", "think");
		// } else {
		// 	echo $data;
		// }
		$this->search();
	}
	// 初始化查询参数
	public function initParams(){
		$params = [
			'mm' => 'kecheng',
			'mr' => [
				'kechengjiaolian' => [
					'mm' => 'kechengjiaolian',
					'mr' => [
						'jiaolian' => [
							'mm' => 'jiaolian',
							'ms' => 'ml_id,ml_name,ml_description,thumbs,ml_company,ml_position'
						]
					]
				]
			],
			'mo' => 'mc_courseid desc,mc_createdAt desc',

		];
		return $params;
	}

	// 显示 ID 俺哥案例
	public function show()
	{	
		if (!$_GET[cs]) {
			showmessage('错误的请求', '/index.php?m=content&c=index&a=lists&catid=11');
		}
		$courseid = $_GET['cs'];
		$params = $this->initParams();
		$params['mw'] = ['mc_courseid' => $courseid];
		$row = $this->curl->curl_action('api/index', $params);
		extract($row['data'][0]);
		// 右侧推荐案例
		$position = $this->getPosition(['assignToTop100' => 1]);
		$file = $this->getAttachUrl($file);
		$speech = $this->getAttachUrl($speech);
		include template("api", "showThink");
	} 

	// 按标签搜索课程
	public function listCourses(){
		if (!$_GET['t'] && !is_int($_GET['t']))showmessage('你访问的页面找不到');
		$tagId = $_GET['t'];
		$start = $this->getPageStart();
		$response = $this->getCoursesByTag($tagId, $start, $this->pageSize);
		$data = json_encode($response);
		if (empty($_GET['isAjax']) || !$_GET['isAjax']) { 
			include template("api", "listThink");
		} else {
			echo $data;
		}
	}

	// 获取分页 offset的值 
	public function getPageStart(){
		return empty($_GET['page']) ? 0 : $_GET['page']*$this->pageSize;
	}
	public function getCourseNum(){
		$num = $this->countCourseNum('mc_assignToTop100=1');
		echo $_GET['callback'].'('.json_encode($num).')';
	}
	public function getSalonNum(){
		$num = $this->countCourseNum('mc_assignToSalon=1');
		echo $_GET['callback'].'('.json_encode($num).')';
	}
	public function countCourseNum($where){
		$params = [
			'mm' => 'kecheng',
			'ms' => 'count(mc_courseid) as num',
			'mw' => $where,
		];
		$row = $this->curl->curl_action('api/index', $params);
		return $row;
	}
	// 通过标签获取课程
	public function getCoursesByTag($tagId, $start=0, $limit=20){
		$where = 'c.assignToTop100=1';
		if ($tagId) {
			$where .= ' AND tr.tagId = '.$tagId;
		}
		$getParams = [
			'mm' => 'kecheng',
			'ms' => 'c.praises,c.content,c.auditionvideo,c.title,c.courseid,c.desc,c.comments,c.hits',
			'mw' => $where,
			'mo' => $start,
			"ml" => $limit
		];
		$row = $this->curl->curl_action('api/search-course', $getParams);
		// 循环获取结果中的教练
		// ToDo 待优化
		// if (!empty($row)) {
		// 	$response = $row[data];
		// 	foreach($response as &$v) {
		// 		$request = [
		// 			'mm' => "kechengjiaolian",
		// 			"mw" => ["mcl_cid" => $v[courseid]],
		// 			"mr" => [
		// 				"jiangshi" => [
		// 					"mm" => "jiangshi",
		// 					"ms" => "ml_name,ml_penName,ml_position,ml_thumbs,ml_company"
		// 				]
		// 			],
		// 			"mo" => "0",
		// 			"ml" => "20"
		// 		];
		// 		$lecturers = $this->curl->curl_action(
		// 				'api/indx', $request);
		// 		// $v['courseLecturer'] = $lecturers[data];
		// 	}
		// }
		return $row[data];
	}
}
?>