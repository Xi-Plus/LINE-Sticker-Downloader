<?php
$sticker_id = $_SERVER["argv"][1];
if (!isset($sticker_id)) {
	exit("no sticker id");
} else if (preg_match("/^\d+$/", $sticker_id) != 1) {
	exit("sticker id not a number");
}
$url  = "http://store.line.me/stickershop/product/".$sticker_id."/zh-Hant";
$cont = file_get_contents($url);
$cont = str_replace("\n", "", $cont);
if (preg_match('/<h3 class="mdCMN08Ttl">(.*?)<\/h3>/', $cont, $temp)) $name = trim($temp[1]);
else if (preg_match('/mdMN05Img.*?<h2>(.*?)<\/h2>.*?mdMN05Txt/', $cont, $temp)) $name = trim($temp[1]);
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') $name = iconv("UTF-8", "BIG5//IGNORE", $name);
$download_url = array();
if (!isset($_SERVER["argv"][2])) {
	$start = strpos($cont, '<ul class="mdCMN09Ul FnSticker_animation_list_img">');
	$end   = strpos($cont, '<p class="mdCMN09Copy"');
	$html  = substr($cont, $start, $end-$start);
	preg_match_all('/background-image:url\((.*?)\);/', $html, $temp);
	$download_url = $temp[1];
} else {
	if (preg_match("/^(\d+)-(\d+)$/", $_SERVER["argv"][2], $m) == 1) {
		$startid = $m[1];
		$endid   = $m[2];
	} else {
		exit("photo id wrong format");
	}
	for($id = $startid; $id <= $endid; $id++){
		$download_url[] = "http://sdl-stickershop.line.naver.jp/products/0/0/1/".$sticker_id."/android/stickers/".$id.".png";
	}
}
@mkdir("downloads");
$folder = "downloads/".$sticker_id." ".$name;
@mkdir($folder);
$all = count($download_url);
foreach ($download_url as $count => $url){
	echo "Downloading ".(++$count)." / ".$all."\n";
	$photo = file_get_contents($url);
	file_put_contents($folder."/".basename($url),$photo);
}
?>
