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
         $retuen_isReview =  $this->curl->curl_action('user-api/user-is-reivew', $data );
        
        
         $isReview = $retuen_isReview['data'];
        // 右侧推荐案例

        $data = array();
        $page = 1   ;// 每页显示的数据条数
        $totalData = count($return['data']);
        $totalPage = ceil($totalData/$page); //总分页数
        $currentPage = (int)$_GET['currPage'];
       
        if(empty(($currentPage))){
                  $currentPage = 1;
        }
        //截取url
        $t = $_SERVER['REQUEST_URI'];
        $urlSub = strpos($t,'currPage');
        $url = substr($t,0,$urlSub+9); 
        
        //每页显示的第一个数据的数组下标
        $data_start = ($currentPage - 1) * $page;
        $count = $totalData;
        //判断是否最后一页
        if($currentPage == $totalPage){
            $page = $totalData-(($currentPage-1)*$page);
        }
        for($i=0;$i<$page;$i++){

            $data[$i] = $return['data'][$data_start++];
         
        }
        //显示按钮
        $html = array();
        for($j=1;$j<=$totalPage;$j++){

            if($_GET['currPage']==$j){
                $html[$j]="<span>{$j}</span>";
            }else{
                $html[$j]="<a href='{$url}{$j}'>{$j}</a>";
            }
        }
        if($_GET['currPage']!=1){
            $prev=$_GET['currPage']-1;
            array_unshift($html,"<a href='{$url}{$prev}'>« 上一页</a>");
        }
        if($_GET['currPage']!=$totalPage){
            $next=$_GET['currPage']+1;
            array_push($html,"<a href='{$url}{$next}'>下一页 »</a>");
        }
        // p($data);
        $courseLecturer = $data;
      
        // 右侧推荐案例
        $position = $this->getPosition(['mc_assignToSalon' => 1]);

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
          $retuen_isReview =  $this->curl->curl_action('user-api/user-is-reivew', $data ); 
         $isReview = $retuen_isReview['data'];
        
           $data = array();
        $page = 2   ;// 每页显示的数据条数
        $totalData = count($return['data']);
        $totalPage = ceil($totalData/$page); //总分页数
        $currentPage = (int)$_GET['currPage'];
       
        if(empty(($currentPage))){
                  $currentPage = 1;
        }
        //截取url
        $t = $_SERVER['REQUEST_URI'];
        $urlSub = strpos($t,'currPage');
        $url = substr($t,0,$urlSub+9); 
        
        //每页显示的第一个数据的数组下标
        $data_start = ($currentPage - 1) * $page;
        $count = $totalData;
        //判断是否最后一页
        if($currentPage == $totalPage){
            $page = $totalData-(($currentPage-1)*$page);
        }
        for($i=0;$i<$page;$i++){

            $data[$i] = $return['data'][$data_start++];
         
        }
        //显示按钮
        $html = array();
        for($j=1;$j<=$totalPage;$j++){

            if($_GET['currPage']==$j){
                $html[$j]="<span>{$j}</span>";
            }else{
                $html[$j]="<a href='{$url}{$j}'>{$j}</a>";
            }
        }
        if($_GET['currPage']!=1){
            $prev=$_GET['currPage']-1;
            array_unshift($html,"<a href='{$url}{$prev}'>« 上一页</a>");
        }
        if($_GET['currPage']!=$totalPage){
            $next=$_GET['currPage']+1;
            array_push($html,"<a href='{$url}{$next}'>下一页 »</a>");
        }
        // p($data);
        $courseLecturer = $data;
      
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
          
         $return = $this->curl->curl_action('user-api/get-case-submit-detail-by-id',$data);
       
         $courseLecturer = $return['data'];
        // 右侧推荐案例
         // p($courseLecturer);
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
        
         $courseLecturer = $return['data']['caseSumbit'];
        
        // p($return['data']['advice']);
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
         $courseLecturer = $return['data']['caseSumbit'];
         

          include template("api","case_update");
    }
}

?>
