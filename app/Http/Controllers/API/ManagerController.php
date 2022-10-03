<?php
namespace App\Http\Controllers\API;

use Couchbase\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
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
use App\Models\Confidences;
use App\Models\Total_values;

class ManagerController extends BaseController
{
	//в totals є 3 статуси:
	//waiting - створене, менеджер не подавав на верифікації та ще наповнює дані
	//for approval - менеджер подав на верифікацію
	//approved - адміністратор верифікував дані


	/** @OA\Get(
	 * path="/api/manager/community",
	 * summary="Отримати дані профіля",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="get_manager_community",
	 * tags={"Менеджер"},
	 * security={ {"bearer": {} }},
	 *@OA\Response(
	 *    response=200,
	 *    description="Буде повернено форма для користувача",
	 *),
	 * @OA\Response(
	 *    response=401,
	 *    description="",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Not authorized"),
	 *    )
	 * )
	 * )
	 */
	public function get_manager_community(Request $request){
		$user = Auth::user();
		if($user){
			$community = Communities::where([['id', $user->community_id], ['status', 'active']])->get()->first();
			$eea_status = $community->eea_member == 1 ? 'verification' : 'dataentry';
			$eea_title = $community->eea_member == 1 ? 'Подано на верифікацію' : 'Громада не є членом ЄЕВ';
			if($eea_status == 'verification' && $community->eea_status){
				$eea_title = 'Затверджено адміністратором';
				$eea_status = 'approved';
			}
			$results = array(
				'pageTitle' => 'Профіль громади',
				'community' => array(
					'id' => $community->id,
					'slug' => $community->slug,
					'objType' => 'communities',
					'profileForm' => array(
						'name' => array(
							'field_type' => 'text',
							'label' => 	'Назва',
							'value' => $community->name
						),
						'chief' => array(
							'field_type' => 'text',
							'label' => 	'Керівник громади',
							'value' => $community->chief
						),
						'contact_person' => array(
							'field_type' => 'text',
							'label' => 	'Контактна особа',
							'value' => $community->contact_person
						),
						'phone' => array(
							'field_type' => 'phone',
							'label' => 	'Телефон',
							'value' => $community->phone
						),
						'email' => array(
							'field_type' => 'email',
							'label' => 	'Email',
							'value' => $community->email
						),
						'geo' => array(
							'field_type' => 'text',
							'label' => 	'Геодані',
							'value' => $community->lat . ', ' . $community->lng
						)
					),
					'picture' => array(
						'url' => !empty($user->picture) ? $this->url . Storage::url($user->picture) : $this->default_picture,
					)
				),
				'participant' => array(
					'ttl' => $eea_title,
					'subttl' => $eea_status != 'approved' ? 'При внесенні змін, дані потрібно буде верифікувати адміністратором.' : '',
					'status' => $eea_status,
					'objType' => 'communities/eea',
					'verifyForm' => array(
						'year' => array(
							'field_type' => 'text',
							'label' => 	'З якого року ?',
							'value' => $community->eea_year
						),
						'rating' => array(
							'field_type' => 'text',
							'label' => 	'Рейтинг, %',
							'value' => $community->eea_value
						),
						'approval' => array(
							'field_type' => 'radio',
							'label' => 'Учасник системи сертифікації ЄЕВ ?',
							'value' => !empty($community->eea_member)
						)
					),
				)
			);
			return $this->send_response($results, 'Зміни збережено');
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Get(
	 * path="/api/manager/rating",
	 * summary="Отримати рейтинг громади",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="get_manager_rating",
	 * tags={"Менеджер"},
	 * security={ {"bearer": {} }},
	 *@OA\Response(
	 *    response=200,
	 *    description="Буде повернено форма для користувача",
	 *),
	 * @OA\Response(
	 *    response=401,
	 *    description="",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Not authorized"),
	 *    )
	 * )
	 * )
	 */
	public function get_manager_rating(Request $request){
		$user = Auth::user();
		if($user){
			$status_tooltip = array(
				'name' => 'Статус року',
				'items' => array(
					array(
						'icon' => 'ant-design:info-circle-outlined',
						'name' => 'Дані вносяться'
					),
					array(
						'icon' => 'ant-design:file-search-outlined',
						'name' => 'Подано на верифікацію'
					),
					array(
						'icon' => 'codicon:check-all',
						'name' => 'Верифіковано'
					),
				)
			);
			$headers = array(
				$this->table_header_col('Статус', 'status', false, 'ico', $status_tooltip),
				$this->table_header_col('Рік', 'year', true),
				$this->table_header('Загальний бал', 'totalPoints', false),
				$this->table_header_col('% заповненості', 'fillPercent', true, 'progress', false, false, 'yellow'),
				$this->table_header_col('% верифікації', 'approvePercent', true, 'progress', false, false, 'green'),
				$this->table_header_col('', 'buttons', false, 'btn'),
			);

			$rows = [];
			$years = Years::orderBy('name', 'desc')->get();
			$totals = Total_values::where([['community_id', $user->community_id], ['type', 'year']])->get();
			foreach($years as $year){
				$year_total_exist = false;
				if(!empty($totals)){
					foreach($totals as $total){
						if($total->year_id == $year->id){
							$year_total_exist = true;
							switch($total->status){
								case 'for approval':
									$total_status = 'verification';
									$total_buttons = ['view'];
									break;
								case 'approved':
									$total_status = 'approved';
									$total_buttons = ['view'];
									break;
								default:
									$total_status = 'dataentry';
									$total_buttons = ['validate', 'view'];
							}
							$rows[] = [
								//'id' => $total->id,
								'id' => $user->community_id . '_' . $total->year_id,
								'status' => $total_status,
								'year' => $total->year_name,
								'year_id' => $total->year_id,
								'community_id' => intval($user->community_id),
								'totalPoints' => $total->status == 'approved' ? intval($total->value) : '---',
								'slug' => $total->year_name,
								'fillPercent' => $total->percent_values,
								'approvePercent' => $total->percent_approved_values,
								'buttons' => $total_buttons
							];
						}
					}
				}
				if(!$year_total_exist){
					$rows[] = [
						'id' => $user->community_id . '_' . $year->id,
						'status' => 'dataentry',
						'year' => $year->name,
						'year_id' => $year->id,
						'community_id' => intval($user->community_id),
						'totalPoints' => '---',
						'slug' => $year->name,
						'fillPercent' => 0,
						'approvePercent' => 0,
						'buttons' => ['validate', 'view']
					];
				}
			}


			$results = array();
			$results['community_id'] = $user->community_id;
			$results['table'] = $this->table('Загальні дані', $headers, $rows, 'communities/year');
			return $this->send_response($results, 'Зміни збережено');
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Get(
	 * path="/api/manager/rating/{year}",
	 * summary="Отримати рейтинг громади по року",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="get_manager_rating_year",
	 * tags={"Менеджер"},
	 * security={ {"bearer": {} }},
	 *@OA\Response(
	 *    response=200,
	 *    description="Буде повернено форма для користувача",
	 *),
	 * @OA\Response(
	 *    response=401,
	 *    description="",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Not authorized"),
	 *    )
	 * )
	 * )
	 */
	public function get_manager_rating_year(Request $request){
		$user = Auth::user();
		$year = Years::where('name', $request->year_id)->get()->first();
		if($user && !empty($year)){
			$community = Communities::where([['id', $user->community_id], ['status', 'active']])->get()->first();
			if(!empty($community)){
				if($this->is_admin_or_approved_manager($user, $community->id)){
					$model_groups = new Groups;
					$select_groups = 'name as name,id as value';
					$filter_groups = $this->get($model_groups, $select_groups, [['status', 'active']]);

					$model_sources = new Sources;
					$select_sources = 'name as name,id as value';
					$filter_sources = $this->get($model_sources, $select_sources, [['status', 'active']]);

					$year_total = Total_values::where([['community_id', $community->id], ['year_id', $year->id], ['type', 'year']])->get()->first();
					$event_name = '';
					if(!empty($year_total)){
						switch($year_total->status){
							case 'for approval':
								$button_text = 'Переглянути';
								$year_icon = 'ant-design:info-circle-outlined';
								$year_action = false;
								$year_status = 'verification';
								$year_status_name = 'На верифікації';
								break;
							case 'approved':
								$button_text = 'Переглянути';
								$year_icon = 'codicon:check-all';
								$year_action = false;
								$year_status = 'approved';
								$year_status_name = 'Верифіковано';
								break;
							default:
								$button_text = 'Редагувати';
								$year_icon = '';
								$year_action = true;
								$year_status = 'dataentry';
								$event_name = 'validate';
								$year_status_name = 'Подати на верифікацію';
						}
					}else{
						$button_text = 'Редагувати';
						$year_icon = '';
						$year_action = true;
						$year_status = 'dataentry';
						$event_name = 'validate';
						$year_status_name = 'Подати на верифікацію';
					}

					$groups_modal = new Groups;
					$groups_select = 'id,name,icon,formula';
					$groups_list = $this->get($groups_modal, $groups_select, [['status', 'active']]);
					$groups = array();
					foreach($groups_list as $item){
						$item_total = Total_values::where([['community_id', $community->id], ['year_id', $year->id], ['group_id', $item->id], ['type', 'group']])->get()->first();
						$progress = 0;
						if(!empty($item_total)){
							$progress = in_array($year_status, ['unverified', 'dataentry']) ? $item_total->percent_values : $item_total->percent_approved_values;
						}
						$groups[] = [
							'btnText' => $button_text,
							'name' => $item->name,
							'pic' => $item['icon'],
							'progress' => $progress,
							'filterValue' => $item->id
						];
					}

					//отримати файли для цього року
					$files = DB::table('files as f')
					           ->join('confidences as c', 'c.id', '=', 'f.confidence_id')
					           ->where([['f.community_id', $community->id], ['f.year_id', $year->id]])
					           ->select('f.name', 'f.file', 'c.icon')->distinct('f.id')
					           ->get()->toArray();
					//пройтись по файлах і якщо існує файл, тоді додати посилання
					foreach($files as $file){
						if(!empty($file->file)){
							$file->path = $this->url . Storage::url($file->file);
						}else{
							$file->path = '';
						}
					}

					$confidences = Confidences::select('name', 'icon')->get()->toArray();

					$results = array(
						'pageTitle' => $community->name,
						'community_id' => $community->id,
						'year_id' => $year->id,
						'status' => array(
							'key' => $year_status,
							'name' => $year_status_name,
							'icon' => $year_icon,
							'isAction' => $year_action,
							'evtName' => $event_name,
							'community_id' => $community->id,
							'year_id' => $year->id,
							'objType' => 'communities/year'
						),
						'search' => array(
							'name' => 'Пошук за назвою ...',
							'value' => ''
						),
						'filter' => array(
							'measure' => array(
								'name' => 'Група показників',
								'list' => $filter_groups
							),
							'source' => array(
								'name' => 'Джерело',
								'list' => $filter_sources
							)
						),
						'group' => $groups,
						'files' => array(
							'quantity' => count($files),
							'tooltip' => array(
								'name' => 'Типи джерел',
								'items' => $confidences
							),
							'list' => $files
						)
					);
					return $this->send_response($results, 'Зміни збережено');
				}
				return $this->send_error('forbidden', 403);
			}
			return $this->send_error('not found', 404);
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Get(
	 * path="/api/manager/rating/{year}/metric",
	 * summary="Отримати дані та форму для громади відносно року та групи (з гет параметром measure)",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="get_manager_rating_year_metric",
	 * tags={"Менеджер"},
	 * security={ {"bearer": {} }},
	 *@OA\Response(
	 *    response=200,
	 *    description="Буде повернено ід громади, рік та масив status",
	 *),
	 * @OA\Response(
	 *    response=401,
	 *    description="",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Not authorized"),
	 *    )
	 * )
	 * )
	 */
	public function get_manager_rating_year_metric(Request $request){
		$user = Auth::user();
		$year = Years::where('name', $request->year_id)->get()->first();
		if($user && !empty($year)){
			$community = Communities::where([['id', $user->community_id], ['status', 'active']])->get()->first();
			if(!empty($community)){
				if($this->is_admin_or_approved_manager($user, $community->id)){
					$model_groups = new Groups;
					$select_groups = 'name as name,id as value';
					$filter_groups = $this->get($model_groups, $select_groups, [['status', 'active']]);

					$model_sources = new Sources;
					$select_sources = 'name as name,id as value';
					$filter_sources = $this->get($model_sources, $select_sources, [['status', 'active']]);

					//отримати внесені значення
					$query = DB::table('measures_values as v')->where([['community_id', $community->id], ['year_id', $year->id]]);
					$query->join('measures as m', 'm.id', '=', 'v.measure_id');
					if(isset($request->measure)){
						$query->join('measures_x_groups as xg', 'm.id', '=', 'xg.measures_id');
						$query->where('groups_id', $request->measure);
					}
					if(isset($request->source)){
						$query->join('measures_x_sources as xs', 'm.id', '=', 'xs.measures_id');
						$query->where('sources_id', $request->source);
					}
					$query->select('v.id', 'v.year_id', 'v.measure_id', 'v.file_id', 'v.value', 'v.status', 'm.name', 'm.dimension');
					$measures_values = $query->distinct('v.id')->get();
					$measures_values_array = array();
					foreach($measures_values as $measures_value){
						$measures_values_array[$measures_value->measure_id] = array(
							'id' => $measures_value->id,
							'value' => $measures_value->value,
							'file_id' => $measures_value->file_id,
							'status' => $measures_value->status
						);
					}

					//отримати всі можливі показники для того, щоб їх заповнювати
					$query = DB::table('measures as m');
					if(isset($request->measure)){
						$query->join('measures_x_groups as xg', 'm.id', '=', 'xg.measures_id');
						$query->where('xg.groups_id', $request->measure);
					}
					if(isset($request->source)){
						$query->join('measures_x_sources as xs', 'm.id', '=', 'xs.measures_id');
						$query->where('xs.sources_id', $request->source);
					}
					if(isset($request->search)){
						$query->where('m.name', 'LIKE', '%' . $request->search . '%');
					}
					$measures = $query->select('m.id', 'm.name', 'm.dimension')->distinct('m.id')->get();

					//якщо рік вже поданий на верифікацію, тоді не дозволяти редагувати рядки
					$year_total = Total_values::where([['community_id', $community->id], ['year_id', $year->id], ['type', 'year']])->get()->first();
					if(!empty($year_total->status) && in_array($year_total->status, ['for approval', 'approved'])){
						$global_status = $year_total->status == 'for approval' ? 'dataentry' : 'approved';
						$headers = array(
							$this->table_header_col('Назва показника', 'name', false),
							$this->table_header_col('Значення', 'value', false, 'text'),
							$this->table_header('Од. виміру', 'measure', false),
							$this->table_header_col('Файл', 'file', false, 'file'),
						);
					}else{
						$global_status = 'active';
						$headers = array(
							$this->table_header_col('', 'checked', false, 'chkb'),
							$this->table_header_col('Назва показника', 'name', false),
							$this->table_header_col('Значення', 'value', false, 'input'),
							$this->table_header('Од. виміру', 'measure', false),
							$this->table_header_col('Файл', 'file', false, 'file'),
							$this->table_header_col('', 'actions', false, 'action'),
						);
					}
					$rows = array();
					foreach($measures as $measure){
						$measures_status = isset($measures_values_array[$measure->id]) ? $measures_values_array[$measure->id]['status'] : '';
						$measures_file = array('icon' => '', 'name' => 'Немає файлів', 'url' => '');
						$measures_file_exist = false;
						$measures_value = '';
						if(isset($measures_values_array[$measure->id]) && !empty($measures_values_array[$measure->id])){
							$measures_value = $measures_values_array[$measure->id]['value'];
							$measures_file_query = DB::table('files as f');
							$measures_file_query->join('confidences as c', 'f.confidence_id', '=', 'c.id');
							$measures_file_query->where('f.id', $measures_values_array[$measure->id]['file_id']);
							$measures_file = $measures_file_query->select('f.id', 'f.name', 'f.file as url', 'c.icon')->get()->toArray();
							if(!empty($measures_file) && isset($measures_file[0])){
								$measures_file = $measures_file[0];
								$measures_file_exist = !empty($measures_file);
								$measures_file->url = URL::to('/') . Storage::url($measures_file->url);
							}else{
								$measures_file = array('icon' => '', 'name' => 'Немає файлів', 'url' => '');
							}
						}

						$row_id = $measure->id . '_' . $community->id . '_' . $year->id;
						$rows[] = [
							'id' => $row_id,
							'measure_id' => $measure->id,
							'community_id' => $community->id,
							'year_id' => $year->id,
							'checked' => false,
							'status' => $global_status,
							'name' => $measure->name,
							'value' => $measures_value,
							'measure' => $measure->dimension,
							'file' => $measures_file,
							'actions' => $measures_file_exist ? ['unlink'] : ['link']
						];
					}

					$results = array(
						'pageTitle' => $community->name,
						'community_id' => $community->id,
						'year_id' => $year->id,
						'search' => array(
							'name' => 'Пошук за назвою ...',
							'value' => ''
						),
						'filter' => array(
							'measure' => array(
								'name' => 'Група показників',
								'list' => $filter_groups
							),
							'source' => array(
								'name' => 'Джерело',
								'list' => $filter_sources
							)
						),
						'table' => $this->table('Показники', $headers, $rows, 'measure-values', false, array('link', 'unlink'))
					);
					return $this->send_response($results, 'Зміни збережено');
				}
				return $this->send_error('forbidden', 403);
			}
			return $this->send_error('not found', 404);
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Get(
	 * path="/api/manager/rating/{year}/files",
	 * summary="Отримати дані та форму для громади відносно року та групи (з гет параметром measure)",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="get_manager_rating_year_files",
	 * tags={"Менеджер"},
	 * security={ {"bearer": {} }},
	 *@OA\Response(
	 *    response=200,
	 *    description="Буде повернено ід громади, рік та масив status",
	 *),
	 * @OA\Response(
	 *    response=401,
	 *    description="",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Not authorized"),
	 *    )
	 * )
	 * )
	 */
	public function get_manager_rating_year_files(Request $request){
		$user = Auth::user();
		$year = Years::where('name', $request->year_id)->get()->first();
		if($user && !empty($year)){
			$community = Communities::where([['id', $user->community_id], ['status', 'active']])->get()->first();
			if(!empty($community)){
				if($this->is_admin_or_approved_manager($user, $community->id)){
					$model_groups = new Groups;
					$select_groups = 'name as name,id as value';
					$filter_groups = $this->get($model_groups, $select_groups, [['status', 'active']]);

					$model_sources = new Sources;
					$select_sources = 'name as name,id as value';
					$filter_sources = $this->get($model_sources, $select_sources, [['status', 'active']]);

					$model_confidences = new Confidences;
					$select_confidences = 'name,icon,value';
					$filter_confidences = $this->get($model_confidences, $select_confidences);

					$tooltip = array(
						'name' => 'Типи джерел',
						'items' => $filter_confidences
					);

					$headers = array(
						$this->table_header_col('', 'checkbox', false, 'chkb'),
						$this->table_header_col('Тип', 'type', false, 'textIco', $tooltip),
						$this->table_header('Назва файлу', 'name', true),
						$this->table_header('Формат', 'format', true),
						$this->table_header('Джерело', 'source', true),
						$this->table_header_col('Файл', 'file', false, 'file'),
						$this->table_header_col('', 'buttons', true, 'btn'),
						$this->table_header_col('', 'actions', true, 'action'),
					);
					$rows = array();
					$query = DB::table('files as f')
			           ->where([['community_id', $community->id], ['year_id', $year->id]])
			           ->join('sources as s', 's.id', '=', 'f.source_id')
			           ->join('confidences as c', 'c.id', '=', 'f.confidence_id')
			           ->select('f.id', 'f.file', 'f.name', 'f.extension', 's.id as source_id', 's.name as source_name', 'c.id as confidence_id', 'c.name as confidence_name', 'c.icon as confidence_icon');
					if(isset($request->source)){
						$query->where('source_id', $request->source);
					}
					$files = $query->get();
					foreach($files as $file){
						array_push(
							$rows,
							array(
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
							)
						);
					}

					$results = array(
						'pageTitle' => $community->name,
						'community_id' => $community->id,
						'year_id' => $year->id,
						'filter' => array(
							'measure' => array(
								'name' => 'Група показників',
								'list' => $filter_groups
							),
							'source' => array(
								'name' => 'Джерело',
								'list' => $filter_sources
							),
							'type' => array(
								'name' => 'Тип',
								'list' => $filter_confidences
							)
						),
						'table' => $this->table('Редагувати файли', $headers, $rows, 'files', false)
					);

					return $this->send_response($results, 'Зміни збережено');
				}
				return $this->send_error('forbidden', 403);
			}
			return $this->send_error('not found', 404);
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Post(
	 * path="/api/manager/community/status",
	 * summary="Подати дані року на верифікацію",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="edit_manager_community_status",
	 * tags={"Менеджер"},
	 * security={ {"bearer": {} }},
	 *@OA\Response(
	 *    response=200,
	 *    description="Буде повернено ід громади, рік та масив status",
	 *),
	 * @OA\Response(
	 *    response=401,
	 *    description="",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Not authorized"),
	 *    )
	 * )
	 * )
	 */
	public function edit_manager_community_status(Request $request){
		$user = Auth::user();
		if($user){
			$data = json_decode($request->getContent());
			if(isset($data->payload->community_id) && isset($data->payload->year_id)){
				if($this->is_admin_or_approved_manager($user, $data->payload->community_id)){
					$results = array(
						'community_id' => $data->payload->community_id,
						'year_id' => $data->payload->year_id,
						'status' => array(
							'key' => 'verification',
							'name' => 'На верифікації',
							'icon' => '',
							'type' => 'text'
						)
					);
					DB::table('total_values')
					  ->where([['community_id', $data->payload->community_id], ['year_id', $data->payload->year_id]])
					  ->update(['status' => 'verification']);
					return $this->send_response($results, 'Зміни збережено');
				}
				return $this->send_error('forbidden', 403);
			}
			return $this->send_error('not found', 404);
		}
		return $this->send_error('unauthorised');
	}
}