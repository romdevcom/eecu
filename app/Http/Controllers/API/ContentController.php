<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Years;
use App\Models\Communities;
use Illuminate\Support\Facades\Auth;

class ContentController extends BaseController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	/** @OA\Get(
	 * path="/api/content",
	 * summary="Content",
	 * description="Get information about user",
	 * operationId="content",
	 * tags={"Контент"},
	 * security={ {"bearer": {} }},
	 * @OA\Response(
	 *    response=200,
	 *    description="success",
	 *     @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="success"),
	 *       @OA\Property(property="name", type="string", example="Name"),
	 *       @OA\Property(property="role", type="integer", example="1"),
	 *       @OA\Property(
	 *          property="nav",
	 *          type="array",
	 *          @OA\Items(),
	 *      ),
	 *    )
	 *     ),
	 * @OA\Response(
	 *    response=401,
	 *    description="User should be authorized to get information<br><br> Headers have to contain:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Not authorized"),
	 *    )
	 * )
	 * )
	 */
	public function index(Request $request)
	{
		$results = array();
		$user = Auth::user();
		if($user){
			if($this->is_approved_user($user)){
				$results['role'] = $user->role_id;
				$results['name'] = $user->name;

				$years_repo = Years::orderBy('name', 'desc')->get()->toArray();
				$years = array();
				foreach ($years_repo as $value){
					array_push($years, $this->content_menu_item($value['name'], $value['name'], 'path'));
				}

				$results['nav'] = array();
				$results['nav']['top'] = array(
					//$this->content_menu_item('Рейтинг команд', '#'),
					[
						'name' => 'Рейтинг громад',
						'url' => '#',
						'subpages' => array(
							$this->content_menu_item('Рейтинг громад', $this->url . '/rating/communities'),
							$this->content_menu_item('Порівняти громади', $this->url . '/rating/compare'),
						)
					],
					[
						'name' => 'Методологія',
						'url' => '#',
						'subpages' => array(
							$this->content_menu_item('Як приєднатись', $this->url . '/methodology/how-to-join'),
							$this->content_menu_item('Збір даних', $this->url . '/methodology/data-collection'),
							$this->content_menu_item('Інструкція користувача', $this->url . '/methodology/user-manual'),
							$this->content_menu_item('Новини', $this->url . '/news'),
						)
					],
					[
						'name' => 'ЄЕВ',
						'url' => '#',
						'subpages' => array(
							$this->content_menu_item('Про ЄЕВ', $this->url . '/eea/about'),
							$this->content_menu_item('Переваги участі в ЄЕВ', $this->url . '/eea/advantages-of-participation'),
							$this->content_menu_item('Партнери ЄЕВ', $this->url . '/eea/partners'),
						)
					],
					$this->content_menu_item('Про проєкт', $this->url . '/about'),
					$this->content_menu_item('Контакти', $this->url . '/contacts')
				);

				$communities = Communities::where('status', 'active')->get()->toArray();
				$communities_manu = array();
				foreach($communities as $community){
					array_push(
						$communities_manu,
						$this->content_menu_item($community['name'], $community['slug'], 'path')
					);
				}

				switch($user->role_id){
					case 1:
					case 2:
						$results['nav']['menu'] = array(
							array(
								'name' => 'Громади',
								'path' => 'communities',
								'icon' => 'iconoir:city',
								'subpages' => $communities_manu
							),
							array(
								'name' => 'Користувачі',
								'path' => 'users',
								'icon' => 'carbon:user-multiple',
								'goToFirstSubpage' => true,
								'subpages' => array(
									$this->content_menu_item('Активні', 'active', 'path'),
									$this->content_menu_item('Запити', 'requests', 'path'),
									$this->content_menu_item('Заблоковані', 'blocked', 'path'),
									$this->content_menu_item('Не верифіковані', 'unverified', 'path')
								)
							),
							$this->content_menu_item('Дані рейтингу', 'rating', 'path', 'carbon:agriculture-analytics', $years),
							array(
								'name' => 'Структура даних',
								'path' => 'data',
								'icon' => 'uil:analytics',
								'goToFirstSubpage' => true,
								'subpages' => array(
									$this->content_menu_item('Індикатори', 'indicators', 'path'),
									$this->content_menu_item('Показники', 'measures', 'path'),
									$this->content_menu_item('Джерела', 'sources', 'path')
								)
							),
							array(
								'name' => 'Повідомлення',
								'path' => 'messages',
								'icon' => 'ant-design:file-search-outlined',
								'goToFirstSubpage' => true,
								'subpages' => array(
									$this->content_menu_item('Потребують дії', 'action', 'path'),
									$this->content_menu_item('Архів', 'archive', 'path')
								)
							),
							$this->content_menu_item('Мій профіль', 'profile', 'path', 'carbon:user-profile')
						);
						break;
					case 3:
						if(!empty($user->community_id)){
							$user_community = Communities::where('id', $user->community_id)->get()->first();
							if(!empty($user_community)){
								$results['communityName'] = $user_community->name;
								$results['communityPicUrl'] = !empty($user_community->picture) ? $this->url . Storage::url($user_community->picture) : $this->default_picture;
							}
						}
						$results['nav']['menu'] = array(
							$this->content_menu_item('Дані рейтингу', 'rating', 'path', 'carbon:agriculture-analytics', $years),
							$this->content_menu_item('Профіль громади', 'community', 'path', 'iconoir:city'),
							$this->content_menu_item('Мій профіль', 'profile', 'path', 'typcn:messages')
						);
						break;
				}
				return $this->send_response($results, 'success');
			}
			return $this->send_error('Вашого користувача ще не затвердив адміністратор', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function content_menu_item($title, $url, $url_type = 'url', $icon = false, $subpages = false){
		$menu_item = array(
			'name' => $title,
			$url_type => $url
		);
		if(!empty($subpages)){
			$menu_item['subpages'] = $subpages;
		}
		if(!empty($icon)){
			$menu_item['icon'] = $icon;
		}
		return $menu_item;
	}

	public function test(){
		$results = array(
			'test' => 'value'
		);
		return $this->send_response($results, 'success');
	}
}