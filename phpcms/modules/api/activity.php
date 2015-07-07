<?php
pc_base::load_app_class('api', 'api');
class activity  extends api {
	public function __construct() {
		$this->curl = new curl();
	}
	public function init() {

		$params = $this->initParams();
		$params['mw'] .= ' AND startTime > '.time();
	 	$rows = $this->curl->curl_action('api/index', $params);
	 	if ($rows['errno'] == 0) {
		 	// 将教练信息归并到最上层的数组中
		 	foreach($rows['data'] as &$row) {
		 		$row['poster'] = $this->getAttachUrl($row['poster']);
		 		$row['lecturers'] = [];
		 		if (empty($row['schedulingVenueCourse'])) continue;
		 		$row['lecturers'] = $this->getLecturerFromCourseLecturer($row['schedulingVenueCourse']);
			 	unset($row['schedulingVenueCourse']);
			}
		} else {
			// showmessage($rows['errmsg'], '/index.php');
		}
		
		$position =$position = $this->getPosition(['mc_assignToSalon' => 1]);
		$schedulings = $rows['data'];
	 	include template('api', 'activity');
	}

	public function show()
	{
		if (empty($_GET['id']) || !preg_match("/^[0-9]*$/", $_GET['id']))  showmessage('您访问的页面不存在','index.php?m=api&c=activity');
	 	$params = [
	 		'mm' => 'paike',
	 		'ms' => 'ms_title,ms_content,ms_startTime,ms_address,ms_clicks,ms_comments,ms_poster,ms_praises,ms_applicans,ms_celling,ms_createdAt',
	 		'mw' => ['ms_type'=>5, 'ms_id' => $_GET['id']],
	 		'mr' => [
	 			'huichangkecheng' => [
	 				'mm' => 'huichangkecheng',
	 				'mp' => 'msvc_date desc,msvc_startTime asc',
	 				'mr' => [
	 					'kechengjiaolian' => [
	 						'mm' => 'kechengjiaolian',
	 						'mr' => [
	 							'jiangshi' => [
	 								'mm' => 'jiangshi',
	 								'ms' => "ml_name,ml_thumbs,ml_company,ml_position,ml_description"
	 							],
	 							'kecheng' => [
	 								'mm' => 'kecheng',
	 								'ms' => 'mc_title,mc_content'
	 							]
	 						]
	 					]
	 				]
	 			]
	 		]

	 	];

	 	$scheduling = $this->curl->curl_action('api/index', $params);
	 	$scheduling = $scheduling['data'][0];


	 	$courses = [];//所有的课程集合
	 	// 提取出教练和课程，去除掉重复的教练和课程
	 	foreach($scheduling['schedulingVenueCourse'] as &$courseLecturers) {
	 		foreach($courseLecturers['courseLecturer'] as &$course) {
		 		if (!in_array( $course['cid'],$courses)) {
		 			$courses[$course['cid']]['course'] = $course['course'];
		 		}
		 		$courses[$course['cid']]['startTime'] = $courseLecturers['startTime'];
		 		$courses[$course['cid']]['endTime'] = $courseLecturers['endTime'];
		 		$course['lecturer']['thumbs'] = $this->getAttachUrl($course['lecturer']['thumbs']);
		 		$courses[$course['cid']]['lecturer'][$course['lid']] = $course['lecturer'];

	 		}

	 	}

		$position =$position = $this->getPosition(['mc_assignToSalon' => 1]);

	 	unset($scheduling[schedulingVenueCourse]);
	 	$scheduling['courses'] = $courses;
	 	include template('api', 'showActivity');
	}
	public function history(){
		$params = $this->initParams();
		$params['mw'] .= '  AND startTime < '.time();
	 	$rows = $this->curl->curl_action('api/index', $this->initParams());
	 	if ($rows['errno'] == 0) {
		 	// 将教练信息归并到最上层的数中
		 	foreach($rows['data'] as &$row) {
		 		$row['poster'] = $this->getAttachUrl($row['poster']);
		 		$row['lecturers'] = [];
		 		if (empty($row['schedulingVenueCourse'])) continue;
		 		$row['lecturers'] = $this->getLecturerFromCourseLecturer($row['schedulingVenueCourse']);
			 	unset($row['schedulingVenueCourse']);
			}
		} else {
			showmessage($rows['errmsg'], '/index.php');
		}
		$position =$position = $this->getPosition(['mc_assignToSalon' => 1]);
		$schedulings = $rows['data'];
	 	include template('api', 'activity');
	}
	
	/**
	 * 报名界面
	 */
	public function signup(){
	    
	    if (empty($_GET['id']) || !preg_match("/^[0-9]*$/", $_GET['id']))  showmessage('您访问的页面不存在','index.php?m=api&c=activity');
	    $params = [
	        'mm' => 'paike',
	        'ms' => 'ms_title,ms_content,ms_startTime,ms_endTime,ms_address,ms_clicks,ms_comments,ms_poster,ms_praises,ms_applicans,ms_celling',
	        'mw' => ['ms_type'=>5, 'ms_id' => $_GET['id']],
	        'mr' => [
	            'huichangkecheng' => [
	                'mm' => 'huichangkecheng',
	                'mp' => 'msvc_date desc,msvc_startTime asc',
	                'mr' => [
	                    'kechengjiaolian' => [
	                        'mm' => 'kechengjiaolian',
	                        'mr' => [
	                            'jiangshi' => [
	                                'mm' => 'jiangshi',
	                                'ms' => "ml_name,ml_thumbs,ml_company,ml_position,ml_description"
	                            ],
	                            'kecheng' => [
	                                'mm' => 'kecheng',
	                                'ms' => 'mc_title,mc_content'
	                            ]
	                        ]
	                    ]
	                ]
	            ]
	        ]
	    
	    ];
	    
	    $scheduling = $this->curl->curl_action('api/index', $params);
	    $scheduling = $scheduling['data'][0];
	   // echo json_encode($scheduling);exit;
	    include template('api', 'signup');
	}
	
	
	private function initParams($where){
		$pageNum = !empty($_GET['n']) ? $_GET['n'] : 1;
	 	$listNum = 20;
	 	$params = [
	 		'mm' => 'paike',
	 		'ms' => 'ms_title,ms_content,ms_startTime,ms_address,ms_clicks,ms_comments,ms_poster,ms_applicans,ms_celling,ms_createdAt',
	 		'mw' => 'ms_type=5',
	 		'mo' => ($pageNum-1)*$listNum,
	 		'ml' => $listNum,
	 		'mp' => 'ms_id desc',
	 		'mr' => [
	 			'huichangkecheng' => [
	 				'mm' => 'huichangkecheng',
	 				'mr' => [
	 					'kechengjiaolian' => [
	 						'mm' => 'kechengjiaolian',
	 						'mr' => [
	 							'jiangshi' => [
	 								'mm' => 'jiangshi',
	 								'ms' => "ml_name,ml_company,ml_position"
	 							]
	 						]
	 					]
	 				]
	 			]
	 		]

	 	];
		return $params;
	}
	private function getKeyFromCourseLecturer($scheduling, $key){
		$idName = substr($key, 0, 1).'id';
		$value = [];

 		foreach($scheduling as $courses) {
			foreach($courses['courseLecturer'] as $courseLecturer) {
				if(!in_array( $courseLecturer[$idName], $$lecturers)) {
					$value[$courseLecturer[$idName]] = $courseLecturer[$key];
				}
			}
	 	}
	 	return $value;
	}
	private function getCourseFromCourseLecturer($scheduling){
		return $this->getKeyFromCourseLecturer($scheduling, 'course');
	}
	private function getLecturerFromCourseLecturer($scheduling){
		return $this->getKeyFromCourseLecturer($scheduling, 'lecturer');

	}
}

?>
