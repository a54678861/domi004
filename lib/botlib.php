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
			$json = file_get_contents("https://script.google.com/macros/s/AKfycbdfvdfdfvfdJhRFUnApa01lskpRLpkPyD7pbkQ2Gx/exec");
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
$client->replyMessage(array(
                     'replyToken' => $event['replyToken'],
                     'messages' => array(
               array(
                'type' => 'text', // 訊息類型 (文字)
                'text' => '比列:'.$result[1].'
SSR:'.$result[2].'
SR:'.$result[3].'
R:'.$result[4],
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