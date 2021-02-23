<?php
require('skill.php');
function logger($vv) {
   $ho = $_SERVER['SERVER_NAME']."-".date('Y-m');
   $ho = date('Y-m-d');
   $ip = str_pad($_SERVER['REMOTE_ADDR'],15);
   $fp = fopen("C:/Apache24/htdocs/004/logs/app-$ho.log", 'a+');
   fwrite($fp, date('Y-m-d G:i:s').'  '.$ip.' '.$vv."\n");
   fclose($fp);
  }
  
class player
{
    // property declaration
    public $hp = 7500;
	public $atk = 300;
	public $name = '';
	public $block = 15;
	public $crit = 20;
    // method declaration
    public function attack() {
        $result = '對enemy造成300點傷害！';
		return $result;
    }
}
  
  
  
function showhelp() {
	global $client,$event;
    $client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => '目前有的指令如下:
!+你要的關鍵字 EX !吃啥
抽卡*機率*次數 EX抽卡*3%*300 注意 機率只有3%以及6%
學;關鍵字;回答 EX 學;kip;會吃屎\
清單;關鍵字  EX 清單;kip
刪除;編號  EX 刪除;64   備註:編號可以在清單查到
天氣;城市 EX天氣;台北市  備註:國外不行'
                   )
                )
                 ));
               	   	

	           
               }
function echom ($skey) {
			global $client,$event,$con;
			$x = luis($skey);
            $y = json_decode($x, true);
			$vv3 = $y['topScoringIntent'];
            $vv4 = $vv3['intent'];
            $vv5 = $vv3['score'];
            $results = $con->query("select * from echo_list where UPPER(keyword) = UPPER('$skey')");
			if ($vv4 == '食物' and $vv5 > 0.5) $results = $con->query("select * from echo_list where UPPER(category) = UPPER('$vv4')");
			$result = array();
			while ($row = $results->fetchArray(SQLITE3_ASSOC)){
			      $keyword=$row['keyword'];
 				  $content=$row['content'];
				  $img=$row['img'];
 				  $tt = array($keyword,$content,$img) ;
  		  	array_push($result,$tt);
				}
            $count = count($result);
			$count = $count-1;
            $r = rand(0,$count);
			
            $type   = $result[$r][1];
            logger($result[$r][1]);
/*				$row = $results->fetchArray(SQLITE3_ASSOC);*/
            if($type=== '圖片'){
                    $client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'image', // 訊息類型 (圖片)
                'originalContentUrl' => $result[$r][2], // 回復圖片
                'previewImageUrl' => $result[$r][2] // 回復的預覽圖片
                   )
                )
                 ));
               }else
               {
               	$client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => $type
                   )
                )
                 ));
               	}   	

	           
               }


function prediction() {
			global $client,$event;
			$json = file_get_contents("https://script.google.com/macros/s/AKfycbw7_1wEvT-5Co-LoRjcozJhRFUnApa01lskpRLpkPyD7pbkQ2Gx/exec");
            $data = json_decode($json, true);
            $result = array();
      //      $findex=fopen("last.txt","w") ; // 寫上
      //  	  fwrite($findex,$skey); 
            foreach ($data as $item) {
                    if ($item[1] != '' ) {
                        $candidate = array(
                        $item[1],
                        $item[2],
						$item[3],
						$item[4],
						$item[5],
                        );
                        array_push($result, $candidate);
                    }
            }
               	$client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => $result[0][0].'
'.$result[1][0].' '.$result[1][1].' '.$result[1][2].' '.$result[1][3].' '.$result[1][4].' '.$result[1][5].'
'.$result[2][0].' '.$result[2][1].' '.$result[2][2].' '.$result[2][3].' '.$result[2][4].' '.$result[2][5].'
'.$result[3][0].' '.$result[3][1].' '.$result[3][2].' '.$result[3][3].' '.$result[3][4].' '.$result[3][5].'
'.$result[4][0].' '.$result[4][1].' '.$result[4][2].' '.$result[4][3].' '.$result[4][4].' '.$result[4][5].'
'.$result[5][0].' '.$result[5][1].' '.$result[5][2].' '.$result[5][3].' '.$result[5][4].' '.$result[5][5].'
'.$result[6][0].' '.$result[6][1].' '.$result[1][2].' '.$result[1][3].' '.$result[1][4].' '.$result[1][5].'
資料來源是傳說中的那個騎空團',

                   )
                )
                 ));
               	   	

	           
               }

function luis($skey) {
			global $client,$event;
			$skey2 = rawurlencode($skey);
			$tt = 'https://westus.api.cognitive.microsoft.com/luis/v2.0/apps/89d84289-3563-4f99-87e9-6655a7cf5092?verbose=true&timezoneOffset=0&subscription-key=c969a9085d2644b0a25a32d1bd0e582c&q='.$skey2;
			$curl = curl_init(); // 啟動一個CURL會話
			curl_setopt($curl, CURLOPT_URL, $tt);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳過證書檢查
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 從證書中檢查SSL加密算法是否存在
			$x = curl_exec($curl);
			curl_close($curl);
            return $x;    	   		           
}

function trash($mtext) {
			global $client,$event;
			$array=explode(';',$mtext);
			$topic = $array[1];
			$len = $array[2];
			$len=intval($len); 
			if($topic == '' or $len == '' ) error('輸入錯誤 請遵照格式 廢;關鍵字;字數');
			$data = array(
			"Topic" => $topic,
			"MinLen" => $len
						 );
			$postdata = json_encode($data);
			$tt = 'https://api.howtobullshit.me/bullshit';
			$curl = curl_init(); // 啟動一個CURL會話
			curl_setopt($curl, CURLOPT_URL, $tt);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //結果轉為變數
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳過證書檢查
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 從證書中檢查SSL加密算法是否存在
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($curl, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
				'charset=utf-8'
				]);
			$x = curl_exec($curl);

			curl_close($curl);
			$x = str_replace("&nbsp;", "", $x);
			$x = str_replace("<br>", "", $x);
            $client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => $x
                   )
                )
                 ));	           
}


function getdisplayname(){ //取得群組內的displayname
			  global $client,$event,$groupid,$user,$channelAccessToken;
			  $tt = 'https://api.line.me/v2/bot/group/'.$groupid.'/member/'.$user;
			  $curl = curl_init(); // 啟動一個CURL會話
			  curl_setopt($curl, CURLOPT_URL, $tt);
			  curl_setopt($curl, CURLOPT_HTTPGET, 1);
			  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //結果轉為變數
			  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳過證書檢查
			  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);  // 從證書中檢查SSL加密算法是否存在
			  curl_setopt($curl, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
				'Authorization: Bearer '.$channelAccessToken
				]);
		      $x = curl_exec($curl);

			 curl_close($curl);
             $y = json_decode($x);
             $vv3 = $y->displayName;  
			 return $vv3;
}



function weather($mtext) {  //只取第一筆資料
			global $client,$event;
			$array=explode('*',$mtext);
			$keyword = $array[1];
			if($keyword  === '台北市') $keyword = '臺北市';
			$json = file_get_contents("https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-BCC30670-309F-4B91-8262-085A8DF0E6A0&locationName=".$keyword);
            $data = json_decode($json, true);
			$wheatercode = $data['records']['location'][0]['weatherElement'][0]['time'][0]['parameter']['parameterValue']; // 1= sunny  2~7 =cloudy	else rain default
			$wheater = $data['records']['location'][0]['weatherElement'][0]['time'][0]['parameter']['parameterName']; // 1= sunny  2~7 =cloudy	else rain default
			$rain = $data['records']['location'][0]['weatherElement'][1]['time'][0]['parameter']['parameterName']; // 降雨機率
			$mint = $data['records']['location'][0]['weatherElement'][2]['time'][0]['parameter']['parameterName']; // 最小溫度
			$ci = $data['records']['location'][0]['weatherElement'][3]['time'][0]['parameter']['parameterName']; // 敘述
			$maxt = $data['records']['location'][0]['weatherElement'][4]['time'][0]['parameter']['parameterName']; // 最big溫度
			$city = $data['records']['location'][0]['locationName']; // city
			$img = 'https://domidomi.tk/004/img/rain.png';
			if($wheatercode === '1') $img = 'https://domidomi.tk/004/img/sunny.png';
			if($wheatercode > 1 and $wheatercode < 8) $img = 'https://domidomi.tk/004/img/suncloudy.png';
			logger($tt);
			$result = array(
  'type'=> 'bubble',
  'direction'=> 'ltr',
  'header'=> array(
    'type'=> 'box',
    'layout'=> 'horizontal',
    'contents'=> array(
      array(
        'type'=> 'text',
        'text'=> '本日天氣',
        'align'=> 'start',
		"contents"=> array(
          array(
            "type"=> "span",
            "text"=> "本日天氣",
            "size"=> "xxl"
          )
        )

      ),
	  array(
        "type"=> "text",
        "text"=> $city ,
        "size"=> "xxl",
        "align"=> "end",
        "contents"=> array(
          array(
            "type"=> "span",
            "text"=> $city ,
            "weight"=> "bold",
            "style"=> "normal"
          )
        )
      )
    )
  ),
  'hero'=> array(
    'type'=> 'image',
    'url'=> $img,
    'margin'=> 'xl',
    'gravity'=> 'top',
    'size'=> 'xxl',
    'aspectRatio'=> '1.51:1',
    'aspectMode'=> 'fit',
    'backgroundColor'=> '#FFFFFFFF'
  ),
  'body'=> array(
    'type'=> 'box',
    'layout'=> 'vertical',
    'contents'=> array(
      array(
        'type'=> 'text',
        'text'=> $wheater.' '.$ci,
        'align'=> 'center',

      )
    )
  ),
  'footer'=> array(
    'type'=> 'box',
    'layout'=> 'vertical',
    'contents'=> array(
      array(
        'type'=> 'text',
        'text'=> $mint.'°C~'.$maxt.'°C',
        'size'=> 'xxl',
        'align'=> 'center',
        'gravity'=> 'center',

      ),
      array(
        'type'=> 'text',
        'text'=> '降雨'.$rain.'%',
        'size'=> '3xl',
		'align'=> 'center',
        'gravity'=> 'center',
      ),
	  array(
        "type"=> "text",
        "text"=> "hello, world",
        "contents"=> array(
          array(
            "type"=> "span",
            "text"=> "資料來源 :"
          ),
          array(
            "type"=> "span",
            "text"=> "中央氣象局",
            "color"=> "#FF6161FF"
          )
        )
      )
    )
  )
);
               	$client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
            array(
                'type' => 'flex', // 訊息類型 (模板)
                'altText' => 'flex', // 替代文字
                'contents' => $result
            )
            
        )
                 ));
               	   	

	           
               }








function learn($mtext) {
			global $client,$event,$con;
			$array=explode(';',$mtext);
			$keyword = $array[1];
			$content = $array[2];
			$x = luis($keyword);
			if($keyword == '' or $content == '' ) error('沒學到，可能是關鍵字或是內容有空白，或是輸入錯誤');
            $y = json_decode($x, true);
			$vv3 = $y['topScoringIntent'];
            $vv4 = $vv3['intent'];
            $vv5 = $vv3['score'];
			$who = getdisplayname();
			if ($content === '圖片'){
				$img = $array[3];
				$con->exec("INSERT INTO echo_list (keyword, content,img,who) VALUES('$keyword', '圖片','$img','$who')");		
				
			}else{
				if ($vv4 == '食物' and $vv5 > 0.5) {
					$results = $con->query("INSERT INTO echo_list (keyword, content,category,who) VALUES('$keyword','$content','$vv4','$who')");
				}else{
					$con->exec("INSERT INTO echo_list (keyword, content,who) VALUES('$keyword','$content','$who')");		
				}
			}		
               	$client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => '學到了  '.$array[1].'
現在支援的新增格式有以下兩個
學;Q;A  EX:學;晚餐;孫東寶牛排
學;Q;圖片;圖片網址  EX:學;哭阿;圖片;https://i.imgur.com/scsdcxxxx.png'

                   )
                )
                 ));
               	   	

	           
               }

function error($mtext) {
			global $client,$event,$con;	
               	$client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => $mtext

                   )
                )
                 ));
             exit();  	   	

	           
               }

			   
function listk($mtext) {
			global $client,$event,$con;
			$array=explode(';',$mtext);
			$keyword = $array[1];
			$results = $con->query("select * from echo_list where UPPER(keyword) = UPPER('$keyword')");
			$result = '';
			while ($row = $results->fetchArray(SQLITE3_ASSOC)){
			      $keyword=$row['keyword'];
 				  $content=$row['content'];
				  $kid=$row['id'];
				  $who=$row['who'];
 				  $tt = array($keyword,$content,$kid) ;
				  $result .= $kid.' '.$keyword.' '.$content.' '.$who.'
';
				}
			
			
			
               	$client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => '關鍵字 '.$keyword.' 結果如下
'.$result.'
可以使用 刪除;編號 來進行刪除指令 EX 刪除;105'
                   )
                )
                 ));
               	   	

	           
               }	
			   
function deletek($mtext) {
			global $client,$event,$con;
			$array=explode(';',$mtext);
			$kid = $array[1];
			if ($kid == '' or is_numeric($kid) != TRUE ) error('刪除請用編號，可以使用清單找到編號');
			$con->exec("DELETE FROM echo_list  WHERE id = '$kid'");
            $client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => 'ok 已經移除編號 '.$kid
                   )
                )
                 ));
               	   	

	           
               }			   
			   
			   
function prize($mtext){
global $client,$event,$con;
$array=explode('*',$mtext);
$whitch = $array[1];
$times = $array[2];
$prize3 = array(
 1 => 4, //specail prize
 2 => 2996, //SSR
 3 => 15000,//SR 
 4 => 82000, 
);
$prize6 = array(
 1 => 18, //specail prize
 2 => 5982, //SSR
 3 => 15000,//SR 
 4 => 79000, 
);
$result = array(
 1 =>0,
 2 =>0,
 3 =>0,
 4 =>0,
);
if ($whitch === '6%') $prize3 = $prize6;
for ( $i=0 ; $i<$times ; $i++ ) {
$chance = 100000;
foreach ($prize3 as $gId => $prizes) { //如沒有在範圍內 則概率空間減去
    $random = mt_rand(1, $chance);
    if ($random <= $prizes) {
        $result[$gId] += 1;
		
        break;
    } else {
        $chance -= $prizes;
    } 
}
}
$sp = strval($result[1]);
$ssr = strval($result[2]);
$sr = strval($result[3]);
$r = strval($result[4]);
$tt = array(
  "type"=> "bubble",
  "direction"=> "ltr",
  "header"=> array(
    "type"=> "box",
    "layout"=> "vertical",
	"height"=> "70px",
    "contents"=> array(
      array(
        "type"=> "text",
        "text"=> "抽卡結果",
        "weight"=> "bold",
        "size"=> "xl",
        "color"=> "#000000FF",
        "align"=> "center",
        "contents"=> array()
      )
    )
  ),
  "body"=> array(
    "type"=> "box",
    "layout"=> "vertical",
	"height"=> "250px",
    "contents"=> array(
      array(
        "type"=> "box",
        "layout"=> "horizontal",
        "contents"=> array(
          array(
            "type"=> "image",
            "url"=> "https://i.imgur.com/zgs011N.png",
			"size"=> "xs",
          ),
          array(
            "type"=> "text",
            "text"=> $sp,
            "size"=> "lg",
            "color"=> "#FF0000FF",
            "align"=> "end",
            "gravity"=> "center",
            "contents"=> array(
              array(
                "type"=> "span",
                "text"=> $sp."顆",
              )
            )
          )
        )
      ),
      array(
        "type"=> "box",
        "layout"=> "horizontal",
        "contents"=> array(
          array(
            "type"=> "image",
            "url"=> "https://i.imgur.com/RxcVOh1.png",
			"size"=> "xs",
          ),
          array(
            "type"=> "text",
            "text"=> $ssr,
            "align"=> "end",
            "gravity"=> "center",
            "contents"=> array(
              array(
                "type"=> "span",
                "text"=> $ssr,
              )
            )
          )
        )
      ),
      array(
        "type"=> "box",
        "layout"=> "horizontal",
        "contents"=> array(
          array(
            "type"=> "image",
            "url"=> "https://i.imgur.com/OqDAdAh.png",
			"size"=> "xs",
          ),
          array(
            "type"=> "text",
            "text"=> $sr,
            "align"=> "end",
            "gravity"=> "center",
            "contents"=> array(
              array(
                "type"=> "span",
                "text"=> $sr,
              )
            )
          )
        )
      ),
      array(
        "type"=> "box",
        "layout"=> "horizontal",
        "contents"=> array(
          array(
            "type"=> "image",
            "url"=> "https://i.imgur.com/m12Itau.png",
			"size"=> "xs",
          ),
          array(
            "type"=> "text",
            "text"=> $r,
            "align"=> "end",
            "gravity"=> "center",
            "contents"=> array(
              array(
                "type"=> "span",
                "text"=> $r,
              )
            )
          )
        )
      )
    )
  ),
  "footer"=> array(
    "type"=> "box",
    "layout"=> "horizontal",
    "contents"=> array(
      array(
        "type"=> "filler"
      )
    )
  )
);

$client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
            array(
                'type' => 'flex', // 訊息類型 (模板)
                'altText' => 'flex', // 替代文字
                'contents' => $tt
            )
            
        )
                 ));

	
}




function ctrlcv ($skey) {
			  global $client,$event;
	          $count=file("last.txt");
	          $a =  $count[0];  //取值
	          if ($a == $skey) echoed ($a);
	   	 	  $findex=fopen("last.txt","w"); // 寫上
              fwrite($findex,$skey);   
               }
               
function echoed ($skey) {
			  global $client,$event;
	          $count=file("echoed.txt");
	          $e =  $count[0];  //取值
	          if ($e != $skey) {
	          	$client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => $skey
                   )
                )
                 ));
	          $findex=fopen("echoed.txt","w") ; // 寫上
            fwrite($findex,$skey);   	
	          	
	          	}
	          exit();           
               }   
function battle ($mtext) {
			  global $client,$event;		
			  $array=explode(';',$mtext);
			  $aname = getdisplayname();
			  $bname = $array[1];
			  $player1 = new player();
			  $player1->name = $aname;
			  $player2 = new player();
			  $player2->name = $bname;
			  $result = duel($player1,$player2);
	          $client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => $result
                   )
                )
                 ));          
               }   

function duel ($player1,$player2) {
		global $con;	
		$result = '戰鬥開始
';
		$results = $con->query("select * from skill ORDER BY active ASC");
		$skill_array = array();
        while ($row = $results->fetchArray(SQLITE3_ASSOC)){
			$skill_name=$row['skill_name'];
			$active=$row['active'];
			$tt = array($skill_name,$active) ;
  		array_push($skill_array,$tt);
				}
		for ($i=0;$i<300;$i++){
			$result .= action($player1,$player2,$skill_array);
			if($player2->hp <= 0 ) {
				$result .= $player2->name.'已經死亡，'.$player1->name.'還剩下'.$player1->hp.' hp';
				 break;
			}
			$result .= action($player2,$player1,$skill_array);
			if($player1->hp <= 0 ) {
				$result .= $player1->name.'已經死亡，'.$player2->name.'還剩下'.$player2->hp.' hp';
				 break;
			}
		}
		return $result;
}   


function attack1($player1,$player2,$power) { //普A
		$atk = intval(($player1->atk + mt_rand(1,70))*$power);
		$random = mt_rand(1,100);
		if ($random <= $player2->block) {
        $result = $player2->name.'擋下了攻擊
';
        return $result;
		}
		$random = mt_rand(1,100);
		if ($random <= $player2->crit) {
		$atk = intval($atk*1.5);
		$player2->hp = $player2->hp - $atk;		
        $result = $player1->name.'攻擊....命中要害！，'.$player2->name.'受到 '.$atk.' 點傷害
';
        return $result;
		}
		$player2->hp = $player2->hp - $atk;
        $result = $player1->name.'攻擊，'.$player2->name.'受到 '.$atk.' 點傷害
';
		return $result;
    }

function action($player1,$player2,$skill_array) {
	$chance = 1000;
	foreach ($skill_array as $skill) { //如沒有在範圍內 則概率空間減去
	$skill_name = $skill[0];
	$action = $skill[1];
	$action=intval($action); 
    $random = mt_rand(1, $chance);
    if ($random <= $action) {
        $func = $skill_name;
		$call_back = $func($player1,$player2);
        return $call_back;
    } else {
        $chance -= $action;
    }
}
if ($chance > 0) {
	$call_back = attack1($player1,$player2,1);
	return $call_back;	
}
	
	
	
	
	
	
	
	
    }
//todo    spacial(dodge block heal delay attack)  hit user more





?>