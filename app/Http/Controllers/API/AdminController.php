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
use App\Models\Numbers;
use App\Models\Groups;
use App\Models\Sectors;
use App\Models\Indicators;
use App\Models\Communities;
use App\Models\Total_values;
use App\Models\Logs;

class AdminController extends BaseController
{
	public function get_rating(Request $request){
		$user = Auth::user();
		$results = [];
		if($user){
			if($this->is_admin($user)){
				$results = array(
					'pageTitle' => 'Дані рейтингу'
				);
				$tooltip = array(
					'name' => 'Статус року',
					'items' => array(
						array(
							'icon' => 'ant-design:info-circle-outlined',
							'name' => 'Рік вноситься'
						),
						array(
							'icon' => 'ant-design:file-search-outlined',
							'name' => 'Рік подано на верифікацію'
						),
						array(
							'icon' => 'codicon:check-all',
							'name' => 'Рік затверджений'
						)
					)
				);
				if(isset($request->year)){
					$year = Years::where('name', $request->year)->get()->first();
					if(!empty($year)){
						$totals = Total_values::where([['type', 'year'], ['year_id', $year->id]])->get();
						$rows = [];
						foreach($totals as $total){
							$community = Communities::where([['id', $total->community_id], ['status', 'active']])->get()->first();

							//визначення статусу, який подати для рядка
							$status = $total->status;
							$status = $status == 'for approval' ? 'verification' : $status;
							$status = $status == 'waiting' ? 'dataentry' : $status;

							$rows[] = [
								'status' => $status,
								'community' => $community->name,
								'filled' => $total->percent_values,
								'verified' => $total->percent_approved_values,
								'points' => intval($total->value),
								'path' => '/admin/communities/' . $community->slug . '/' . $request->year,
								'buttons' => array('view')
							];
						}
						$results['pageTitle'] .= ' (' . $request->year . ')';
						$results['table'] = [
							'name' => '',
							'quantity' => 15,
							'verified' => 10,
							'headers' => array(
								$this->table_header_col('Статус', 'status', false, 'ico', $tooltip),
								$this->table_header_col('Громада', 'community', false),
								$this->table_header('% заповненості', 'filled', false),
								$this->table_header('% верифікації', 'verified', false),
								$this->table_header('Бали', 'points', false),
								$this->table_header_col('', 'buttons', false, 'btn'),
							),
							'rows' => $rows
						];
						return $this->send_response($results, 'success');
					}
					return $this->send_error('not found', 404);
				}else{
					$rows = $this->get_year_rows();
					$headers = [
						$this->table_header_col('Статус', 'status', false, 'ico', $tooltip),
						$this->table_header('Рік', 'year', true),
						$this->table_header('К-сть громад', 'community', true),
						$this->table_header_col('Заповнюють дані', 'filled', true, 'textColor', false, false, 'grey'),
						$this->table_header_col('На перевірці', 'verified', true, 'textColor', false, false, 'red'),
						$this->table_header_col('Затверджено', 'active', true, 'textColor', false, false, 'green'),
						$this->table_header_col('', 'buttons', false, 'btn'),
					];
					$results['table'] = $this->table('', $headers, $rows, 'years', false);
					return $this->send_response($results, 'success');
				}
			}else{
				return $this->send_error('not found', 404);
			}
		}
		return $this->send_error('unauthorised');
	}

	public function edit_year(Request $request){
		$user = Auth::user();
		if($user){
			$results = [];
			if($this->is_admin($user)){
				$results['data'] = [];
				if(isset($request->year_id)){
					if(isset($request->community_id)){
						DB::table('years')->where('id', $request->year_id)->update(['status' => 'approved']);
					}else{
						DB::table('years')->where('id', $request->year_id)->update(['status' => 'dataentry']);
					}
					$results['data'] = $this->get_year_rows($request->year_id);
				}
				return $this->send_response($results, 'Зміни збережено');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function get_year_rows($year_id = false){
		$totals = $year_id ? Total_values::where([['type', 'year'], ['year_id', $year_id]])->get() : Total_values::where('type', 'year')->get();
		$row_key = array();
		foreach($totals as $total){
			$row_key[$total->year_id] = isset($row_key[$total->year_id]) ? $row_key[$total->year_id] : [
				'status' => 'dataentry',
				'year' => $total->year_name,
				'community' => 0,
				'community_id' => 1,
				'filled' => 0,
				'verified' => 0,
				'active' => 0,
				'slug' => $total->year_name,
				'buttons' => ['view']
			];
			$row_key[$total->year_id]['community'] = $row_key[$total->year_id]['community'] + 1;
			switch($total->status){
				case 'waiting':
					$row_key[$total->year_id]['status'] = 'waiting';
					$row_key[$total->year_id]['filled'] = $row_key[$total->year_id]['filled'] + 1;
					break;
				case 'for approval':
					$row_key[$total->year_id]['status'] = 'waiting';
					$row_key[$total->year_id]['verified'] = $row_key[$total->year_id]['verified'] + 1;
					break;
				case 'approved':
					$row_key[$total->year_id]['active'] = $row_key[$total->year_id]['active'] + 1;
					break;
			}

		}
		$rows = [];
		foreach ($row_key as $key => $row_item){
			$year = Years::where('id', $key)->get()->first();
			if(!empty($year)){
				$row_item['id'] = $year->id;
				$row_item['year_id'] = $year->id;
				$row_item['status'] = $year->status;
				if($year->status == 'dataentry'){
					$row_item['buttons'][] = 'approve';
				}else{
					$row_item['buttons'][] = 'block';
				}
			}
			$rows[] = $row_item;
		}
		return $rows;
	}

	public function get_logs_check(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			if(in_array($user->role_id, $this->allowed_roles)){
				$results['count'] = 0;
				//отримати всі неактивовані громади
				$communities = Communities::where('status', '!=', 'active')->get();
				foreach($communities as $community){
					$results['count']++;
				}

				//отримати всіх неактивованих користувачів
				$users = User::whereIn('status', ['blocked', 'unverified', 'verified'])->get();
				foreach($users as $user){
					$results['count']++;
				}

				//отримати всі роки подані на верифікацію
				$totals = Total_values::where([['type', 'year'], ['status', 'for approval']])->get();
				foreach($totals as $total){
					$results['count']++;
				}
				return $this->send_response($results, 'success');
			}
		}
	}
}