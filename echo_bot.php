<?php  
/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require('LINEBotTiny.php');
require('lib/botlib.php');
$con = new SQLite3('sql.db');	
$channelAccessToken = 'hkwwJ6arj9tIefSfkbEkJgNvaidOYU8T2wlgRq5Xnwlz/ZilKvIX3Z3COb8+lCuLnkdN9JzoWnhzGDIy1yf65cTqchC7UUciH6N++PDZ7xatYmKZce3gEsteRkJ+LaJgdB04t89/1O/w1cDnyilFU=';
$channelSecret = '1caab0f5aa7677173f2f';
$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
	 $source = 	$event['source'];
	 $groupid = $source['groupId'];
	 $user = $source['userId'];
    switch ($event['type']) {
        case 'message':
              $message = $event['message'];
              $mtext = $message['text'];
			  $dname = getdisplayname();
		//	  logger($mtext.'  '.$user.'  '.$dname);
			  $mtext = str_replace("！","!",$mtext);
              $skey = substr($mtext,1);
			  
			  if ($mtext == '個榜' or $mtext == '個綁' or $mtext == '各綁' or $mtext == '個排' or $mtext == '各排' ) prediction($skey);
			  if ($mtext == 'help') showhelp();
			  if (substr($mtext,0,6) == '抽卡' ) prize($mtext);
			  if (substr($mtext,0,4) == '學;') learn($mtext);
			  if (substr($mtext,0,4) == '廢;') trash($mtext);
			  if (substr($mtext,0,7) == '清單;') listk($mtext);
			  if (substr($mtext,0,7) == '刪除;') deletek($mtext);
			  if (substr($mtext,0,6) == '天氣') weather($mtext);
			  if (substr($mtext,0,7) == '戰鬥;') battle($mtext);
			  
			  if (substr($mtext,0,1) == '!' or substr($mtext,0,) == '！') echom($skey);
              ctrlcv ($mtext); //借來的跟風喊話
              

        default:
            error_log('Unsupported event type: ' . $event['type']);
            break;
    }
};
?>

			   