<?php
pc_base::load_app_class('api', 'api');
class myCaseList  extends api {
    public function __construct() {
        $this->curl = new curl();
    }
    public function init() {
        $user_id = param::get_cookie('_userid');
            $data = [  
                   'anlitijiao'=>[
                        'mcs_userid'=>$user_id
                   ]             
                    
            ];
         $return = $this->curl->curl_action('user-api/get-case-submit-list', $data );
         $courseLecturer = $return['data'];
       
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
       
        $user_id = param::get_cookie("_userid");
        $data['anlitijiao']['mcs_userid'] =  $user_id;
            $data = [
                'anlitijiao' => [
                    'mcs_userid' =>$user_id
                ]
            ];
         $return = $this->curl->curl_action('user-api/get-case-submit-review-list',$data);
         $courseLecturer = $return['data'];
        // 右侧推荐案例
        $position = $this->getPosition(['assignToTop100' => 1]);
        include template("api","myCaseList");
    }
    //进行评审
    public function case_review(){
       if (!$_GET[case_id]) {
            showmessage('错误的请求', '/index.php?m=content&c=index&a=lists&catid=11');
        }
        $user_id = param::get_cookie("_userid");
         $data = [
                'anlitijiao' => [
                            'mcs_userid'=>$user_id,
                           'mcs_id'=>$_GET[case_id]
                    ]
            ];
          
         $return = $this->curl->curl_action('user-api/get-case-submit-review-detail-by-id',$data);
       
         $courseLecturer = $return['data'];
        // 右侧推荐案例
        $position = $this->getPosition(['assignToTop100' => 1]);
        include template("api","case_review");
    }
    //查看自己的案例
    public function case_see(){
        if (!$_GET[case_id]) {
            showmessage('错误的请求', '/index.php?m=content&c=index&a=lists&catid=11');
        }
         $user_id = param::get_cookie("_userid");

         $data = [
                'anlitijiao' => [
                           'mcs_id'=>$_GET[case_id],
                            'mcs_userid' =>$user_id
                        ]
            ];
           
         $return = $this->curl->curl_action('user-api/get-case-submit-detail-by-id',$data);
        
           // p($return);
         $courseLecturer = $return['data'];
     
        // 右侧推荐案例
        $position = $this->getPosition(['assignToTop100' => 1]);
        include template("api","case_see");
    }
    // 提交反馈意见
    public function addCaseAdvice($value='')
    {
         
         $data = [
                'anlitijiao' => [
                           'mcs_id'=>$_POST['case_id'],
                           'mcs_caseAdvice'=>trim($_POST['caseAdvice']),
                        ]
            ];
         
         $return = $this->curl->curl_action('user-api/add-case-submit-advice',$data);
         showmessage('案例评审成功','index.php?m=api&c=myCaseList&cs=10513&a=toCase_review');
       
       
    }

    //到达编辑案例的页面
    public function toUpdateCaseSubmit($value='')
    {
        if (!$_GET[case_id]) {
            showmessage('错误的请求', '/index.php?m=content&c=index&a=lists&catid=11');
        }
        $user_id = param::get_cookie("_userid");
         $data = [
                'anlitijiao' => [
                            'mcs_userid'=>$user_id,
                           'mcs_id'=>$_GET[case_id]
                    ]
            ];
         $return = $this->curl->curl_action('user-api/get-case-submit-detail-by-id',$data);
         $courseLecturer = $return['data'];
         
        // p($courseLecturer);
          include template("api","case_update");
    }
}

?>
