<?php 
pc_base::load_app_class('api', 'api');
class summit  extends api{
	public function summit2015() {
		$historyCases = $this->getHistoryCases();
		$historyVideos = [];
		include template('api', 'summit2015');
	}

	private function getHistoryCases(){
		$model = pc_base::load_model('case_history_model');
		$row = $model->getCases();

		$courseids = '';
		if (!empty($row)){
			foreach($row as $v){
				$courseids .= $v['courseid'].',';
			}
		}

		$params = [
			'mm' => 'kecheng',
			'ms' => 'mc_thumbs,mc_title',
			'mw' => ' mc_courseid  in ( '.rtrim($courseids, ',').')',
		];
		$courses  = $this->curl->curl_action('api/index', $params);
		return $courses;
	}
	private function getHistoryVideos(){
		$model = pc_base::load_model('case_history_model');
		$row = $model->getCases(21);
		if (!$row) {
			$courseids = '';
			foreach($row as $v) {
				$courseids .= $courseids? ','.$v['caseid'] : $v['caseid'];
			}
			$courseids = '1,2,3,4,5,6,7,8,9,10';
			$params = [
				'mm' => 'kecheng',
				'ms' => 'mc_auditionvideo,mc_title,mc_thumbs',
				'mw' => ' mc_courseid in ('.$courseids.') AND mc_auditionvideo !="" ',
			];
		}
		$courses  = $this->curl->curl_action('api/index', $params);
		return $courses;
	}
}

?>