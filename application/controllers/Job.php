<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 일괄처리 클래스
class Job extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->helper(array('url', 'my_array_helper'));
    $this->load->model(array('admin_model', 'area_model'));
  }

  // 중기예보 가져오기
  public function weather()
  {
    $search['status'] = array(STATUS_ABLE, STATUS_CONFIRM);
    $listNotice = $this->admin_model->listNotice($search);

    // 일단 기존의 데이터는 비우고 시작
    $this->area_model->emptyWeather();

    foreach ($listNotice as $value) {
      $area = unserialize($value['area_sido']);
      if (!empty($area[0])) {
        $areaData = $this->area_model->getName($area[0]);
        $term = date('j', (strtotime($value['startdate']) - time())); // 몇일 후 산행인지 계산

        if ($term > 3) {
          // 3일 이상이면 중기 예보
          $ch = curl_init();
          $url = 'http://apis.data.go.kr/1360000/MidFcstInfoService/getMidLandFcst';
          $queryParams = '?' . urlencode('ServiceKey') . '=iHR7b704p3vkJWgFxtQrNmCyfoZHDUtGKTqndQbYqQxRucqeeDVXSSXv%2F96BeDI9nl5VDuayjpYaP30Q73GouA%3D%3D';
          $queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1');
          $queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('1');
          $queryParams .= '&' . urlencode('dataType') . '=' . urlencode('JSON');
          $queryParams .= '&' . urlencode('regId') . '=' . urlencode($areaData['regid']);
          $queryParams .= '&' . urlencode('tmFc') . '=' . urlencode(date('Ymd') . '0600');

          curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HEADER, FALSE);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
          $response = curl_exec($ch);
          curl_close($ch);

          $insertValues['notice_idx'] = $value['idx'];
          $insertValues['json_data'] = $response;

          $this->area_model->insertWeather($insertValues);
        } else {
          // 3일 이내면 단기 예보
          $ch = curl_init();
          $url = 'http://apis.data.go.kr/1360000/VilageFcstInfoService/getVilageFcst';
          $queryParams = '?' . urlencode('ServiceKey') . '=iHR7b704p3vkJWgFxtQrNmCyfoZHDUtGKTqndQbYqQxRucqeeDVXSSXv%2F96BeDI9nl5VDuayjpYaP30Q73GouA%3D%3D';
          $queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1');
          $queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('1000');
          $queryParams .= '&' . urlencode('dataType') . '=' . urlencode('JSON');
          $queryParams .= '&' . urlencode('base_date') . '=' . urlencode(date('Ymd'));
          $queryParams .= '&' . urlencode('base_time') . '=' . urlencode('0500');
          $queryParams .= '&' . urlencode('nx') . '=' . urlencode($areaData['nx']);
          $queryParams .= '&' . urlencode('ny') . '=' . urlencode($areaData['ny']);

          curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
          curl_setopt($ch, CURLOPT_HEADER, FALSE);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
          $response = curl_exec($ch);
          curl_close($ch);

          $insertValues['notice_idx'] = $value['idx'];
          $insertValues['json_data'] = $response;

          $this->area_model->insertWeather($insertValues);
        }
      }
    }
  }
}
?>