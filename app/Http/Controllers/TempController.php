<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Sources;
use App\Models\Numbers;
use App\Models\Measures;
use App\Models\Groups;
use App\Models\Sectors;
use App\Models\Indicators;
use App\Models\Measures_values;
use App\Http\Controllers\CalculationController;
use Illuminate\Support\Facades\DB;

class TempController extends Controller
{
	public function replace_nbsp_with_space($content){
		return preg_replace('/\s+/', '_', $content);
	}

	//years: 2018 - 6, 2019 - 1, 2020 - 2
	public function import_data(){
		$update = isset($_REQUEST['update']) && $_REQUEST['update'] == 'yes';
		//$file = 'https://eecu.sitegist.com/csv/zhytomyr-new.csv';
		//$file = 'https://eecu.sitegist.com/csv/vinnycia-new.csv';
		$file = isset($_REQUEST['url']) ? 'https://eecu.sitegist.com/csv/' . $_REQUEST['url'] : false;
		if(!$file){
			echo 'Вкажіть файл у параметрі url (попередньо залийте файл в папку  eecu.sitegist.com/public/csv)';
			return;
		}
		if (($handle = fopen($file, 'r')) !== false){
			while(($data = fgetcsv($handle, 10000, ',')) !== false){
				$value = doubleval(str_replace(',', '.', $data[1]));
				switch($data[3]){
					case 'Житомир':
						$community_id = 6;
						break;
					case 'Вінниця':
						$community_id = 5;
						break;
					case 'Сєвєродонецьк':
						$community_id = 15;
						break;
					case 'Нововолинськ':
						$community_id = 14;
						break;
					default:
						$community_id = false;
				}
				$measure = Measures::where('name', $data[0])->get()->first();
				$measure_id = isset($measure['id']) ? $measure['id'] : false;
				$source = Sources::where('name', $data[4])->get()->first();
				$source_id = isset($source['id']) ? $source['id'] : false;
				switch($data[2]){
					case '2018':
						$year_id = 6;
						break;
					case '2019':
						$year_id = 1;
						break;
					case '2020':
						$year_id = 2;
						break;
					default:
						$year_id = false;
				}
				$measure_name = isset($measure['id']) ? $measure['id'] : '<strong style="color:red">|M|</strong>';
				$source_name = isset($source['id']) ? $source['id'] : '<strong style="color:red">|S|</strong>';
				echo '[' . $data[0] . ' => ' . $measure_name . '] [' . $data[4] . ' => ' . $source_name . '] done = ' . $value . '<br>';

				if($community_id && $measure_id && $year_id){
					$measure_value = Measures_values::where([['measure_id', $measure_id], ['year_id', $year_id], ['community_id', $community_id]])->get()->first();
					if($update && !empty($measure_value)){
						$measure_new = $measure_value;
						$measure_new->measure_id = $measure_id;
						$measure_new->file_id = 1;
						$measure_new->year_id = $year_id;
						$measure_new->community_id = $community_id;
						$measure_new->status = 'active';
						$measure_new->value = $value;
						$measure_new->save();
					}else{
						if(empty($measure_value)){
							$measure_new = new Measures_values;
							$measure_new->measure_id = $measure_id;
							$measure_new->file_id = 1;
							$measure_new->year_id = $year_id;
							$measure_new->community_id = $community_id;
							$measure_new->status = 'active';
							$measure_new->value = $value;
							$measure_new->save();
						}
					}

					//перерахувати значення для років
					if(isset($_REQUEST['totals']) && $_REQUEST['totals'] == 'yes'){
						$calculation = new CalculationController;
						$calculation->calculate_indicator_by_measures($community_id, $year_id, array($measure_id));
						$calculation->calculate_groups_total($community_id, $year_id);
						$calculation->calculate_year_total($community_id, $year_id);
					}
				}
			}
			fclose($handle);
		}
	}

    public function import_numbers(){
		$file = 'https://eecu.sitegist.com/csv/eecu-numbers.csv';
	    if (($handle = fopen($file, 'r')) !== false){
		    while(($data = fgetcsv($handle, 10000, ',')) !== false){
				$source = Sources::where('name', $data[3])->get()->first()->toArray();
			    $source = !empty($source) ? $source['id'] : false;

			    $measure_exist = Measures::where('name', $data[0])->get()->first();

				$measure = new Measures;
				$measure->name = $data[0];
				$measure->code = $data[4];
				$measure->dimension = $data[1];
				$measure->precision = $data[2];
				$measure->source_id = $source;
				$measure->status = 'active';
//			        $measure->save();

				$measure_id = isset($measure_exist['id']) ? $measure_exist['id'] : 'none';

//			    DB::table('measures_x_sources')->insert([
//				    'measure_id' => $measure_id,
//				    'source_id' => $source
//			    ]);
				echo $data[0] . ' => ' . $measure_id . ' => ' . $source . '<br>';
		    }
		    fclose($handle);
	    }
    }

	public function import_indicators(){
		$file = 'https://eecu.sitegist.com/csv/eecu-indicators.csv';
		if (($handle = fopen($file, 'r')) !== false){
			while(($data = fgetcsv($handle, 10000, ',')) !== false){

				//$source = Sources::where('name', $data[3])->get()->first()->toArray();
				//$source = !empty($source) ? $source['id'] : false;

				//імпорт груп
				//if(empty(Groups::where('name', $data[0])->get()->first())){
				//	$group = new Groups;
				//	$group->name = $data[0];
				//	$group->used_in_calculations = 1;
				//	$group->status = 'active';
				//	$group->save();
				//}

				//імпорт секторів
				//if(empty(Sectors::where('name', $data[1])->get()->first())){
				//	$group = Groups::where('name', $data[0])->get()->first()->toArray();
				//	$group = !empty($group) ? $group['id'] : false;
				//	$sector = new Sectors;
				//	$sector->name = $data[1];
				//	$sector->group_id = $group;
				//	$sector->status = 'active';
				//	$sector->save();
				//}

				//імпорт індикаторів
				$group = Groups::where('name', $data[0])->get()->first();
				$group = !empty($group) ? $group->id : false;
				$sector = Sectors::where('name', $data[1])->get()->first();
				$sector = !empty($sector) ? $sector->id : false;
				$numerator = Measures::where('name', $data[6])->get()->first();
				$numerator = !empty($numerator) ? $numerator->id : 549;
				$denominator = Measures::where('name', $data[7])->get()->first();
				$denominator = !empty($denominator) ? $denominator->id : 549;

//				$indicator = new Indicators;
//				$indicator->name = $data[2];
//				$indicator->code = $data[8];
//				$indicator->dimension = $data[3];
//				$indicator->precision = $data[4];
//				$indicator->numerator_id = $numerator;
//				$indicator->denominator_id = $denominator;
//				$indicator->sector_id = $sector;
//				$indicator->group_id = $group;
//				$indicator->status = 'active';
				//$indicator->save();

				$numerator = Measures::where('name', $data[6])->get()->first();
				$denominator = Measures::where('name', $data[7])->get()->first();

//				echo '<strong>Чисельник:</strong> ' . $data[6] . ' => [' . count($numerator) .']<br>';
//				echo '<strong>Знаменник:</strong> ' . $data[7] . ' => [' . count($denominator) .']<br>';
				$indicator = Indicators::where('name', $data[2])->get()->first();
//				DB::table('indicators')->where('id', $indicator->id)->update(
//					array(
//						'weight' => str_replace(',', '.', $data[5])
//					)
//				);

				echo '<strong>[' . $group . ']</strong>';
				if(!empty($numerator->id)){
					echo ' ' . $numerator->id;
//					DB::table('measures_x_groups')->insert([
//						'measures_id' => $numerator->id,
//						'groups_id' => $group
//					]);
				}
				if(!empty($denominator->id)){
					echo ' ' . $denominator->id;
//					DB::table('measures_x_groups')->insert([
//						'measures_id' => $denominator->id,
//						'groups_id' => $group
//					]);
				}
				echo '<br>';
			}
			fclose($handle);
		}
	}
}
