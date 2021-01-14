<?php
function logger($vv) {
   $ho = $_SERVER['SERVER_NAME']."-".date('Y-m');
   $ho = date('Y-m-d');
   $ip = str_pad($_SERVER['REMOTE_ADDR'],15);
   $fp = fopen("C:/Apache24/htdocs/004/logs/app-$ho.log", 'a+');
   fwrite($fp, date('Y-m-d G:i:s').'  '.$ip.' '.$vv."\n");
   fclose($fp);
  }

function echom ($skey) {
			global $client,$event,$con;
            $results = $con->query("select * from echo_list where UPPER(keyword) = UPPER('$skey')");
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
			$json = file_get_contents("https://script.google.com/macros/s/AKfycbdJhRFUnApa01lskpRLpkPyD7pbkQ2Gx/exec");
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
			if ($content === '圖片'){
				$img = $array[3];
				$con->exec("INSERT INTO echo_list (keyword, content,img) VALUES('$keyword', '圖片','$img')");		
				
			}else{
				$con->exec("INSERT INTO echo_list (keyword, content) VALUES('$keyword','$content')");			
				
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
 1 => 3, //specail prize
 2 => 5997, //SSR
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
foreach ($prize3 as $gId => $prizes) {
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
    "contents"=> array(
      array(
        "type"=> "text",
        "text"=> "抽卡結果",
        "weight"=> "bold",
        "size"=> "4xl",
        "color"=> "#000000FF",
        "align"=> "center",
        "contents"=> array()
      )
    )
  ),
  "body"=> array(
    "type"=> "box",
    "layout"=> "vertical",
    "contents"=> array(
      array(
        "type"=> "box",
        "layout"=> "horizontal",
        "contents"=> array(
          array(
            "type"=> "image",
            "url"=> "https://i.imgur.com/zgs011N.png"
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
            "url"=> "https://i.imgur.com/RxcVOh1.png"
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
            "url"=> "https://i.imgur.com/OqDAdAh.png"
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
            "url"=> "https://i.imgur.com/m12Itau.png"
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






?>