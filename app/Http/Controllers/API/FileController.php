<?php
namespace App\Http\Controllers\API;

use Couchbase\Role;
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
use App\Models\Files;
use App\Models\Sources;
use App\Models\Measures;
use App\Models\Measures_values;
use App\Models\Numbers;
use App\Models\Groups;
use App\Models\Sectors;
use App\Models\Indicators;
use App\Models\Communities;
use App\Models\Confidences;
use App\Models\Total_values;
use Response,File;
//use ParagonIE\Sodium\File;

class FileController extends BaseController
{
	public function get_file_form(Request $request){
		$user = Auth::user();
		$file = false;
		if($user){
			if($this->is_approved_user($user)){
				$sources = Sources::select('id as value', 'name as label')->get()->toArray();
				$confidences = Confidences::select('id as value', 'name as label')->get()->toArray();

				$file_array = [];
				if($request->file_id){
					$file = Files::where('id', $request->file_id)->get()->first();
					$file_array['name'] = $file->name;
					$file_array['size'] = !empty($file->file) ? Storage::size($file->file) : 0;
					$file_array['type'] = $file->extension;
				}
				$file_name = !empty($file) ? $file->name : '';
				$file_source = !empty($file) ? $file->source_id : '';
				$file_confidence = !empty($file) ? $file->confidence_id : '';

				$files = array();
				if(isset($request->community_id) && isset($request->year_id)){
					$files = DB::table('files as f')
					           ->join('confidences as c', 'c.id', '=', 'f.confidence_id')
					           ->where([['f.community_id', $request->community_id], ['f.year_id', $request->year_id]])
					           ->select('f.id as value', 'f.name as label', 'f.file as url', 'f.confidence_id as type', 'f.source_id as source')->distinct('f.file')
					           ->get()->toArray();
				}
				foreach($files as $item){
					$item->url = $this->url . Storage::url($item->url);
				}

				$results = array(
					'objType' => 'files',
					'form' => array(
						'newfile' => array(
							'field_type' => 'file',
							'label' => 	'Файл',
							'value' => count($file_array) ? $file_array : []
						),
						'oldfile' => array(
							'field_type' => 'file',
							'label' => 'Файл',
							'value' => null,
							'options' => !empty($files) ? $files : []
						),
						'name' => array(
							'field_type' => 'text',
							'label' => 	'Назва файлу',
							'value' => $file_name
						),
						'source' => array(
							'field_type' => 'select',
							'label' => 	'Джерело',
							'value' => $file_source,
							'options' => $sources
						),
						'confidence' => array(
							'field_type' => 'select',
							'label' => 	'Рівень довіри',
							'value' => $file_confidence,
							'options' => $confidences
						)
					)
				);
				$community = Communities::where('id', $user->community_id)->get()->first();
				if(!empty($community)){
					$results['community_id'] = $community->id;
				}
				return $this->send_response($results, 'Зміни збережено');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function edit_file_form(Request $request){
		$user = Auth::user();
		if($user){
			$results = [];

			//змінні, які приходять
			$file_id = isset($request->file_id) ? $request->file_id : null;
			$file_name = isset($request->name) ? $request->name : '';
			$confidence_id = isset($request->confidence) ? $request->confidence : '';
			$source_id = isset($request->source) ? $request->source : '';

			//якщо користувач має дозвіл
			if($this->is_approved_user($user)){

				//потрібно зібрати рядок, для запису файлу в базу даних
				$results['data'] = array(
					'name' => $file_name,
					'confidence_id' => $confidence_id,
					'source_id' => $source_id
				);
				if(isset($request->community_id)){
					$results['data']['community_id'] = $request->community_id;
				}
				if(isset($request->year_id)){
					$results['data']['year_id'] = $request->year_id;
				}
				if(empty($results['data']['community_id'])){
					$results['data']['community_id'] = $user->community_id;
				}

				$file_extension = 'pdf';
				$file_url = '';
				//якщо приходить файл, тоді потрібно завантажити його і записати в масив посилання та розширення
				if($request->hasFile('file')){
					$file_extension = explode('.', $request->filename);
					$file_extension = $file_extension[count($file_extension) - 1];
					$file_url = $request->file('file')->store('public/uploads');
					$results['data']['file'] = $file_url;
					$results['data']['extension'] = $file_extension;
				}

				//якщо приходить file_id - тоді це редагування файлу
				if($file_id){
					$file = Files::where('id', $file_id)->get()->first();
					if(!empty($file)){
						$file_extension = $file->extension;
						DB::table('files')->where('id', $request->file_id)->update($results['data']);
					}
				}else{
					if($request->hasFile('file')){
						$file_id = DB::table('files')->insertGetId($results['data']);
					}else{
						return $this->send_error('файлу не існує', 404);
					}
				}

				//якщо приходить payload, тоді форма відкривається з показників і потрібно прикріпити файл до метрик
//				$measures_value_id = null;
//				$measures_value = null;
//				if(isset($request->community_id) && isset($request->measure_id) && isset($request->year_id)){
//					$query = DB::table('measures_values as v')->where([['id', $request->measure_id], ['community_id', $request->community_id], ['year_id', $request->year_id]]);
//					$measures_value = $query->get()->first();
//					if(!empty($measures_values)){
//						$measures_value_id = $measures_value->id;
//						DB::table('measures_values')->where('id', $measures_value_id)->update(
//							array('file_id' => $file_id, 'status' => 'active')
//						);
//					}
//				}

				$confidence = Confidences::where('id', $request->confidence)->select('id', 'icon', 'name')->get()->first()->toArray();
				$source = Sources::where('id', $request->source)->select('name')->get()->first();

				//якщо форму відкрили зі списку файлів, тоді має бути одне повернення, якщо із показників тоді інше
				//також тоді потрібно повернути рядок як на сторінці показників
				if(isset($request->payload)){
					$measures = json_decode($request->payload);
					if(is_array($measures)){
						$results['data'] = [];
						foreach($measures as $measure){
							//якщо приходить id, тоді значення вже збережене в базі та потрібно оновити файл
							//якщо ні, тоді потрібно створити значення показника і зберегти його
							$measure_id = $measure->measure_id . '_' . $measure->community_id . '_' . $measure->year_id;
							$measure_repo = Measures_values::where([['measure_id', $measure->measure_id], ['community_id', $measure->community_id], ['year_id', $measure->year_id]])->get()->first();
							$calculation = new CalculationController;
							if(!empty($measure_repo)){
								DB::table('measures_values')->where('id', $measure_repo->id)->update(
									array('file_id' => $file_id, 'status' => 'active')
								);
								$calculation->calculate_indicator_by_measures($measure_repo->community_id, $measure_repo->year_id, [$measure_repo->measure_id]);
							}else{
								$measure->id = DB::table('measures_values')->insertGetId(
									[
										'measure_id' => $measure->measure_id,
										'community_id' => $measure->community_id,
										'year_id' => $measure->year_id,
										'file_id' => $file_id,
										'value' => !empty($measure->value) ? $measure->value : 0,
										'status' => $measure->status
									]
								);
								$calculation->calculate_indicator_by_measures($measure->community_id, $measure->year_id, [$measure->measure_id]);
							}

							//обрахувати загальні значення знову, оскільки файл міг бути доданий до заповненого показника
							$calculation->calculate_groups_total($measure->community_id, $measure->year_id);
							$calculation->calculate_year_total($measure->community_id, $measure->year_id);

							//записати оновлений вигляд рядка в data
							$results['data'][] = [
								'id' => $measure->measure_id . '_' . $measure->community_id . '_' . $measure->year_id,
								'measure_id' => $measure->measure_id,
								'community_id' => $measure->community_id,
								'year_id' => $measure->year_id,
								'checked' => false,
								'status' => $measure->status,
								'name' =>$measure->name,
								'value' => !empty($measure->value) ? $measure->value : 0,
								'measure' => $measure->measure,
								'file' => [
									'id' => $file_id,
									'icon' => $confidence['icon'],
									'name' => $file_name,
									'url' => !empty($file_url) ? $this->url . Storage::url($file_url) : '',
								],
								'actions' => ['unlink']
							];
						}
					}
				}else{
					$results['data'] = [
						'type' => $confidence,
						'id' => $file_id,
						'name' => $file_name,
						'format' => $file_extension,
						'source' => !empty($source) ? $source->name : '',
						'file' => array(
							'icon' => $confidence['icon'],
							'name' => $file_name,
							'url' => !empty($file_url) ? $this->url . Storage::url($file_url) : '',
						),
						'source_id' => $source_id,
						'actions' => ['edit', 'delete']
					];
				}

				return $this->send_response($results, 'Зміни збережено');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function get_file_types(Request $request){
		$user = Auth::user();
		if($user){
			if($this->is_approved_user($user)){
				$confidences = Confidences::select('id as value', 'name as label', 'icon')->get()->toArray();
				$results = array(
					'objType' => 'files/type',
					'label' => 'Тип',
					'value' => null,
					'options' => $confidences
				);
				return $this->send_response($results, 'Зміни збережено');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function edit_file_types(Request $request){
		$user = Auth::user();
		if($user){
			$results = [];
			if($this->is_approved_user($user)){
				$results['data'] = [];
				if(isset($request->payload)){
					foreach($request->payload as $item){
						DB::table('files')->where('id', $item['id'])->update(['confidence_id' => $request->value]);
						$results['data'][] = $this->get_file_row($item['id']);
					}
				}
				return $this->send_response($results, 'Зміни збережено');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function get_file_sources(Request $request){
		$user = Auth::user();
		if($user){
			if($this->is_approved_user($user)){
				$sources = Sources::select('id as value', 'name as label')->get()->toArray();
				$results = array(
					'objType' => 'files/source',
					'label' => 'Джерело',
					'value' => null,
					'options' => $sources
				);
				return $this->send_response($results, 'Зміни збережено');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function edit_file_sources(Request $request){
		$user = Auth::user();
		if($user){
			$results = [];
			if($this->is_approved_user($user)){
				$results['data'] = [];
				if(isset($request->payload)){
					foreach($request->payload as $item){
						DB::table('files')->where('id', $item['id'])->update(['source_id' => $request->value]);
						$results['data'][] = $this->get_file_row($item['id']);
					}
				}
				return $this->send_response($results, 'Зміни збережено');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function edit_file_link(Request $request){
		$user = Auth::user();
		if($user){
			$results = array();
			if($this->is_approved_user($user)){


				return $this->send_response($results, 'Зміни збережено');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function edit_file_unlink(Request $request){
		$user = Auth::user();
		if($user){
			$results = [];
			if($this->is_approved_user($user)){
				$results['data'] = [];
				$calculation = new CalculationController;
				$community_id = false; $year_id = false;
				foreach($request->request as $item){
					$measure_value = Measures_values::where([['measure_id', $item['measure_id']], ['community_id', $item['community_id']], ['year_id', $item['year_id']]])->get()->first();
					$measure = Measures::where('id', $item['measure_id'])->get()->first();
					if(!empty($measure_value) && !empty($measure)){
						DB::table('measures_values')->where('id', $measure_value->id)->update(
							array('file_id' => null, 'status' => 'active')
						);
						$community_id = $measure_value->community_id;
						$year_id = $measure_value->year_id;
						$calculation->calculate_indicator_by_measures($measure_value->community_id, $measure_value->year_id, [$measure_value->measure_id]);
						$results['data'][] = [
							'id' => $measure_value->measure_id . '_' . $measure_value->community_id . '_' . $measure_value->year_id,
							'measure_id' => $measure_value->measure_id,
							'community_id' => $measure_value->community_id,
							'year_id' => $measure_value->year_id,
							'checked' => false,
							'status' => $measure_value->status,
							'name' => $measure->name,
							'value' => !empty($measure_value->value) ? $measure_value->value : 0,
							'measure' => $measure->dimension,
							'file' => [
								'icon' => '',
								'name' => 'Немає файлів',
								'url' => '',
							],
							'actions' => ['link']
						];
					}
				}

				if($community_id && $year_id){
					$calculation->calculate_groups_total($community_id, $year_id);
					$calculation->calculate_year_total($community_id, $year_id);
				}
				return $this->send_response($results, 'Зміни збережено');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function edit_file_delete(Request $request){
		$user = Auth::user();
		if($user){
			$results = array();
			if($this->is_approved_user($user)){
				if(isset($request->file_id)){
					$this->delete_file($request->file_id);
				}elseif(isset($request->payload)){
					foreach($request->payload as $item){
						if(isset($item['id'])){
							$this->delete_file($item['id']);
						}
					}
				}
				return $this->send_response($results, 'Зміни збережено');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function delete_file($file_id){
		DB::table('files')->where('id', $file_id)->delete();
		$measures = Measures_values::where('file_id', $file_id)->get();
		$community_id = false; $year_id = false;
		$calculation = new CalculationController;
		//обрахувати загальні значення знову, оскільки файл міг бути доданий до заповненого показника
		foreach($measures as $measure){
			$community_id = $measure->community_id;
			$year_id = $measure->year_id;
			DB::table('measures_values')->where('id', $measure->id)->update(
				array('file_id' => null)
			);
			$calculation->calculate_indicator_by_measures($measure->community_id, $measure->year_id, [$measure->measure_id]);
		}

		if($community_id && $year_id){
			$calculation->calculate_groups_total($community_id, $year_id);
			$calculation->calculate_year_total($community_id, $year_id);
		}
	}

	public function get_file_row($file_id){
		$file = DB::table('files as f')
           ->where('f.id', $file_id)
           ->join('sources as s', 's.id', '=', 'f.source_id')
           ->join('confidences as c', 'c.id', '=', 'f.confidence_id')
           ->select('f.id', 'f.file', 'f.name', 'f.extension', 's.id as source_id', 's.name as source_name', 'c.id as confidence_id', 'c.name as confidence_name', 'c.icon as confidence_icon')
		   ->get()->first();
		if(!empty($file)){
			return [
				'type' => array(
					'id' => $file->confidence_id,
					'icon' => $file->confidence_icon,
					'name' => $file->confidence_name
				),
				'id' => $file->id,
				'name' => $file->name,
				'format' => $file->extension,
				'source' => $file->source_name,
				'file' => array(
					'icon' => $file->confidence_icon,
					'name' => $file->name,
					'url' => $this->url . Storage::url($file->file)
				),
				'source_id' => $file->source_id,
				'actions' => array('edit', 'delete'),
			];
		}
		return [];
	}
}