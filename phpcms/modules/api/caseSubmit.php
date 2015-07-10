<?php
pc_base::load_app_class('api', 'api');
class caseSubmit  extends api{
    public $curl = '';
    public $pageSize;
    public function __construct(){
        $this->curl = new curl();
        $this->pageSize = 20;

    }

    public function init(){

        $userid =  param::get_cookie('_userid');

        if(empty($userid)){

            header("Location:/index.php?m=member&c=index&a=login&comeform=caseSubmit");
        }else{
            include template('api', 'caseSubmit');
        }
    }

    public function caseSubmitData()
    {
        $userid =  param::get_cookie('_userid');
        $_POST['anlitijiao']['mcs_userid'] = $userid;
        //这个是分享者的照片
        $tmp_name =$_FILES['mcs_lecturerThumbs']['tmp_name'];  // 文件上传后得临时文件名
        $name     =$_FILES['mcs_lecturerThumbs']['name'];     // 被上传文件的名称
        $size     =$_FILES['mcs_lecturerThumbs']['size'];    //  被上传文件的大小
        $type     =$_FILES['mcs_lecturerThumbs']['type'];   // 被上传文件的类型
        $dir      = 'uploadfile/'.date("Y").'/'.date('md');
        @chmod($dir,0777);//赋予权限
        @is_dir($dir) or mkdir($dir,0777);
        move_uploaded_file($_FILES['mcs_lecturerThumbs']['tmp_name'],$dir."/".$name);
        $type = explode(".",$name);
        $type = @$type[1];
        $date   = date("YmdHis");
        $rename = @rename($dir."/".$name,$dir."/".$date.".".$type);
        if($rename)
        {
         $_POST['anlitijiao']['mcs_lecturerThumbs'] = $dir."/".$date.".".$type;
        }
        //公司  logo 照片 mcs_companyThumbs

        $tmp_name =$_FILES['mcs_companyThumbs']['tmp_name'];  // 文件上传后得临时文件名
        $name     =$_FILES['mcs_companyThumbs']['name'];     // 被上传文件的名称
        $size     =$_FILES['mcs_companyThumbs']['size'];    //  被上传文件的大小
        $type     =$_FILES['mcs_companyThumbs']['type'];   // 被上传文件的类型
        $dir      = 'uploadfile/'.date("Y").'/'.date('md');
        @chmod($dir,0777);//赋予权限
        @is_dir($dir) or mkdir($dir,0777);
        move_uploaded_file($_FILES['mcs_companyThumbs']['tmp_name'],$dir."/".$name);
        $type = explode(".",$name);
        $type = @$type[1];
        $date   = date("YmdHis");
        $rename = @rename($dir."/".$name,$dir."/".$date.".".$type);
        if($rename)
        {
         $_POST['anlitijiao']['mcs_companyThumbs'] = $dir."/".$date.".".$type;
        }


        $return = $this->curl->curl_action('user-api/case-submit', ['anlitijiao'=>$_POST['anlitijiao']]);
       showmessage('案例提交成功','http://localhost/index.php?m=api&c=myCaseList');
    }
    
    //文件上传的方法
    public  function Upload($uploaddir)
    {
        echo 111;
        $tmp_name =$_FILES['file']['tmp_name'];  // 文件上传后得临时文件名
        $name     =$_FILES['file']['name'];     // 被上传文件的名称
        $size     =$_FILES['file']['size'];    //  被上传文件的大小
        $type     =$_FILES['file']['type'];   // 被上传文件的类型
        $dir      = $uploaddir.date("Ym");
        @chmod($dir,0777);//赋予权限
        @is_dir($dir) or mkdir($dir,0777);
        //chmod($dir,0777);//赋予权限
        move_uploaded_file($_FILES['file']['tmp_name'],$dir."/".$name);
        $type = explode(".",$name);
        $type = @$type[1];
        $date   = date("YmdHis");
        $rename = @rename($dir."/".$name,$dir."/".$date.".".$type);
        if($rename)
        {
        return $dir."/".$date.".".$type;
        }
    }
}
?>
