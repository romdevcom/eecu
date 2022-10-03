<?php
namespace App\Http\Controllers\API;

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
use App\Models\Total_values;
use App\Models\Numbers;
use App\Models\Groups;
use App\Models\Sectors;
use App\Models\Indicators;
use App\Models\Communities;
use App\Models\Logs;

class DataController extends BaseController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	/** @OA\Get(
	 * path="/api/admin/data/indicators",
	 * summary="Отримати масив індикаторів",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="IndicatorsGetPage",
	 * tags={"Індикатори"},
	 * security={ {"bearer": {} }},
	 * @OA\Response(
	 *    response=200,
	 *    description="Буде повернено масив з джерелами",
	 * ),
	 * @OA\Response(
	 *    response=401,
	 *    description="Відповідь, якщо користувач неавторизований",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="unauthorised"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=403,
	 *    description="Помилка, якщо користувач не має прав отримати ці дані",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="forbidden"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=404,
	 *    description="Даних не знайдено",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="data not found"),
	 *    )
	 * )
	 * )
	 */
	public function get_indicators(Request $request){
		$user = Auth::user();
		if($user){
			if(in_array($user->role_id, $this->allowed_roles)){
				$model_groups = new Groups;
				$select_groups = 'name as name,id as value';
				$groups = $this->get($model_groups, $select_groups, [['status', 'active']]);

				$model_sources = new Sources;
				$select_sources = 'name as name,id as value';
				$sources = $this->get($model_sources, $select_sources, [['status', 'active']]);

				$results = array(
					'pageTitle' => 'Індикатори',
					'quantity' => 0,
					'search' => array(
						'name' => 'Пошук за назвою ...',
						'value' => ''
					),
					'filter' => array(
						'measure' => array(
							'name' => 'Група показників',
							'list' => $groups
						),
						'source' => array(
							'name' => 'Джерело',
							'list' => $sources
						)
					)
				);
				$headers = array(
					$this->table_header_col('Назва показника', 'name', false),
					$this->table_header('Чисельник', 'numerator', false),
					$this->table_header('Знаменник', 'denominator', false),
					$this->table_header('Од.виміру', 'dimension', false)
				);

				//формування запиту для отримання індикаторів
				$query = DB::table('indicators as i');
				$query->join('measures as n', 'n.id', '=', 'i.numerator_id');
				$query->join('measures as d', 'd.id', '=', 'i.denominator_id');
				if(isset($request->measure)){
					$query->where('group_id', $request->measure);
				}
				if(isset($request->source)){
					$query->where('sector_id', $request->source);
				}
				$list = $query->select('i.name as name', 'n.name as numerator', 'd.name as denominator', 'i.dimension')->get();

				$rows = [];
				$quantity = 0;
				if(!empty($list)){
					$quantity = count($list);
					foreach($list as $item){
						array_push(
							$rows,
							array(
								'name' => str_replace('\n', '', $item->name),
								'numerator' => str_replace('\n', '', $item->numerator),
								'denominator' => str_replace('\n', '', $item->denominator),
								'dimension' => str_replace('\n', '', $item->dimension),
							)
						);
					}
				}
				$results['table'] = $this->table('Індикатори', $headers, $rows, '', $quantity);
				return $this->send_response($results, 'success');
			}else{
				return $this->send_error('forbidden', 403);
			}
		}
		return $this->send_error('unauthorised', 401);
	}

	public function get_measures(Request $request){
		$user = Auth::user();
		if($user){
			if(in_array($user->role_id, $this->allowed_roles)){
				$model_groups = new Groups;
				$select_groups = 'name as name,id as value';
				$groups = $this->get($model_groups, $select_groups, [['status', 'active']]);

				$model_sources = new Sources;
				$select_sources = 'name as name,id as value';
				$sources = $this->get($model_sources, $select_sources, [['status', 'active']]);

				$results = array(
					'pageTitle' => 'Показники',
					'quantity' => 0,
					'search' => array(
						'name' => 'Пошук за назвою ...',
						'value' => ''
					),
					'filter' => array(
						'measure' => array(
							'name' => 'Група показників',
							'list' => $groups
						),
						'source' => array(
							'name' => 'Джерело',
							'list' => $sources
						)
					)
				);
				$headers = array(
					$this->table_header_col('Назва показника', 'name', false),
					$this->table_header('Од.виміру', 'dimension', false),
					$this->table_header('Точність', 'precision', false),
					$this->table_header('Джерело', 'source', false),
				);

				//формування запиту для отримання показників
				$query = DB::table('measures as m');
				$query->join('sources as s', 's.id', '=', 'm.source_id');
				if(isset($request->measure)){
					$query->where('m.source_id', $request->measure);
				}
				if(isset($request->source)){
					$query->join('measures_x_sources as x', 'm.id', '=', 'x.measures_id');
					$query->where('x.sources_id', $request->source);
				}
				$list = $query->select('m.name as name', 's.name as source', 'm.precision', 'm.dimension')->get();

				$rows = [];
				$quantity = 0;
				if(!empty($list)){
					$quantity = count($list);
					foreach($list as $item){
						$rows[] = [
							'name' => str_replace('\n', '', $item->name),
							'dimension' => str_replace('\n', '', $item->dimension),
							'precision' => str_replace('\n', '', $item->precision),
							'source' => str_replace('\n', '', $item->source),
						];
					}
				}
				$results['table'] = $this->table('Показники', $headers, $rows, '', $quantity);
				return $this->send_response($results, 'success');
			}else{
				return $this->send_error('community not found', 404);
			}
		}
		return $this->send_error('unauthorised');
	}

	public function get_sources(Request $request){
		$user = Auth::user();
		if($user){
			if(in_array($user->role_id, $this->allowed_roles)){
				$model_sources = new Sources;
				$select_sources = 'id,name,status';
				$sources = $this->get($model_sources, $select_sources, [['status', 'active'],['public', 1]]);
				$results = array(
					'pageTitle' => 'Джерела'
				);
				$headers = array(
					$this->table_header_col('Назва', 'name', false),
				);
				$rows = array();
				$quantity = 0;
				if(!empty($sources)){
					$quantity = count($sources);
					foreach($sources as $item){
						$rows[] = [
							'status' => str_replace('\n', '', $item->status),
							'name' => str_replace('\n', '', $item->name)
						];
					}
				}
				$results['table'] = $this->table('Джерела', $headers, $rows, '', $quantity);
				return $this->send_response($results, 'success');
			}else{
				return $this->send_error('community not found', 404);
			}
		}
		return $this->send_error('unauthorised');
	}

	public function get_messages(Request $request){
		$user = Auth::user();
		if($user){
			if(in_array($user->role_id, $this->allowed_roles)){
				$headers = array(
					$this->table_header_col('Подія', 'event', false),
					$this->table_header('Дата', 'date', false),
					$this->table_header('Громада', 'community', false),
					$this->table_header('Користувач', 'user', false),
					$this->table_header_col('Дія', 'buttons', false, 'btn')
				);

				$rows = [];
				$count = 0;

				//отримати всі неактивовані громади
				$communities = Communities::where('status', '!=', 'active')->get();
				foreach($communities as $community){
					$count++;
					$rows[] = [
						'event' => 'Громаду потрібно затвердити',
						'date' => $community->created_at,
						'community' => $community->name,
						'user' => '',
						'path' => '/admin/communities',
						'buttons' => ['view'],
					];
				}

				//отримати всіх неактивованих користувачів
				$users = User::whereIn('status', ['blocked', 'unverified', 'verified'])->get();
				foreach($users as $user){
					$count++;
					if(isset($user->community_id) && !empty($user->community_id)){
						$community = Communities::where('id', $user->community_id)->get()->first();
					}
					switch($user->status){
						case 'blocked':
							$link = '/admin/users/blocked';
							break;
						case 'unverified':
							$link = '/admin/users/unverified';
							break;
						case 'verified':
							$link = '/admin/users/requests';
							break;
						default:
							$link = '/admin/users/active';
					}
					$rows[] = [
						'event' => 'Користувача потрібно затвердити',
						'date' => $user->created_at,
						'community' => isset($community) && !empty($community) ? $community->name : '',
						'user' => $user->name,
						'path' => $link,
						'buttons' => ['view'],
					];
				}

				//отримати всі роки подані на верифікацію
				$totals = Total_values::where([['type', 'year'], ['status', 'for approval']])->get();
				foreach($totals as $total){
					$count++;
					if(isset($total->community_id) && !empty($total->community_id)){
						$community = Communities::where('id', $total->community_id)->get()->first();
					}
					$rows[] = [
						'event' => 'Потрібно верифікувати ' . $total->year_name . ' для громади',
						'date' => $total->created_at,
						'community' => isset($community) && !empty($community) ? $community->name : '',
						'user' => '',
						'path' => isset($community) && !empty($community) ? '/admin/communities/' . $community->slug : '/admin/communities',
						'buttons' => ['view'],
					];
				}


				//зміна заголовків сторінки відносно до статусу
				switch($request->status){
					case 'action':
						$page_title = 'Потребують дії';
						break;
					case 'archive':
						$page_title = 'Архів';
						break;
					default:
						$page_title = 'Повідомлення';
				}

				$results = array(
					'pageTitle' => $page_title,
					'table' => $this->table('', $headers, $rows, 'messages', $count . ' повідомлень')
				);
				return $this->send_response($results, 'success');
			}
		}
		return $this->send_error('unauthorised');
	}
}