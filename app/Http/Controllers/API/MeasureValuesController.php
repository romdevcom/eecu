<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\CalculationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Models\Years;
use App\Models\Sources;
use App\Models\Measures;
use App\Models\Measures_values;
use App\Models\Numbers;
use App\Models\Groups;
use App\Models\Sectors;
use App\Models\Indicators;
use App\Models\Communities;

class MeasureValuesController extends BaseController
{
	public function update_metric($value, $event, $user){
		$user_manager = $user->role_id == 3;
		//потрібно глянути чи є таке значення з для такої громади за таким роком і якщо немає, тоді створити значення
		$exist = false;
		$exist_file = true;
		//початково статус буде active, далі значення $event може змінити його
		$status = 'active';

		//якщо файл не прикріплений, тоді видати помилку
		if(!isset($value->file) || !isset($value->file->id) || empty($value->file->id)){
			$file_array = [
				'id' => '',
				'icon' => '',
				'name' => 'Нема файлів',
				'url' => '',
			];
		}else{
			$file_array = [
				'id' => $value->file->id,
				'icon' => $value->file->icon,
				'name' => $value->file->name,
				'url' => $value->file->url,
			];
		}

		$value_id = false;
		$value_explode = explode('_', $value->id);
		if(isset($value_explode[0]) && isset($value_explode[1]) && isset($value_explode[2])){
			$value_repo = Measures_values::where([['measure_id', $value_explode[0]], ['community_id', $value_explode[1]], ['year_id', $value_explode[2]]])->get()->first();
			if(!empty($value_repo)){
				$value_id = $value_repo->id;
			}
		}

		if($value_id && isset($value_repo)){
			//дістати показник, який приходить
			$item = $value_repo;
			if(!empty($item)){
				$status = $item->status;
				switch($event){
					//якщо подія approve - тоді якщо це адміністратор - затвердити значення, якщо ні - дати 403 помилку
					case 'approve':
						if(!$user_manager){
							//якщо файл не прикріплений, тоді видати помилку
							if(!isset($value->file) || !isset($value->file->id) || empty($value->file->id)){
								return $this->send_error('до значення не прикріплений файл', 403);
							}
							$status = 'approved';
							DB::table('measures_values')->where([['measure_id', $value_explode[0]], ['community_id', $value_explode[1]], ['year_id', $value_explode[2]]])->update(
								array('value' => $value->value, 'file_id' => $value->file->id, 'status' => 'approved')
							);
						}else{
							$status = 'dataentry';
							$file_id = !isset($value->file) || !isset($value->file->id) || empty($value->file->id) ? null : $value->file->id;
							DB::table('measures_values')->where([['measure_id', $value_explode[0]], ['community_id', $value_explode[1]], ['year_id', $value_explode[2]]])->update(
								array('value' => $value->value, 'file_id' => $file_id, 'status' => 'active')
							);
						}
						break;
					case 'cancel':
						//cancel для адміністратора скасовує затвердження, для менеджера - відкріпляє файл
						if($user_manager){
							$status = $value->status;
							$exist_file = false;
							DB::table('measures_values')->where([['measure_id', $value_explode[0]], ['community_id', $value_explode[1]], ['year_id', $value_explode[2]]])->update(
								array('file_id' => null)
							);
						}else{
							$status = 'active';
							DB::table('measures_values')->where([['measure_id', $value_explode[0]], ['community_id', $value_explode[1]], ['year_id', $value_explode[2]]])->update(
								array('status' => 'for approval')
							);
						}
						break;
					case 'save':
						//save зберігає значення показника для будь-якого користувача
						$status = 'active';
						DB::table('measures_values')->where([['measure_id', $value_explode[0]], ['community_id', $value_explode[1]], ['year_id', $value_explode[2]]])->update(
							array('value' => $value->value)
						);
						break;
					case 'unlink':
						DB::table('measures_values')->where([['measure_id', $value_explode[0]], ['community_id', $value_explode[1]], ['year_id', $value_explode[2]]])->update(
							array('file_id' => null)
						);
						$file_array = [
							'id' => '',
							'icon' => '',
							'name' => 'Нема файлів',
							'url' => '',
						];
						$exist_file = false;
						break;
				}
				$exist = true;
			}
		}

		//якщо значення не знайдуно - тоді потрібно додати в базу показник
		if(!$value_id){
			$value_id = DB::table('measures_values')->insertGetId(
				[
					'community_id' => $value->community_id,
					'year_id' => $value->year_id,
					'measure_id' => $value->measure_id,
					'file_id' => !isset($value->file) || !isset($value->file->id) || empty($value->file->id) ? null : $value->file->id,
					'value' => $value->value,
					'status' => $status
				]
			);
			$value->id = $value->measure_id . '_' . $value->community_id . '_' . $value->year_id;
		}

		$actions = $exist_file ? ['unlink'] : ['link'];
		if(empty($user_manager)){
			if($status == 'approved'){
				$actions = ['cancel'];
			}else{
				$actions[] = 'approve';
			}
		}

		sort($actions);
		//повернути рядок, який буде показаний у таблиці
		return array(
			'id'  => $value->id,
			'community_id' => $value->community_id,
			'year_id' => $value->year_id,
			'measure_id' => $value->measure_id,
			'checked'  => $value->checked,
			'status' => in_array($status, ['approved', 'for approval']) && !$user_manager ? 'approved' : 'dataentry',
			'name' => $value->name,
			'value' => $value->value,
			'measure' => $value->measure,
			'file' => $file_array,
			'actions' => array_reverse($actions)
		);
	}

	public function edit_metrics(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			$community = Communities::where('id', $user->community_id)->get()->first();
			if($this->is_admin_or_approved_manager($user, $user->role_id == 3 ?$community->id : false)){
				$data = json_decode($request->getContent());
				$results['data'] = array();
				//потрібно зберегти дані громади, року та показників, щоб потім оновити дані
				$community_id = false; $year_id = false; $measures = [];
				foreach($data->payload as $value){
					$community_id = $value->community_id;
					$year_id = $value->year_id;
					$measures[] = $value->measure_id;
					$results['data'][] = $this->update_metric($value, $data->evtName, $user);
				}

				//оновити загальні дані по показниках для громади та року
				$calculation = new CalculationController;
				$calculation->calculate_indicator_by_measures($community_id, $year_id, $measures);
				$calculation->calculate_groups_total($community_id, $year_id);
				if($data->evtName == 'cancel' && $this->is_admin($user)){
					$calculation->calculate_year_total($community_id, $year_id, 'for approval');
				}else{
					$calculation->calculate_year_total($community_id, $year_id);
				}

				return $this->send_response($results, 'Зміни збережено');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function edit_metrics_id(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			if(isset($request->id)){
				$data = json_decode($request->getContent());
				if($this->is_admin_or_approved_manager($user, $data->payload->community_id)){
					$data->payload->id = $request->id;
					$results['data'] = $this->update_metric($data->payload, $data->evtName, $user);

					//оновити загальні дані по показниках для громади та року
					$calculation = new CalculationController;
					$calculation->calculate_indicator_by_measures($data->payload->community_id, $data->payload->year_id, [$data->payload->measure_id]);
					$calculation->calculate_groups_total($data->payload->community_id, $data->payload->year_id);
					if($data->evtName == 'cancel' && $this->is_admin($user)){
						$calculation->calculate_year_total($data->payload->community_id, $data->payload->year_id, 'for approval');
					}else{
						$calculation->calculate_year_total($data->payload->community_id, $data->payload->year_id);
					}

					return $this->send_response($results, 'Зміни збережено');
				}
				return $this->send_error('forbidden', 403);
			}
			return $this->send_error('not found', 404);
		}
		return $this->send_error('unauthorised');
	}
}