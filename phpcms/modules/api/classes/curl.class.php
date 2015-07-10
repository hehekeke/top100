<?php 
class curl{
	public $baseUrl = 'localhost:81/admin.php/';
	public function curl_action($url, $params, $type = 'get'){
		$url = $this->parseUrl($url, $params);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		// echo $url;exit;
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_REFERER, 'www.top100summit.com');
		$row = curl_exec($ch);
		curl_close($ch);
		// p($row);
		return $this->formatCurlOut($row);
	} 
	public function post($url, $params){

		$url = $this->parseUrl($url);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$row = curl_exec($ch);
		curl_close($ch);
		return $this->formatCurlOut($row);

	}
	public function test($url, $params){
		echo $this->parseUrl($url, $params);exit;
	}
	private function parseUrl($url, array $request = array()){
		if (empty($request)) {

			return rtrim($this->baseUrl, '/').'/'.$url;
		}
		return $this->baseUrl.$url.'?data='.json_encode($request);
	}
	public function formatCurlOut($data){
		if (substr(trim($data),0,1) != '(') {
			return $data;
		}
		return json_decode(rtrim(ltrim(trim($data),'('),')'), true);
	}
}

?>