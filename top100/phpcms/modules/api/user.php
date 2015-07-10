<?php 
/**
 *
 * @link   www.msup.com.cn
 * @author stromKnight <410345759@qq.com>
 * @since  1.0 
 */

// defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('api', 'api');
pc_base::load_sys_class('param');
class user extends api{

	public function __construct(){
		parent::__construct();
		$this->_session_start();
			// if(!empty($_POST)) {
			// 	if ( (empty($_SESSION['connectid']) && $_SESSION['code'] != strtolower($_POST['code']) && $_POST['code']!==NULL) || empty($_SESSION['code']) ) {
			// 		showmessage(L('code_error'));
			// 	} else {
			// 		$_SESSION['code'] = '';
			// 	}
			// }
	}
	public function logout() {
		param::set_cookie('_userid', '');
		param::set_cookie('_username', '');
		showmessage('成功退出登录', 'index.php');
	}
	public function login() {
		if ( empty($_POST['huiyuan']['mu_userName']) ) {
			showmessage('用户名不能为空');
		} else if(empty($_POST['huiyuan']['mu_password'])){
			showmessage('密码不能为空');
		}
		//  检查验证码
		if ((empty($_SESSION['connectid']) && $_SESSION['code'] != strtolower($_POST['code']) && $_POST['code']!==NULL) || empty($_SESSION['code'])) {
			showmessage(L('code_error'));
		} else {
			$_SESSION['code'] = '';
		}

		$return = $this->curl->curl_action('user-api/login',   ['huiyuan'=>$_POST['huiyuan']]);
		if($return['errno']  == 0) {

				$cookietime = SYS_TIME+114400;
				param::set_cookie('_userid', $return['data']['userId'], $cookietime);
				param::set_cookie('_username', $return['data']['_username'], $cookietime);
               if(empty($_POST['comeform'])){
                   showmessage('登录成功','index.php');
               }else{
                   showmessage('登录成功','index.php?m=api&c=caseSubmit');
               }

		} else {
			showmessage($return['errmsg']);
		}
		include template('member', 'login');

	}

	// 注册
	public function register() {
		$attributes = $this->attributeLabels();
		
		if ($_POST['doSubmit']) {
			// 检查验证码
			if ((empty($_SESSION['connectid']) && $_SESSION['code'] != strtolower($_POST['code']) && $_POST['code']!==NULL) || empty($_SESSION['code'])) {
				showmessage(L('code_error'));
			} else {
				$_SESSION['code'] = '';
			}
			$memberInfo = array_merge($_POST['huiyuan'],$_POST['huiyuanxinxi']);
			$return = $this->registerMember($memberInfo);
			if (!$return['errno']) {
				$get_cookietime = param::get_cookie('cookietime');
				$_cookietime =  $get_cookietime ? $get_cookietime : 0;
				$cookietime = $_cookietime ? SYS_TIME + $_cookietime : 0;
				param::set_cookie('_userid', $return['data']['id'], $cookietime);
				param::set_cookie('_username', $return['data']['username'], $cookietime);
				showmessage('注册成功，请登录', '/index.php?m=member&c=index&a=login');
			} else {
				showmessage($return['errmsg']);
			}
		} else {
			include template("member", "register");
		}
	}

	/**
	 * 注册会员
	 * @param  array  $memberInfo [description]
	 * @return [type]             [description]
	 */
	public function registerMember(array $memberInfo) {
		$attributes = $this->attributeLabels();
		$postInfo = [ 'huiyuan' => [], 'huiyuanxinxi' => [] ];
		// 检查错误信息
		foreach($attributes as $attribute => $label) {
			
			if (empty($memberInfo[$attribute])) {
				$return['errmsg'] = $label.'不能为空';
				$return['errno'] = 1;
				showmessage($label.'不能为空');
			} else {
				$pos = substr($attribute,0,strpos($attribute, '_'));
				switch($pos) {
					case 'mu':
						$postInfokey = 'huiyuan';
					break;
					case 'mmi':
						$postInfokey = 'huiyuanxinxi';
					break;
					default:
					break;
				}
				$postInfo[$postInfokey][$attribute] = $memberInfo[$attribute];
			}

		}
		$return = $this->curl->curl_action('user-api/register',$postInfo);
		return $return;
	}
	private function attributeLabels($attribute = null){
		$attributeLabels = [
			'mu_phone' => '手机号',
			'mu_email' => '邮箱',
			'mu_password' => '登录密码',
			'mmi_name' => '姓名',
			'mmi_company' => '所在公司',
			'mmi_position' => '所在职位',
		];
		if ($attribute && in_array($attribute, array_keys($attributeLabels))){
			return $attributeLabels[$attribute];
		} else {
			return $attributeLabels;
		}

	}
	private function _session_start() {
		$session_storage = 'session_'.pc_base::load_config('system','session_storage');
		pc_base::load_sys_class($session_storage);
	}
}	


?>