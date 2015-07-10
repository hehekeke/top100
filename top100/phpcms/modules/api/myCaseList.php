<?php
pc_base::load_app_class('api', 'api');
class myCaseList  extends api {
    public function __construct() {
        $this->curl = new curl();
    }
    public function init() {
        if (!$_GET[cs]) {
            showmessage('错误的请求', '/index.php?m=content&c=index&a=lists&catid=11');
        }
        $courseid = $_GET['cs'];
        $params = $this->initParams();
        $params['mw'] = ['mc_courseid' => $courseid];
        $row = $this->curl->curl_action('api/index', $params);
        $courseLecturer =$row['data'];
        extract($row['data'][0]);
        // 右侧推荐案例



        $position = $this->getPosition(['mc_assignToSalon' => 1]);


       include template("api","myCaseList");
    }

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

    //到达案例评审
    public function toCase_review(){
        if (!$_GET[cs]) {
            showmessage('错误的请求', '/index.php?m=content&c=index&a=lists&catid=11');
        }
        $courseid = $_GET['cs'];
        $params = $this->initParams();
        $params['mw'] = ['mc_courseid' => $courseid];
        $row = $this->curl->curl_action('api/index', $params);
        $courseLecturer =$row['data'];
        extract($row['data'][0]);
        // 右侧推荐案例
        $position = $this->getPosition(['assignToTop100' => 1]);
        include template("api","myCaseList");
    }
    //chakan case_review
    public function case_review(){
        if (!$_GET[cs]) {
            showmessage('错误的请求', '/index.php?m=content&c=index&a=lists&catid=11');
        }
        $courseid = $_GET['cs'];
        $params = $this->initParams();
        $params['mw'] = ['mc_courseid' => $courseid];
        $row = $this->curl->curl_action('api/index', $params);
        $courseLecturer =$row['data'];
        extract($row['data'][0]);
        // 右侧推荐案例
        $position = $this->getPosition(['assignToTop100' => 1]);
        include template("api","case_review");
    }
    //查看自己的案例
    public function case_see(){
        if (!$_GET[cs]) {
            showmessage('错误的请求', '/index.php?m=content&c=index&a=lists&catid=11');
        }
        $courseid = $_GET['cs'];
        $params = $this->initParams();
        $params['mw'] = ['mc_courseid' => $courseid];
        $row = $this->curl->curl_action('api/index', $params);
        $courseLecturer =$row['data'];
        extract($row['data'][0]);
        // 右侧推荐案例
        $position = $this->getPosition(['assignToTop100' => 1]);
        include template("api","case_see");
    }
}

?>
