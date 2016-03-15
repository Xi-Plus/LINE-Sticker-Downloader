<?php
ini_set("display_errors",1);
require("config.php");
$url="https://store.line.me/stickershop/product/".$config["sticker_id"]."/zh-Hant";
$cont=file_get_contents($url);
$cont=str_replace("\n","",$cont);
if(preg_match('/<h3 class="mdCMN08Ttl">(.*?)<\/h3>/',$cont,$temp))$name=trim($temp[1]);
else if(preg_match('/mdMN05Img.*?<h2>(.*?)<\/h2>.*?mdMN05Txt/',$cont,$temp))$name=trim($temp[1]);
$download_url=array();
if($config["type"]==1){
	$start=strpos($cont,'<ul class="mdCMN09Ul FnSticker_animation_list_img">');
	$end  =strpos($cont,'<p class="mdCMN09Copy"');
	$html =substr($cont,$start,$end-$start);
	preg_match_all('/background-image:url\((.*?)\);/',$html,$temp);
	$download_url=$temp[1];
}else if($config["type"]==2){
	for($id=$config["photo_id"];$id<$config["photo_id"]+$config["photo_count"];$id++){
		$download_url[]="https://sdl-stickershop.line.naver.jp/products/0/0/1/".$config["sticker_id"]."/android/stickers/".$id.".png";
	}
}else {
	exit("type error.");
}
$folder="downloads/".$config["sticker_id"]." ".$name;
mkdir($folder);
$all=count($download_url);
foreach($download_url as $count => $url){
	echo "Download ".(++$count)." / ".$all."\n";
	$photo=file_get_contents($url);
	file_put_contents($folder."/".basename($url),$photo);
}
?>
