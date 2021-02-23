<?php
function attack2($player1,$player2,$power) { //skill attack
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
        $result = '命中要害！，對'.$player2->name.'受到 '.$atk.' 點傷害
';
        return $result;
		}
		$player2->hp = $player2->hp - $atk;
        $result = '對'.$player2->name.'受到 '.$atk.' 點傷害
';
		return $result;
    }
function starburst_stream($player1,$player2){
	$result = $player1->name.'：「星爆...氣流斬」
';
	
	for($i = 1 ;$i<17;$i++){
		$power = 1+(0.1*$i);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}

function eclipse($player1,$player2){
	$result = $player1->name.'使出日蝕
';
	
	for($i = 1 ;$i<22;$i++){
		$power = 1+(0.1*$i);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}


function horizontal_square($player1,$player2){
	$result = $player1->name.'使出水平四方斬
';
	
	for($i = 1 ;$i<5;$i++){
		$power = 1+(0.7);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}

function vertical_square($player1,$player2){
	$result = $player1->name.'使出垂直四方斬
';
	
	for($i = 1 ;$i<5;$i++){
		$power = 1+(0.7);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}



function sharp_rail($player1,$player2){
	$result = $player1->name.'使出銳爪
';
	
	for($i = 1 ;$i<4;$i++){
		$power = 1+(0.05);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}

function star_splash($player1,$player2){
	$result = $player1->name.'使出星屑飛濺
';
	
	for($i = 1 ;$i<9;$i++){
		$power = 1+(0.03*$i);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}

function deadly_sins($player1,$player2){
	$result = $player1->name.'使出七大罪
';
	
	for($i = 1 ;$i<8;$i++){
		$power = 1+(0.07*$i);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}


function vertical($player1,$player2){
	$result = $player1->name.'使出垂直斬
';
	
	for($i = 1 ;$i<2;$i++){
		$power = 1+(0.1*$i);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}

function sonic_leap($player1,$player2){
	$result = $player1->name.'使出音速衝擊
';
	
	for($i = 1 ;$i<2;$i++){
		$power = 1+(0.1*$i);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}
function snake_bite($player1,$player2){
	$result = $player1->name.'使出蛇咬
';
	
	for($i = 1 ;$i<3;$i++){
		$power = 1+(0.04*$i);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}

function savage_fulcrum($player1,$player2){
	$result = $player1->name.'使出野蠻支軸
';
	
	for($i = 1 ;$i<4;$i++){
		$power = 1+(0.04*$i);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}

function mother_Rosario($player1,$player2){
	$result = $player1->name.'使出聖母聖詠
';
	
	for($i = 1 ;$i<12;$i++){
		$power = 1+(0.06*$i);
		$result .='第'.$i.'擊';
		$result .= attack2($player1,$player2,$power);
	}
	return $result;
}

function skill_combo($player1,$player2){
	$result = $player1->name.'使出劍技連攜
';
	$random = mt_rand(1,7);
	$combo_list=array('savage_fulcrum','snake_bite','sonic_leap','vertical','sharp_rail','vertical_square','horizontal_square');
	for($i = 0 ;$i<$random;$i++){
		$key = array_rand($combo_list,1);
		$func = $combo_list[$key];
		unset($combo_list[$key]);
		$i2 = $i+1;
		$result .='第'.$i2.'招';
		$result .=  $func($player1,$player2);
        
	}
	return $result;
}






?>