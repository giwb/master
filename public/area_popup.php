<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>한국여행 :: 주소 검색</title>
<? 
  $ADDR['inputYn'] = $_POST['inputYn'];
  $ADDR['roadFullAddr'] = $_POST['roadFullAddr'];
  $ADDR['roadAddrPart1'] = $_POST['roadAddrPart1'];
  $ADDR['roadAddrPart2'] = $_POST['roadAddrPart2'];
  $ADDR['engAddr'] = $_POST['engAddr'];
  $ADDR['jibunAddr'] = $_POST['jibunAddr'];
  $ADDR['zipNo'] = $_POST['zipNo'];
  $ADDR['addrDetail'] = $_POST['addrDetail'];
  $ADDR['admCd'] = $_POST['admCd'];
  $ADDR['rnMgtSn'] = $_POST['rnMgtSn'];
  $ADDR['bdMgtSn'] = $_POST['bdMgtSn'];
  $ADDR['detBdNmList'] = $_POST['detBdNmList'];
  //** 2017년 2월 제공항목 추가 **/
  $ADDR['bdNm'] = $_POST['bdNm'];
  $ADDR['bdKdcd'] = $_POST['bdKdcd'];
  $ADDR['siNm'] = $_POST['siNm'];
  $ADDR['sggNm'] = $_POST['sggNm'];
  $ADDR['emdNm'] = $_POST['emdNm'];
  $ADDR['liNm'] = $_POST['liNm'];
  $ADDR['rn'] = $_POST['rn'];
  $ADDR['udrtYn'] = $_POST['udrtYn'];
  $ADDR['buldMnnm'] = $_POST['buldMnnm'];
  $ADDR['buldSlno'] = $_POST['buldSlno'];
  $ADDR['mtYn'] = $_POST['mtYn'];
  $ADDR['lnbrMnnm'] = $_POST['lnbrMnnm']; 
  $ADDR['lnbrSlno'] = $_POST['lnbrSlno'];
  //** 2017년 3월 제공항목 추가 **/
  $ADDR['emdNo'] = $_POST['emdNo'];
?>
</head>
<script language="javascript">
document.domain = "tripkorea.net";

function init(){
  var url = location.href;
  var confmKey = "U01TX0FVVEgyMDE5MDcyNzEyMTQwMDEwODkxMjc=";
  var resultType = "1";
  var inputYn= "<?=$ADDR['inputYn']?>";
  if(inputYn != "Y"){
    document.form.confmKey.value = confmKey;
    document.form.returnUrl.value = url;
    document.form.resultType.value = resultType;
    document.form.action = "http://www.juso.go.kr/addrlink/addrLinkUrl.do";
    document.form.submit();
  }else{
    opener.jusoCallBack("<?=$ADDR[roadFullAddr]?>","<?=$ADDR[roadAddrPart1]?>","<?=$ADDR[addrDetail]?>","<?=$ADDR[roadAddrPart2]?>","<?=$ADDR[engAddr]?>","<?=$ADDR[jibunAddr]?>","<?=$ADDR[zipNo]?>", "<?=$ADDR[admCd]?>", "<?=$ADDR[rnMgtSn]?>", "<?=$ADDR[bdMgtSn]?>", "<?=$ADDR[detBdNmList]?>", "<?=$ADDR[bdNm]?>", "<?=$ADDR[bdKdcd]?>", "<?=$ADDR[siNm]?>", "<?=$ADDR[sggNm]?>", "<?=$ADDR[emdNm]?>", "<?=$ADDR[liNm]?>", "<?=$ADDR[rn]?>", "<?=$ADDR[udrtYn]?>", "<?=$ADDR[buldMnnm]?>", "<?=$ADDR[buldSlno]?>", "<?=$ADDR[mtYn]?>", "<?=$ADDR[lnbrMnnm]?>", "<?=$ADDR[lnbrSlno]?>", "<?=$ADDR[emdNo]?>");
    window.close();
  }
}
</script>
<body onload="init();">
  <form id="form" name="form" method="post">
    <input type="hidden" id="confmKey" name="confmKey" value="">
    <input type="hidden" id="returnUrl" name="returnUrl" value="">
    <input type="hidden" id="resultType" name="resultType" value="">
  </form>
</body>
</html>
