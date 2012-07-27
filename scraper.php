<?php
	include('../simplehtmldom/simple_html_dom.php');
	
	$file = file_get_contents('urls.txt');
	$urls = explode(',', $file);

	foreach($urls as $url){
		$html = file_get_html($url);
		$count = 0;
		$ic = 0;
		$data = array();
		$heroStats = array();
		foreach($html->find('table') as $stats){
			foreach($stats->find('td') as $td){
				//echo $ic.' - '.$td.'<br />';
				$ic++;
				array_push($data, $td);
			}
			$count++;
			if ($count > 8){break;}
		}

		foreach($data[0]->find('th') as $name){
			$heroStats['name'] = $name;
		}
		foreach($data[2]->find('th') as $faction){
			$heroStats['faction'] = str_replace($faction->last_child(), '', $faction);
		}

		$baseStats = array();
		foreach($data[3]->find('th') as $faction){
			array_push($baseStats, str_replace($faction->first_child(), '', $faction));
		}

		$strength = explode('+', str_replace('</b>', '', str_replace('<b>', '', $baseStats[0])));
		$heroStats['base_strength'] = str_replace('<br />', '', $strength[0]);
		$heroStats['strength_gain'] = $strength[1];

		$agility = explode('+', str_replace('</b>', '', str_replace('<b>', '', $baseStats[1])));
		$heroStats['base_agility'] = str_replace('<br />', '', $agility[0]);
		$heroStats['agility_gain'] = $agility[1];

		$intelligence = explode('+', str_replace('</b>', '', str_replace('<b>', '', $baseStats[2])));
		$heroStats['base_intelligence'] = str_replace('<br />', '', $intelligence[0]);
		$heroStats['intelligence_gain'] = $intelligence[1];

		$heroStats['lvl_1_hp'] = $data[6]->innertext;
		$heroStats['level_16_hp'] = $data[7]->innertext;
		$heroStats['level_25_hp'] = $data[8]->innertext;

		$heroStats['lvl_1_mp'] = $data[10]->innertext;
		$heroStats['level_16_mp'] = $data[11]->innertext;
		$heroStats['level_25_mp'] = $data[12]->innertext;

		$heroStats['lvl_1_damage'] = $data[14]->innertext;
		$heroStats['level_16_damage'] = $data[15]->innertext;
		$heroStats['level_25_damage'] = $data[16]->innertext;

		$heroStats['lvl_1_armor'] = $data[18]->innertext;
		$heroStats['level_16_armor'] = $data[19]->innertext;
		$heroStats['level_25_armor'] = $data[20]->innertext;

		$heroStats['lvl_1_attack_speed'] = $data[22]->innertext;
		$heroStats['level_16_attack_speed'] = $data[23]->innertext;
		$heroStats['level_25_attack_speed'] = $data[24]->innertext;

		$heroStats['movespeed'] = $data[27]->innertext;

		$sightRange = array();
		foreach($data[29]->find('span') as $sight){
			array_push($sightRange, $sight);
		}

		$heroStats['day_sight_range'] = $sightRange[0];
		$heroStats['night_sight_range'] = $sightRange[1];

		$heroStats['attack_range'] = $data[31];
		$heroStats['missile_speed'] = $data[33];

		$attackDuration = explode('+', $data[35]);
		$heroStats['attack_point_time'] = $attackDuration[0];
		$heroStats['attack_folow_time'] = $attackDuration[1];

		$castDuration = explode('+', $data[37]);
		$heroStats['cast_point_time'] = $castDuration[0];
		$heroStats['cast_follow_time'] = $castDuration[1];

		$heroStats['base_attack_time'] = $data[39];

		$roleAndLore = array();
		foreach($data[75]->find('td') as $row){
			array_push($roleAndLore, $row);
		}

		$heroStats['role'] = $roleAndLore[3]->innertext;
		$heroStats['lore'] = str_replace('<i>', '', str_replace('</i>', '', $roleAndLore[5]->innertext));

		$keys = array_keys($heroStats);
		echo '{';
		$cnt = 0;
		foreach($keys as $key){
			$cnt++;
			if($cnt != count($keys)){ echo "'".$key."':'".$heroStats[$key]."', ";}
			else{echo "'".$key."':'".$heroStats[$key]."'}<br />";}

		}
	}

?>
 