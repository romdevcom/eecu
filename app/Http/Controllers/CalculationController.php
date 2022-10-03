<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Sources;
use App\Models\Numbers;
use App\Models\Measures;
use App\Models\Groups;
use App\Models\Sectors;
use App\Models\Years;
use App\Models\Indicators;
use App\Models\Communities;
use App\Models\Coefficients;
use App\Models\Measures_values;
use App\Models\Indicators_values;
use App\Models\Total_values;
use Illuminate\Support\Facades\DB;

class CalculationController extends Controller
{
	public function index(Request $request){
		$all_communities = [];
		$all_years = [];
		$communities = Communities::where('status', 'active')->get();
		$years = Years::where('status', 'approved')->get();
		$current_community = isset($request->communities) ? $request->communities : false;
		$current_year = isset($request->years) ? $request->years : false;
		?>
		<html>
			<head>
				<title>Перерахунок індикаторів та значень</title>
			</head>
			<body>
				<form type="get">
					<label>
						Громада
						<select name="communities">
							<option value="Всі">Всі</option>
							<?php if(!empty($communities)){ ?>
								<?php foreach($communities as $community){ $all_communities[] = $community->id; ?>
									<option value="<?php echo $community->id; ?>" <?php echo $current_community == $community->id ? 'selected' : ''; ?>><?php echo $community->name; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</label>
					<label>
						Роки
						<select name="years">
							<option value="Всі">Всі</option>
							<?php if(!empty($years)){ ?>
								<?php foreach($years as $year){ $all_years[] = $year->id; ?>
									<option value="<?php echo $year->id; ?>" <?php echo $current_year == $year->id ? 'selected' : ''; ?>><?php echo $year->name; ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</label>
					<input type="submit" value="Перерахувати">
				</form>
			</body>
		</html>
		<?php
		if(!empty($current_community) && !empty($current_year)){
			$calculate_communities = $current_community == 'Всі' ? $all_communities : [$current_community];
			$calculate_years = $current_year == 'Всі' ? $all_years : [$current_year];

			//dd($calculate_communities);
			//dd($calculate_years);

			//вирахувати значення індикаторів по групах
			foreach($calculate_communities as $calculate_community){
				foreach($calculate_years as $calculate_year){
					for($i = 1; $i < 13; $i++){
						$this->calculate_indicator_by_group($calculate_community, $calculate_year, $i);
					}
				}
			}

			foreach($calculate_communities as $calculate_community){
				foreach($calculate_years as $calculate_year){
					$this->calculate_groups_total($calculate_community, $calculate_year);
					$this->calculate_year_total($calculate_community, $calculate_year);
				}
			}
			echo 'Готово!';
		}
	}

	public function calculate_indicator_by_measures($community_id, $year_id, $measure_ids){
		$denominators = Indicators::whereIn('denominator_id', $measure_ids)->get();
		if(count($denominators)){
			foreach($denominators as $denominator){
				$this->calculate_indicator_value($denominator, $community_id, $year_id);
			}
		}
		$numerators = Indicators::whereIn('numerator_id', $measure_ids)->get();
		if(count($numerators)){
			foreach($numerators as $numerator){
				$this->calculate_indicator_value($numerator, $community_id, $year_id);
			}
		}
	}

	public function calculate_indicator_by_group($community_id, $year_id, $group_id){
		//DB::enableQueryLog();
		$measures = DB::table('measures_x_groups')->where('groups_id', $group_id)->get()->pluck('measures_id')->toArray();
		if(!empty($measures)){
			$this->calculate_indicator_by_measures($community_id, $year_id, $measures);
		}
	}

	public function calculate_indicator_value($indicator, $community_id, $year_id, $file_id = 1){
		$measure_numerator = Measures_values::where(
			[['community_id', $community_id], ['year_id', $year_id], ['measure_id', $indicator->numerator_id]]
		)->get()->first();
		$measure_denominator = Measures_values::where(
			[['community_id', $community_id], ['year_id', $year_id], ['measure_id', $indicator->denominator_id]]
		)->get()->first();

		//якщо показники для чисельника та знаменника внесені тоді вираховуємо значення, інакше залишається 0
		$value = 0; $score = 0;
        $denominator_allow_formulas = ['divide by 1'];
		if((!empty($measure_numerator) && !empty($measure_numerator->file_id)) && ((!empty($measure_denominator) && !empty($measure_denominator->file_id)) || in_array($indicator->formula, $denominator_allow_formulas))){
			//індикатор може вираховуватись не по стандартній формулі, тому тут switch
			switch($indicator->formula){
                case 'divide by 1':
	                $value = round(floatval($measure_numerator->value) / 1);
                    break;
				default:
					if(floatval($measure_denominator->value) != 0){
						$value = round(floatval($measure_numerator->value) / floatval($measure_denominator->value), $indicator->precision);
					}
			}
			//якщо одиниця вимірювання = %, тоді потрібно помножити значення на 100
			if($indicator->dimension == '%'){
				$value = $value * 100;
			}

			//далі необхідно порахувати значення відносно ваги (коефіцієнту)
			//у таблиці coefficients може бути представлена вага відносно індикатора року
			$coefficient = Coefficients::where([['year_id', $year_id], ['indicator_id', $indicator->id]])->get()->first();

			//якщо ваги відносно року не знайдено, тоді береться вага, записана в таблиці індикатора
			$coefficient = !empty($coefficient) ? $coefficient->value : $indicator->weight;
			$score = $value * floatval($coefficient);

			//потрібно пошукати чи вже є такий індикатор для цього року та громади
			$indicator_value = Indicators_values::where([['community_id', $community_id], ['year_id', $year_id], ['indicator_id', $indicator->id]])->get()->first();

			//якщо є - оновити дані value та score, якщо ні - створити його
			if(!empty($indicator_value)){
				DB::table('indicators_values')
				  ->where('id', $indicator_value->id)
				  ->update(['value' => $value, 'score' => $score]);
			}else{
				$indicator_value = new Indicators_values;
				$indicator_value->indicator_id = $indicator->id;
				$indicator_value->year_id = $year_id;
				$indicator_value->community_id = $community_id;
				$indicator_value->file_id = $file_id;
				$indicator_value->value = $value;
				$indicator_value->score = $score;
				$indicator_value->status = 'active';
				$indicator_value->save();
			}
		}
	}

	public function calculate_group_total($community_id, $year_id, $group_id, $status = false){
		$count = DB::table('measures as m')
           ->join('measures_x_groups as x', 'm.id', '=', 'x.measures_id')
           ->where('x.groups_id', $group_id)
           ->distinct('m.id')->count('m.id');
		$values_count = DB::table('measures_values as m')
           ->join('measures_x_groups as x', 'm.measure_id', '=', 'x.measures_id')
           ->where([['x.groups_id', $group_id], ['m.community_id', $community_id], ['m.year_id', $year_id], ['m.value', '!=', null], ['m.value', '!=', ''], ['m.file_id', '!=', null]])
           ->distinct('m.measure_id')->count('m.measure_id');
		$values_approved_count = DB::table('measures_values as m')
           ->join('measures_x_groups as x', 'm.measure_id', '=', 'x.measures_id')
           ->where([['x.groups_id', $group_id], ['m.community_id', $community_id], ['m.year_id', $year_id], ['m.value', '!=', null], ['m.value', '!=', ''], ['m.file_id', '!=', null], ['m.status', 'approved']])
           ->distinct('m.measure_id')->count('m.measure_id');

		//вирахування рейтингу
		$values_percent = !empty($values_count) ? round(($values_count * 100) / $count, 0) : 0;
		$values_approved_percent = !empty($values_approved_count) ? round(($values_approved_count * 100) / $values_count, 0) : 0;

		//пошук чи є вже дані по цьому року, громаді та групі
		$total_value = Total_values::where([['group_id', $group_id], ['community_id', $community_id], ['year_id', $year_id], ['type', 'group']])->get()->first();

		$value = 0;
		$indicators = DB::table('indicators_values as v')->join('indicators as i', 'v.indicator_id', '=', 'i.id')->where([['v.community_id', $community_id], ['v.year_id', $year_id], ['i.group_id', $group_id]])->get();
		foreach($indicators as $indicator){
			$value += doubleval($indicator->score);
		}

		//якщо це група Екологія, тоді значення вираховується іншим шляхом
		//як звичайна сума + AVERAGE(Вода - 2, Територія - 4, Відходи - 8)
		if($group_id == 3){
			$this->calculate_group_total($community_id, $year_id, 2);
			$this->calculate_group_total($community_id, $year_id, 4);
			$this->calculate_group_total($community_id, $year_id, 8);
			$value += Total_values::where([['community_id', $community_id], ['year_id', $year_id], ['type', 'group']])->whereIn('group_id', [2, 4, 8])->avg('value');
        }

        //отримати назву року
        $year_name = Years::where('id', $year_id)->get()->pluck('name')->first();

		if($total_value){
			DB::table('total_values')
			  ->where('id', $total_value->id)
			  ->update([
				  'value' => $value,
				  'count_all' => $count,
				  'count_values' => $values_count,
				  'count_approved_values' => $values_approved_count,
				  'percent_values' => $values_percent,
				  'percent_approved_values' => $values_approved_percent,
				  'status' => $status ? $status : $total_value->status
			  ]);
		}else{
			DB::table('total_values')
			  ->insert([
				  'year_id' => $year_id,
				  'year_name' => $year_name,
				  'community_id' => $community_id,
				  'group_id' => $group_id,
				  'value' => $value,
				  'type' => 'group',
				  'count_all' => $count,
				  'count_values' => $values_count,
				  'count_approved_values' => $values_approved_count,
				  'percent_values' => $values_percent,
				  'percent_approved_values' => $values_approved_percent,
				  'status' => $status ? $status : 'waiting'
			  ]);
		}
    }

	public function calculate_groups_total($community_id, $year_id, $status = false){
		$groups = Groups::get();
		if(count($groups)){
			foreach($groups as $group){
				$this->calculate_group_total($community_id, $year_id, $group->id);
			}
		}
	}

	public function calculate_year_total($community_id, $year_id, $status = false){
		$count = DB::table('measures as m')->distinct('m.id')->count('m.id');
		$values_count = DB::table('measures_values as m')
          ->where([['community_id', $community_id], ['year_id', $year_id], ['value', '!=', null], ['value', '!=', ''], ['file_id', '!=', null]])
          ->distinct('m.measure_id')->count('m.measure_id');
		$values_approved_count = DB::table('measures_values as m')
           ->where([['m.community_id', $community_id], ['m.year_id', $year_id], ['m.status', 'approved']])
           ->distinct('m.measure_id')->count('m.measure_id');

		//вирахування рейтингу
		$values_percent = !empty($values_count) ? round(($values_count * 100) / ($count - 1), 0) : 0;
		$values_approved_percent = !empty($values_approved_count) ? round(($values_approved_count * 100) / $values_count, 0) : 0;

		//пошук чи є вже дані по цьому року та громаді
		$total_value = Total_values::where([['community_id', $community_id], ['year_id', $year_id], ['type', 'year']])->get()->first();

		//$value = 0;
		//перерахувати значення по групах
		$value = DB::table('total_values as v')
			->join('groups as g', 'v.group_id', '=', 'g.id')
			->where([['community_id', $community_id], ['year_id', $year_id], ['v.type', 'group'], ['g.used_in_calculations', 1]])
			->sum('v.value');
		//$indicators = DB::table('indicators_values as v')->where([['v.community_id', $community_id], ['v.year_id', $year_id]])->get();
		//foreach($indicators as $indicator){
		//	$value += doubleval($indicator->score);
		//}

		//отримати назву року
		$year_name = Years::where('id', $year_id)->get()->pluck('name')->first();

		if($total_value){
			DB::table('total_values')
			  ->where('id', $total_value->id)
			  ->update([
				  'value' => $value,
				  'count_all' => $count,
				  'count_values' => $values_count,
				  'count_approved_values' => $values_approved_count,
				  'percent_values' => $values_percent,
				  'percent_approved_values' => $values_approved_percent,
				  'status' => $status ? $status : $total_value->status,
			  ]);
		}else{
			DB::table('total_values')
			  ->insert([
				  'year_id' => $year_id,
				  'year_name' => $year_name,
				  'community_id' => $community_id,
				  'value' => $value,
				  'type' => 'year',
				  'count_all' => $count,
				  'count_values' => $values_count,
				  'count_approved_values' => $values_approved_count,
				  'percent_values' => $values_percent,
				  'percent_approved_values' => $values_approved_percent,
				  'status' => $status ? $status : 'waiting',
			  ]);
		}
	}
}
