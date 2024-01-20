<?php
date_default_timezone_set('Asia/Shanghai');

$rand = $_GET['rand'] ?? 'false';
$gettime = $rand === 'true' ? rand(-1,7) : ($_GET['day'] ?? 0);

$getformat = $_GET['format'] ?? "jpg";

// List of allowed sizes
$allowed_sizes = [
  "3840x2160",
  "4596x2583",
  "1920x1080",
  "1366x768",
  "1280x768",
  "1024x768",
  "800x600",
  "800x480",
  "768x1280",
  "720x1280",
  "640x480",
  "480x800",
  "400x240",
  "320x240",
  "240x320"
];

$imgsize = $_GET['size'] ?? "3840x2160";

// Check if the provided size is allowed
if (!in_array($imgsize, $allowed_sizes)) {
  $imgsize = "3840x2160";
}

list($uhdwidth, $uhdheight) = explode('x', $imgsize);

$json_string = file_get_contents('https://cn.bing.com/HPImageArchive.aspx?format=js&idx='.$gettime.'&n=1&uhd=1&uhdwidth='.$uhdwidth.'&uhdheight='.$uhdheight);
$data = json_decode($json_string);
// echo $json_string;

// https://www.bing.com/th?id=OHR.ParisBridge_EN-US1771484789_1920x1080.jpg
$imgurlbase = "https://cn.bing.com".$data->{"images"}[0]->{"urlbase"};
$imgurl = $imgurlbase."_".$imgsize.".".$getformat."";

// https://www.bing.com/th?id=OHR.PlitviceWinter_EN-US1870468945_UHD.jpg&rf=LaDigue_UHD.jpg&pid=hp&w=3840&h=2160&rs=1&c=4
// $imgurl = "https://www.bing.com".$data->{"images"}[0]->{"url"};

// echo $imgurl;
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
