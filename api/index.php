<?php
date_default_timezone_set('Asia/Shanghai');

$rand = $_GET['rand'] ?? 'false';
$gettime = $rand === 'true' ? rand(-1,7) : ($_GET['day'] ?? 0);

$getformat = $_GET['format'] ?? "jpg";

$allowed_sizes = ["3840x2160", "4596x2583", "1920x1080", "1366x768", "1280x768", "1024x768", "800x600", "800x480", "768x1280", "720x1280", "640x480", "480x800", "400x240", "320x240", "240x320"];
$imgsize = $_GET['size'] ?? "3840x2160";
if (!in_array($imgsize, $allowed_sizes)) {
  $imgsize = "3840x2160";
}

list($uhdwidth, $uhdheight) = explode('x', $imgsize);

$opts = array(
  'http' => array(
    'method' => "GET",
    'header' => "Cookie: _EDGE_S=mkt=zh-CN"
  )
);

$context = stream_context_create($opts);

$json_string = file_get_contents('https://www.bing.com/HPImageArchive.aspx?format=js&idx='.$gettime.'&n=1&uhd=1&uhdwidth='.$uhdwidth.'&uhdheight='.$uhdheight.'&mkt=zh-CN', false, $context);
$data = json_decode($json_string);

$imgurl = "https://www.bing.com".$data->{"images"}[0]->{"url"};

$imgtime = $data->{"images"}[0]->{"startdate"};
$imgtitle = $data->{"images"}[0]->{"title"};
$imgcopyright = $data->{"images"}[0]->{"copyright"};
$imglink = $data->{"images"}[0]->{"copyrightlink"};

$keyword = explode(", ", $imgcopyright)[0];
$keyword = preg_replace('/[^A-Za-z0-9\-]/', '', $keyword);

$filename = "bing_".$imgtime."_".$keyword.".jpg";

$info = $_GET['info'] ?? 'false';
if ($info === 'true') {
  $output = array(
    "url" => $imgurl,
    "date" => $imgtime,
    "title" => $imgtitle,
    "copyright" => $imgcopyright,
    "keyword" => $keyword,
    "filename" => $filename
  );
  echo json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
} else {
  header("Location: $imgurl");
}
?>
