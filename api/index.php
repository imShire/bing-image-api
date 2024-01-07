<?php
date_default_timezone_set('Asia/Shanghai');

$rand = $_GET['rand'] ?? 'false';
$gettime = $rand === 'true' ? rand(-1,7) : ($_GET['day'] ?? 0);

$getformat = $_GET['format'] ?? "jpg";

$json_string = file_get_contents('https://cn.bing.com/HPImageArchive.aspx?format=js&idx='.$gettime.'&n=1');
$data = json_decode($json_string);

$imgurlbase = "https://cn.bing.com".$data->{"images"}[0]->{"urlbase"};

$imgsize = $_GET['size'] ?? "1920x1080";

$imgurl = $imgurlbase."_".$imgsize.".".$getformat."";

$imgtime = $data->{"images"}[0]->{"startdate"};
$imgtitle = $data->{"images"}[0]->{"copyright"};
$imglink = $data->{"images"}[0]->{"copyrightlink"};

$info = $_GET['info'] ?? 'false';
if ($info === 'true') {
  echo "{title:".$imgtitle.",url:".$imgurl.",link:".$imglink.",time:".$imgtime."}";
}else{
  header("Location: $imgurl");
}
?>
