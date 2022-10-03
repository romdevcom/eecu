<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Years;
use App\Models\Sources;
use App\Models\Numbers;
use App\Models\Measures;
use App\Models\Groups;
use App\Models\Communities;
use App\Models\Sectors;
use App\Models\Indicators;
use App\Models\Indicators_values;
use App\Models\Measures_values;
use App\Models\Total_values;
use App\Models\Page;
use App\Models\News;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class StaticController extends Controller
{
	public function index(Request $request){
		$user = Auth::user();
		$groups = Groups::where([['used_in_calculations', 1], ['show_on_home', 1]])->get();
		$news = News::where('status', 'active')->orderBy('created_at', 'desc')->limit(4)->get();
		$lang = app()->getLocale();

		$current_year_repo = Years::where('status', 'approved')->orderBy('name', 'desc')->limit(1)->get()->first();
		$current_year = !empty($current_year_repo) ? $current_year_repo->id : false;
		$totals = Total_values::where([['type', 'year'], ['year_id', $current_year], ['status', 'approved']])->orderBy('value', 'desc')->get();

		$communities = Communities::where('status', 'active')->get();
		$markers = [];
		foreach($communities as $community){
			$position = 0;
			foreach($totals as $total){
				$position++;
				if($total->community_id == $community->id){
					$markers[$community->id] = [
						'position' => $position,
						'value' => intval($total->value),
						'eecu' => $community->eea_member == '1' && $community->eea_status == '1' ? '<span class=\'marker-eecu-logo\'></span>' : ''
					];
				}
			}
		}
		return view('home', compact('groups', 'user', 'news', 'communities', 'markers', 'current_year_repo', 'lang'));
	}

	public function index_rating(Request $request){
		return redirect('/rating/communities');
	}

	public function index_methodology(Request $request){
		return redirect('/methodology/how-to-join');
	}

	public function index_eea(Request $request){
		return redirect('/eea/about');
	}

	public function page(Request $request){
		$lang = app()->getLocale();
		$user = Auth::user();
		$page = Page::where([['slug', $request->slug], ['status', 'active']])->get()->first();
		if(!empty($page)){
			return view('page', compact('page', 'user', 'lang'));
		}else{
			return abort(404);
		}
	}

	public function page2(Request $request){
		$lang = app()->getLocale();
		$user = Auth::user();
		$page = Page::where([['slug', $request->slug1 . '/' . $request->slug2], ['status', 'active']])->get()->first();
		if(!empty($page)){
			return view('page', compact('page', 'user', 'lang'));
		}else{
			return abort(404);
		}
	}

	public function contacts(Request $request){
		$lang = app()->getLocale();
		$user = Auth::user();
		$seo = [
			'title' => trans('translation.menu_contacts') . ' | EECU',
			'description' => trans('translation.menu_contacts') . '. ' . trans('translation.title'),
		];
		return view('contacts', compact('seo', 'user', 'lang'));
	}

	public function news(Request $request){
		$lang = app()->getLocale();
		$user = Auth::user();
		if(isset($request->slug) && !empty($request->slug)){
			$page = News::where([['slug', $request->slug], ['status', 'active']])->get()->first();
			if(!empty($page)){
				$news = News::where([['status', 'active'], ['id', '!=', $page->id]])->orderBy('created_at', 'desc')->limit(5)->get();
				return view('news-single', compact('page', 'news', 'user', 'lang'));
			}else{
				return abort(404);
			}
		}else{
			$news = News::where('status', 'active')->orderBy('created_at', 'desc')->get();
			return view('news', compact('news', 'user', 'lang'));
		}
	}

	public function communities(Request $request){
		$lang = app()->getLocale();
		$user = Auth::user();
		$totals = Total_values::where([['type', 'year'], ['status', 'approved']])->get();
		$search = $request->{'quick-search'};
		if(isset($search)){
			$communities = Communities::where([['status', 'active'], ['name', 'like', '%' . $request->{'quick-search'} . '%']])->get();
		}else{
			$communities = Communities::where('status', 'active')->get();
		}
		$values = [];
		foreach($totals as $total){
			$values[$total->community_id] = isset($values[$total->community_id]) ? $values[$total->community_id] : [];
			$values[$total->community_id][$total->year_id] = $total->value;
		}

		$current_year_repo = Years::where('status', 'approved')->orderBy('name', 'desc')->limit(1)->get()->first();
		$current_year = !empty($current_year_repo) ? $current_year_repo->id : false;
		$totals = Total_values::where([['type', 'year'], ['year_id', $current_year], ['status', 'approved']])->orderBy('value', 'desc')->get();

		$communities = Communities::where('status', 'active')->get();
		$markers = [];
		foreach($communities as $community){
			$position = 0;
			foreach($totals as $total){
				$position++;
				if($total->community_id == $community->id){
					$markers[$community->id] = [
						'position' => $position,
						'value' => intval($total->value),
					];
				}
			}
		}

		$years = Years::where('status', 'approved')->orderBy('name', 'desc')->limit(3)->get();
		return view('communities', compact('communities', 'years', 'values', 'user', 'search', 'markers', 'current_year_repo', 'lang'));
	}

	public function communities_rating(Request $request){
		$lang = app()->getLocale();
		$user = Auth::user();
		$totals = Total_values::where([['type', 'year'], ['status', 'approved']])->get();
		if(isset($request->{'quick-search'})){
			$communities = Communities::where([['status', 'active'], ['name', 'like', '%' . $request->{'quick-search'} . '%']])->get();
		}else{
			$communities = Communities::where('status', 'active')->get();
		}
		$values = [];
		foreach($totals as $total){
			$values[$total->community_id] = isset($values[$total->community_id]) ? $values[$total->community_id] : [];
			$values[$total->community_id][$total->year_id] = intval($total->value);
		}
		$years = Years::where('status', 'approved')->orderBy('name', 'desc')->limit(3)->get();

		$current_year_repo = Years::where('status', 'approved')->orderBy('name', 'desc')->limit(1)->get()->first();
		$current_year = !empty($current_year_repo) ? $current_year_repo->id : false;
		$totals = Total_values::where([['type', 'year'], ['year_id', $current_year], ['status', 'approved']])->orderBy('value', 'desc')->get();

		$communities = Communities::where('status', 'active')->get();
		$markers = [];
		foreach($communities as $community){
			$position = 0;
			foreach($totals as $total){
				$position++;
				if($total->community_id == $community->id){
					$markers[$community->id] = [
						'position' => $position,
						'value' => intval($total->value),
						'eecu' => $community->eea_member == '1' && $community->eea_status == '1' ? '<span class=\'marker-eecu-logo\'></span>' : ''
					];
				}
			}
		}
		return view('communities-rating', compact('communities', 'years', 'values', 'user', 'markers', 'current_year_repo', 'lang'));
	}

	public function communities_compare_admin(Request $request){
		$lang = app()->getLocale();
		$user = Auth::user();
		$user_allowed = false;
		if(!empty($user)){
			$allowed_roles = [1, 2];
			$user_allowed = $user->status == 'approved' && in_array($user->role_id, $allowed_roles);
		}
		if(!$user_allowed){
			return redirect('https://eea-benchmark.enefcities.org.ua/rating/compare/');
		}
		$is_admin_compare = true;

		$city1 = $request->city1;
		$city1_name = $lang == 'en' ? $this->get_community_by_id($city1, 'name_en') : $this->get_community_by_id($city1, 'name');
		$city2 = $request->city2;
		$city2_name = $lang == 'en' ? $this->get_community_by_id($city2, 'name_en') : $this->get_community_by_id($city2, 'name');
		$city3 = $request->city3;
		$city3_name = $lang == 'en' ? $this->get_community_by_id($city3, 'name_en') : $this->get_community_by_id($city3, 'name');

		$cities = [];
		$cities_get = '';
		if(!empty($city1)){
			$cities_get .= '?city1=' . $city1;
			$cities[] = $city1;
		}
		if(!empty($city2)){
			$cities_get .= empty($cities_get) ? '?city2=' . $city2 : '&city2=' . $city2;
			$cities[] = $city2;
		}
		if(!empty($city3)){
			$cities_get .= empty($cities_get) ? '?city3=' . $city3 : '&city3=' . $city3;
			$cities[] = $city3;
		}

		//дістати роки для побудови списку
		$years = Years::where('status', 'approved')->orderBy('name', 'desc')->limit(5)->get();

		//отримати дані про поточний рік
		if(isset($request->year)){
			$current_year_repo = Years::where('name', $request->year)->get()->first();
			$current_year = !empty($current_year_repo) ? $current_year_repo->id : false;
		}else{
			$current_year_repo = Years::where('status', 'approved')->orderBy('name', 'desc')->limit(1)->get()->first();
			$current_year = !empty($current_year_repo) ? $current_year_repo->id : false;
		}

		$totals_by_years = DB::table('total_values')
             ->where([['year_id', $current_year], ['type', 'year']])
             ->select('community_id', 'year_id', 'year_name', 'value', 'count_all', 'count_values', 'count_approved_values', 'percent_values', 'percent_approved_values')
             ->orderBy('value', 'desc')
             ->get();

		$totals_by_city = [];
		$totals_communities = [];
		$totals_labels = false;
		foreach($cities as $city){
			$position = 0;
			foreach($totals_by_years as $totals_by_year){
				$position++;
				if($totals_by_year->community_id == $city){
					$totals_communities[$city] = [];
					$totals_communities[$city]['value'] = intval($totals_by_year->value);
					$totals_communities[$city]['position'] = $position;
				}
			}
			$totals_by_city[$city] = $this->get_community_data_by_year($city, $current_year, true, $lang);
		}

		$communities = Communities::where('status', 'active')->get();
		return view('communities-compare', compact('communities',
			'current_year', 'years',
			'user', 'user_allowed', 'is_admin_compare', 'lang',
			'totals_labels', 'totals_by_city', 'totals_communities',
			'city1', 'city1_name', 'city2', 'city2_name', 'city3', 'city3_name', 'cities_get'
		));
	}

	public function communities_compare(Request $request){
		$lang = app()->getLocale();
		$user = Auth::user();
		$user_allowed = false;
		if(!empty($user)){
			$allowed_roles = [1, 2];
			$user_allowed = $user->status == 'approved' && in_array($user->role_id, $allowed_roles);
			if(!$user_allowed && !empty($user->community_id)){
				$user_community = Communities::where('id', $user->community_id)->first();
				if(!empty($user_community)){
					$user_allowed = $user_community->eea_member == 1 && $user_community->eea_status == 1;
				}
			}
		}

		$city1 = $request->city1;
		$city1_name = $lang == 'en' ? $this->get_community_by_id($city1, 'name_en') : $this->get_community_by_id($city1, 'name');
		$city2 = $request->city2;
		$city2_name = $lang == 'en' ? $this->get_community_by_id($city2, 'name_en') : $this->get_community_by_id($city2, 'name');
		$city3 = $request->city3;
		$city3_name = $lang == 'en' ? $this->get_community_by_id($city3, 'name_en') : $this->get_community_by_id($city3, 'name');

		$cities = [];
		$cities_get = '';
		if(!empty($city1)){
			$cities_get .= '?city1=' . $city1;
			$cities[] = $city1;
		}
		if(!empty($city2)){
			$cities_get .= empty($cities_get) ? '?city2=' . $city2 : '&city2=' . $city2;
			$cities[] = $city2;
		}
		if(!empty($city3)){
			$cities_get .= empty($cities_get) ? '?city3=' . $city3 : '&city3=' . $city3;
			$cities[] = $city3;
		}

		//дістати роки для побудови списку
		$years = Years::where('status', 'approved')->orderBy('name', 'desc')->limit(5)->get();

		//отримати дані про поточний рік
		if(isset($request->year)){
			$current_year_repo = Years::where('name', $request->year)->get()->first();
			$current_year = !empty($current_year_repo) ? $current_year_repo->id : false;
		}else{
			$current_year_repo = Years::where('status', 'approved')->orderBy('name', 'desc')->limit(1)->get()->first();
			$current_year = !empty($current_year_repo) ? $current_year_repo->id : false;
		}

		$totals_by_years = DB::table('total_values')
               ->where([['year_id', $current_year], ['type', 'year'], ['status', 'approved']])
               ->select('community_id', 'year_id', 'year_name', 'value', 'count_all', 'count_values', 'count_approved_values', 'percent_values', 'percent_approved_values')
			   ->orderBy('value', 'desc')
               ->get();

		$totals_communities = [];
		$totals_by_city = [];
		foreach($cities as $city){
			$position = 0;
			foreach($totals_by_years as $totals_by_year){
				$position++;
				if($totals_by_year->community_id == $city){
					$totals_communities[$city] = [];
					$totals_communities[$city]['value'] = intval($totals_by_year->value);
					$totals_communities[$city]['position'] = $position;
				}
			}
			$totals_by_city[$city] = $this->get_community_data_by_year($city, $current_year, false, $lang);
		}

		$communities = Communities::where('status', 'active')->get();
		return view('communities-compare', compact('communities',
			'current_year', 'years',
			'user', 'user_allowed', 'lang',
			'totals_by_city', 'totals_communities',
			'city1', 'city1_name', 'city2', 'city2_name', 'city3', 'city3_name', 'cities_get'
		));
	}

	public function community(Request $request){
		$lang = app()->getLocale();
		$user = Auth::user();
		$user_allowed = false;
		$is_admin = false;
		if(!empty($request->slug)){
			$community = Communities::where('slug', $request->slug)->get()->first();
			if($community){
				if(!empty($user)){
					$allowed_roles = [1, 2];
					$user_allowed = $user->status == 'approved' && (($community->id == $user->community_id) || in_array($user->role_id, $allowed_roles));
					$is_admin = true;
				}
				if(isset($request->year)){
					$current_year_repo = Years::where('name', $request->year)->get()->first();
					$current_year = !empty($current_year_repo) ? $current_year_repo->id : false;
				}else{
					$current_year_repo = Total_values::where([['community_id', $community->id], ['status', 'approved']])->orderBy('year_name', 'desc')->limit(1)->get()->first();
					$current_year = !empty($current_year_repo) ? $current_year_repo->year_id : false;
				}

				$total_position = 0;
				$totals_by_years = DB::table('total_values')
	                     ->where([['year_id', $current_year], ['type', 'year'], ['status', 'approved']])
	                     ->select('community_id', 'year_id', 'year_name', 'value', 'count_all', 'count_values', 'count_approved_values', 'percent_values', 'percent_approved_values')
	                     ->orderBy('value')
	                     ->get();
				$count = 0;
				foreach($totals_by_years as $totals_by_year){
					$count++;
					if($totals_by_year->community_id == $community->id){
						$total_position = $count;
					}
				}

				$years = Years::where('status', 'approved')->orderBy('name', 'desc')->limit(5)->get();
				$totals_year = Total_values::where([['community_id', $community->id], ['year_id', $current_year], ['type', 'year']])->get()->first();
				$totals_groups = false;
				//отримання значень по роках
				$totals_year = Total_values::where([['community_id', $community->id], ['type', 'year'], ['status', 'approved']])->orderBy('year_name', 'asc')->get();
				$totals_current_year = Total_values::where([['community_id', $community->id], ['year_id', $current_year], ['type', 'year'], ['status', 'approved']])->get();
				if(count($totals_current_year)){
					//отримання значень по групах
					$totals_groups = DB::table('total_values as t')
	                   ->join('groups as g', 't.group_id', '=', 'g.id')
	                   ->where([['t.community_id', $community->id], ['t.year_id', $current_year], ['t.type', 'group']])
	                   ->select('t.group_id', 't.year_id', 'g.name', 't.value', 't.count_all', 't.count_values', 't.count_approved_values', 't.percent_values', 't.percent_approved_values')
	                   ->get();

					//отримання значень індикаторів, з'єднаних із групами та секторами
					$indicators_values = DB::table('indicators_values as v')
                       ->join('indicators as i', 'v.indicator_id', '=', 'i.id')
                       ->join('groups as g', 'i.group_id', '=', 'g.id')
                       ->join('sectors as s', 'i.sector_id', '=', 's.id')
                       ->where([['v.community_id', $community->id], ['v.year_id', $current_year]])
                       ->select('i.id', 'i.name', 'v.score', 'g.id as g_id', 's.id as s_id')
                       ->get();

				}

				$totals = $this->get_community_data_by_year($community->id, $current_year, $is_admin, $lang);

				$groups = Groups::where('used_in_calculations', 1)->get();
				$groups_array = [];
				foreach($groups as $group){
					$group_total = Total_values::where([['community_id', $community->id], ['year_id', $current_year], ['type', 'group'], ['group_id', $group->id]])->get()->first();
					$group_max = Total_values::where([['year_id', $current_year], ['type', 'group'], ['group_id', $group->id]])->orderByRaw('CONVERT(value, SIGNED) desc')->get()->first();
					if(!empty($group_total) && !empty($group_max)) {
						$groups_array[$group->id] = [
							'value' => intval($group_total->value),
							'max' => intval($group_max->value),
							'name' => $lang == 'en' ? $group->name_en : $group->name,
						];
					}
				}

				return view('community', compact(
					'community',
					'totals_year', 'totals_groups', 'totals', 'total_position',
					'years', 'current_year', 'groups_array',
					'user', 'user_allowed', 'lang'));
			}
		}
		return abort(404);
	}

	public function form(Request $request){
		$user = Auth::user();
		$lang = app()->getLocale();
		return view('form', compact('user', 'lang'));
	}

	public function register_verify(Request $request){
		$lang = app()->getLocale();
		$user = Auth::user();
		$code = $request->code;
		$verify = false;
		if(isset($code)){
			$user = User::where('email_verified', $code)->get()->first();
			if(!empty($user)){
				DB::table('users')->where('id', $user->id)->update(['status' => 'verified', 'email_verified' => date('d.m.Y H:s:i')]);
				$verify = true;
			}
		}
		return view('register-verify', compact('verify', 'user', 'lang'));
	}

	//допоміжні функції
	public function get_community_by_id($id, $field = false){
		if(!empty($id)){
			$repo = Communities::where('id', $id)->get()->first();
			if(!empty($repo)){
				return $field ? $repo->{$field} : $repo;
			}
		}
		return false;
	}

	public function get_community_data_by_year($community_id, $year_id, $admin = false, $lang = ''){
		$totals = [];

		//якщо рік вже затвердили тоді показувати дані всім, не залежно яке значення $admin
		$total_value_year = Total_values::where([['community_id', $community_id], ['year_id', $year_id], ['type', 'year']])->get()->first();
		if(!empty($total_value_year) && !$admin){
			$admin = $total_value_year->status == 'approved';
		}

		$lang = $lang == 'en' ? '_en' : '';
		$indicators = DB::table('indicators as i')
			->join('groups as g', 'i.group_id', '=', 'g.id')
			->join('sectors as s', 'i.sector_id', '=', 's.id')
			->where('used_in_calculations', 1)
			->select('i.id as i_id', 'i.name' . $lang . ' as i_name', 'g.id as g_id', 'g.name as g_name', 'g.name' . $lang . ' as g_name', 's.id as s_id', 's.name' . $lang . ' as s_name', 'i.dimension' . $lang . ' as dimension')
			->get();

		//формування масиву із загальними обрахунками
		$total_values = Total_values::where([['community_id', $community_id], ['year_id', $year_id]])->get();
		$total_array = [
			'year' => [],
			'group' => [],
		];
		foreach($total_values as $total_value){
			switch($total_value->type){
				case 'year':
					$total_array[$total_value->type][$total_value->year_id] = $admin ? $total_value->value : '---';
					break;
				case 'group':
					$total_array[$total_value->type][$total_value->group_id] = $admin ? $total_value->value : '---';
					break;
			}
		}

		//формування масиву зі значеннями індикаторів
		$indicator_values = Indicators_values::where([['community_id', $community_id], ['year_id', $year_id]])->get();
		$indicator_array = [];
		foreach($indicator_values as $indicator_value){
			$indicator_array[$indicator_value->indicator_id] = $indicator_value->score;
		}

		//$totals['value'] = isset($total_array['year'][$year_id]) ? intval($total_array['year'][$year_id]) : '---';
		foreach($indicators as $indicator){
			//формування верхнього рівня (групи)
			$totals[$indicator->g_id] = isset($totals[$indicator->g_id]) ? $totals[$indicator->g_id] : [];
			$totals[$indicator->g_id]['name'] = $indicator->g_name;
			$totals[$indicator->g_id]['value'] = $admin && isset($total_array['group'][$indicator->g_id]) ? intval($total_array['group'][$indicator->g_id]) : '---';
			$totals[$indicator->g_id]['list'] = isset($totals[$indicator->g_id]['list']) ? $totals[$indicator->g_id]['list'] : [];

			//формування рівня сектори
			$indicator_value = isset($indicator_array[$indicator->i_id]) ? doubleval($indicator_array[$indicator->i_id]) : 0;
			$totals[$indicator->g_id]['list'][$indicator->s_id] = isset($totals[$indicator->g_id]['list'][$indicator->s_id]) ? $totals[$indicator->g_id]['list'][$indicator->s_id] : [];
			$totals[$indicator->g_id]['list'][$indicator->s_id]['name'] = $indicator->s_name;
			if(!$admin){
				$totals[$indicator->g_id]['list'][$indicator->s_id]['value'] = '---';
			}else{
				$totals[$indicator->g_id]['list'][$indicator->s_id]['value'] = !empty($totals[$indicator->g_id]['list'][$indicator->s_id]['value']) ? intval($totals[$indicator->g_id]['list'][$indicator->s_id]['value'] + $indicator_value) : $indicator_value;
			}
			$totals[$indicator->g_id]['list'][$indicator->s_id]['list'] = isset($totals[$indicator->g_id]['list'][$indicator->s_id]['list']) ? $totals[$indicator->g_id]['list'][$indicator->s_id]['list'] : [];
			$totals[$indicator->g_id]['list'][$indicator->s_id]['list'][$indicator->i_id] = [
				//'name' => $indicator->i_name . ' (' . $indicator->dimension . ')',
				'name' => $indicator->i_name,
				'value' => $admin && isset($indicator_array[$indicator->i_id]) ? doubleval($indicator_array[$indicator->i_id]) : '---'
			];
		}

		return $totals;
	}
}
