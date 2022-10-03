<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\CalculationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Response,File;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use App\Models\Logs;
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

class CommunityController extends BaseController
{
	//отримати дані заповненості в громаді по року
	public function get_community_data_info_by_year($community_id, $year_id){
		$count = Measures::select('id')->count();
		$values_count = Measures_values::where([['community_id', $community_id], ['year_id', $year_id]])->select('id')->count();
		$values_approved_count = Measures_values::where([['community_id', $community_id], ['year_id', $year_id], ['status', 'approved']])->select('id')->count();
		return array(
			'count' => $count,
			'values_count' => $values_count,
			'values_percent' => !empty($values_count) ? round(($values_count * 100) / $count, 0) : 0,
			'values_approved_count' => $values_approved_count,
			'values_approved_percent' => !empty($values_approved_count) ? round(($values_approved_count * 100) / $count, 0) : 0,
		);
	}

	/** @OA\Get(
	 * path="/api/admin/communities",
	 * summary="Отримати список громад",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="CommunitiesGet",
	 * tags={"Громади"},
	 * security={ {"bearer": {} }},
	 *@OA\Response(
	 *    response=200,
	 *    description="Буде повернено масив з громадами та хедер таблиць",
	 *     @OA\JsonContent(
	 *       @OA\Property(property="name", type="string", example="Учасники проекту"),
	 *       @OA\Property(
	 *          property="headers",
	 *          type="array",
	 *          @OA\Items(
	 *              @OA\Property(property="name", type="string", example="Назва"),
	 *              @OA\Property(property="value", type="string", example="name"),
	 *              @OA\Property(property="sortable", type="boolean", example="true"),
	 *          ),
	 *       ),
	 *       @OA\Property(
	 *          property="row",
	 *          type="array",
	 *          @OA\Items(
	 *	            @OA\Property(property="id", type="integer", example="1"),
	 *              @OA\Property(property="slug", type="string", example="hromada"),
	 *              @OA\Property(property="name", type="string", example="Громада"),
	 *              @OA\Property(property="chief", type="string", example="Керівник громади"),
	 *              @OA\Property(property="contact_person", type="string", example="Контактна персона"),
	 *              @OA\Property(property="phone", type="string", example="+38 (096) 000 00 00"),
	 *              @OA\Property(property="email", type="string", example="email@gmail.com"),
	 *              @OA\Property(property="lat", type="string", example="42.2323"),
	 *              @OA\Property(property="lng", type="string", example="22.2313"),
	 *              @OA\Property(property="picture", type="string", example="22"),
	 *              @OA\Property(property="eea_member", type="integer", example="0"),
	 *          ),
	 *      ),
	 *     @OA\Property(property="message", type="string", example="success"),
	 *     @OA\Property(property="success", type="string", example="true"),
	 *    )
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
	/** @OA\Get(
	 * path="/api/admin/communities/{community_id}",
	 * summary="Отримати список громад",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="CommunitiesGetID",
	 * tags={"Громади"},
	 * security={ {"bearer": {} }},
	 * @OA\Response(
	 *    response=200,
	 *    description="Буде повернено масив з секторами",
	 *     @OA\JsonContent(
	 *       @OA\Property(
	 *          property="rows",
	 *          type="array",
	 *          @OA\Items(
	 *	            @OA\Property(property="id", type="integer", example="1"),
	 *              @OA\Property(property="slug", type="string", example="hromada"),
	 *              @OA\Property(property="name", type="string", example="Громада"),
	 *              @OA\Property(property="chief", type="string", example="Керівник громади"),
	 *              @OA\Property(property="contact_person", type="string", example="Контактна персона"),
	 *              @OA\Property(property="phone", type="string", example="+38 (096) 000 00 00"),
	 *              @OA\Property(property="email", type="string", example="email@gmail.com"),
	 *              @OA\Property(property="lat", type="string", example="42.2323"),
	 *              @OA\Property(property="lng", type="string", example="22.2313"),
	 *              @OA\Property(property="picture", type="string", example="22"),
	 *              @OA\Property(property="eea_member", type="integer", example="0"),
	 *          ),
	 *      ),
	 *     @OA\Property(property="message", type="string", example="success"),
	 *     @OA\Property(property="success", type="string", example="true"),
	 *    )
	 *     ),
	 * @OA\Response(
	 *    response=401,
	 *    description="Якщо користувач не увійшов на сайт",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="unauthorised"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=403,
	 *    description="Якщо користувач не є супер адміністратором і не приходить community_id",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="method is not allowed without community_id"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=404,
	 *    description="Якщо по community_id не знайдено громади",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="community not found"),
	 *    )
	 * )
	 * )
	 */
	public function get_communities(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			//якщо приходить ід громади тоді віддавати дані про неї
			if(isset($request->community_id)){
				if($this->is_admin_or_approved_manager($user, $request->community_id)){
					$model = new Communities;
					$select = 'id,name,slug,chief,contact_person,phone,email,lat,lng,picture,eea_member,eea_year,eea_value,eea_status';
					$list = $this->get($model, $select, [['status', 'active'],['slug', $request->community_id]]);
					//якщо знайдено громаду по ід
					if(isset($list[0]) && !empty($list[0])){
						//масив загальної інформації про громаду
						$results['pageTitle'] = $list[0]->name;
						$results['community_id'] = $list[0]->id;
						$results['community'] = array(
							'id' => $list[0]->id,
							'name' => 'Профіль громади',
							'objType' => 'communities',
							'form' => array(
								'name' => array(
									'field_type' => 'text',
									'label' => 'Назва',
									'value' => $list[0]->name
								),
								'geo' => array(
									'field_type' => 'text',
									'label' => 'Геодані',
									'value' => $list[0]->lat . ', ' . $list[0]->lng
								),
								'chief' => array(
									'field_type' => 'text',
									'label' => 'Керівник громади',
									'value' => $list[0]->chief
								),
								'contact_person' => array(
									'field_type' => 'text',
									'label' => 'Контактна персона',
									'value' => $list[0]->contact_person
								),
								'phone' => array(
									'field_type' => 'phone',
									'label' => 'Телефон',
									'value' => $list[0]->phone
								),
								'email' => array(
									'field_type' => 'phone',
									'label' => 'Емейл',
									'value' => $list[0]->email
								)
							),
							'picture' => array(
								'url' => !empty($list[0]['picture']) ? $this->url . Storage::url($list[0]->picture) : $this->default_picture,
							),
						);

						//таблиця з користувачами
						$managers = User::where('community_id', $list[0]->id)->get();
						$managers_tooltip = array(
							'name' => 'Стан користувача',
							'items' => array(
								array(
									'icon' => 'ant-design:info-circle-outlined',
									'name' => 'Користувач заблокований'
								),
								array(
									'icon' => 'codicon:check-all',
									'name' => 'Користувача верифіковано'
								),
							)
						);
						$managers_header = array(
							$this->table_header_col('', 'status', false, 'ico', $managers_tooltip),
							$this->table_header_col('Ім’я Прізвище', 'name', true, 'textBold', false, false, '', true),
							$this->table_header_col('Роль', 'role', true, 'textBold', false, false, '', true),
							$this->table_header_col('Телефон', 'phone', false, 'textBold', false, false, '', true),
							$this->table_header_col('Емейл', 'email', false, 'textBold', false, false, '', true),
							$this->table_header_col('', 'buttons', false, 'btn'),
							$this->table_header_col('', 'actions', false, 'action'),
						);
						$managers_rows = array();
						foreach($managers as $manager){
							array_push(
								$managers_rows,
								array(
									'status' => $manager->status,
									'id' => $manager->id,
									'name' => $manager->name,
									'role_id' =>$manager->role_id,
									'role' => DB::table('roles')->where('id', $manager->role_id)->select('display_name')->first()->display_name,
									'phone' => $manager->phone,
									'email' => $manager->email,
									'buttons' => $manager->status == 'approved' ? array('block') : array('authorize'),
									'actions' => array('edit')
								)
							);
						}
						$results['tableUsers'] = $this->table('Користувачі', $managers_header, $managers_rows, 'communities/users', false);

						//таблиця eea
						$eea_tooltip = array(
							'name' => 'Участь у ЄЕВ',
							'items' => array(
								array(
									'icon' => 'ant-design:info-circle-outlined',
									'name' => 'Не бере участь'
								),
								array(
									'icon' => 'codicon:check-all',
									'name' => 'Бере участь'
								),
							),
							'sortable' => false
						);
						$eea_headers = array(
							$this->table_header_col('Статус', 'status', false, 'ico'),
							$this->table_header_col('', 'name', false),
							$this->table_header_col('Бере участь', 'participant', false, 'radio', $eea_tooltip),
							$this->table_header_col('Рік', 'year', true, 'input'),
							$this->table_header_col('Бал', 'input', false, 'input'),
							$this->table_header_col('', 'buttons', false, 'btn'),
						);

						$eea_status = $list[0]->eea_member == 1 ? 'verification' : 'dataentry';
						$eea_buttons = ['verify'];
						if($list[0]->eea_status == 1 && $list[0]->eea_member == 1){
							$eea_status = 'approved';
							$eea_buttons = ['off'];
						}
						$eea_rows = array(
							array(
								'status' => $eea_status,
								'name' => 'ЄЕВ',
								'id' => $list[0]->id,
								'participant' => !empty($list[0]->eea_member),
								'year' => $list[0]->eea_year,
								'input' => $list[0]->eea_value,
								'buttons' => $eea_buttons
							)
						);
						$results['tableEea'] = $this->table('Участь в ЄЕВ', $eea_headers, $eea_rows, 'communities/eea', false);

						//таблиця даних по роках
						$year_status_tooltip = array(
							'name' => 'Статус року',
							'items' => array(
								array(
									'icon' => 'ant-design:info-circle-outlined',
									'name' => 'Дані вносяться'
								),
								array(
									'icon' => 'ant-design:file-search-outlined',
									'name' => 'Рік подано на верифікацію'
								),
								array(
									'icon' => 'codicon:check-all',
									'name' => 'Рік верифіковано'
								),
							)
						);
						$year_headers = array(
							$this->table_header_col('Статус', 'status', true, 'ico', $year_status_tooltip),
							$this->table_header_col('Рік', 'year', true),
							$this->table_header('Загальний бал', 'totalPoints', true),
							$this->table_header_col('% заповненості', 'approvalPercent', false, 'progress', false, false, 'yellow'),
							$this->table_header_col('% верифікації', 'verificationPercent', false, 'progress', false, false, 'green'),
							$this->table_header_col('', 'buttons', false, 'btn'),
						);

						//дістати роки, які є заповнені в цій громаді
						$years = Years::orderBy('name', 'desc')->get();
						$total_values = Total_values::where([['community_id', $list[0]->id], ['type', 'year']])->orderBy('year_name', 'asc')->get();
						$year_rows = [];
						foreach($years as $year){
							$year_total_exist = false;
							if(!empty($total_values)){
								foreach($total_values as $total_value){
									if($total_value->year_id == $year->id){
										$year_total_exist = true;
										switch($total_value->status){
											case 'for approval':
												$total_value_status = 'verification';
												$total_value_buttons = ['viewVerify'];
												if($total_value->percent_approved_values == 100){
													$total_value_buttons[] = 'approve';
												}
												break;
											case 'approved':
												$total_value_status = 'approved';
												$total_value_buttons = ['view', 'block'];
												break;
											default:
												$total_value_status = 'dataentry';
												$total_value_buttons = ['view'];
										}
										$year_rows[] = [
											'status' => $total_value_status,
											'year' => $total_value->year_name,
											'year_id' => $total_value->year_id,
											'community_id' => $list[0]->id,
											'totalPoints' => intval($total_value->value),
											//'id' => $total_value->id,
											'id' => $list[0]->id . '_' . $total_value->year_id,
											'approvalPercent' => $total_value->percent_values,
											'verificationPercent' => $total_value->percent_approved_values,
											'path' => '/admin/communities/' . $list[0]->slug . '/' . $total_value->year_name,
											'buttons' => $total_value_buttons
										];
									}
								}
							}
							if(!$year_total_exist){
								$year_rows[] = [
									'status' => 'dataentry',
									'year' => $year->name,
									'year_id' => $year->id,
									'community_id' => $list[0]->id,
									'totalPoints' => '---',
									'id' => $list[0]->id. '_' . $year->id,
									'approvalPercent' => 0,
									'verificationPercent' => 0,
									'path' => '/admin/communities/' . $list[0]->slug . '/' . $year->name,
									'buttons' => ['view']
								];
							}
						}
						$results['tableYears'] = $this->table('Дані по роках', $year_headers, $year_rows, 'communities/year', false);
					}else{
						return $this->send_error('community not found', 404);
					}
				}else{
					return $this->send_error('community not found', 404);
				}
			}else{
				//якщо не приходить значення community_id, тоді, якщо це супер адмін - тоді повернути громади,
				//якщо ні - видати помилку 403
				if(in_array($user->role_id, $this->allowed_roles)){
					$toolbar = array(
						'name' => 'Участь у ЄЕВ',
						'items' => array(
							array(
								'icon' => 'codicon:check-all',
								'name' => 'Підтверджена'
							),
							array(
								'icon' => 'octicon:blocked-16',
								'name' => 'На затвердженні'
							),
						),
						'sortable' => false
					);
					$results = array(
						'pageTitle' => 'Громади',
						'table' => array(
							'name' => 'Учасники проекту',
							'headers' => array(
								$this->table_header_col('Статус', 'status', false, 'ico', $toolbar),
								$this->table_header_col('Назва', 'name', true),
								$this->table_header('ЄЕВ', 'eecu', true),
								$this->table_header('Рік', 'year', true),
								$this->table_header('%', 'percent', false),
								$this->table_header('Профіль', 'profile', false),
								$this->table_header('Менеджер', 'manager', false),
								$this->table_header_col('', 'buttons', false, 'btn'),
							)
						)
					);
					$model = new Communities;
					$select = 'id,name,status,slug,chief,contact_person,phone,email,lat,lng,picture,eea_member,eea_status,eea_value,eea_year';
					$list = $this->get($model, $select);
					$results['table']['rows'] = [];
					$results['table']['objType'] = 'communities/active';
					foreach($list as $item){
						$manager = User::where([['community_id', $item->id]])->first();
						$community_status = $item->status != 'active' ? 'blocked' : 'approved';
						array_push(
							$results['table']['rows'],
							array(
								'id' => $item->id,
								'community_id' => $item->id,
								'year_id' => 0,
								'slug' => $item->slug,
								'name' => $item->name,
								'status' => $community_status,
								'eecu' => $item->eea_member == '1' && $item->eea_status == '1' ? 'так' : 'ні',
								'year' => $item->eea_year,
								'percent' => $item->eea_value,
								'profile' => $this->community_data_filling($item->id),
								'manager' => !empty($manager) ? $manager->name : '',
								'buttons' => $item->status == 'active' ? ['view'] : ['off', 'approve']
							)
						);
					}

				}else{
					return $this->send_error('method is not allowed without community_id', 403);
				}
			}
			return $this->send_response($results, 'Зміни збережено');
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Get(
	 * path="/api/communities/{community_id}",
	 * summary="Отримати дані громади у вигляді form",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="CommunityGetID",
	 * tags={"Громади"},
	 * security={ {"bearer": {} }},
	 * @OA\Response(
	 *    response=200,
	 *    description="Буде повернено id та масив form",
	 *     @OA\JsonContent(
	 *       @OA\Property(
	 *          property="form",
	 *          type="array",
	 *          @OA\Items(
	 *              @OA\Property(
	 *                  property="chief",
	 *                  type="array",
	 *                  @OA\Items(
	 *	                    @OA\Property(property="label", type="string", example="1"),
	 *                      @OA\Property(property="value", type="string", example="hromada"),
	 *                  ),
	 *              ),
	 *          ),
	 *      ),
	 *     @OA\Property(property="id", type="string", example="1"),
	 *     @OA\Property(property="message", type="string", example="success"),
	 *     @OA\Property(property="success", type="string", example="true"),
	 *    )
	 *     ),
	 * @OA\Response(
	 *    response=401,
	 *    description="Якщо користувач не увійшов на сайт",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="unauthorised"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=403,
	 *    description="Якщо користувач не є супер адміністратором і не підходить community_id",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="method is not allowed without community_id"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=404,
	 *    description="Якщо по community_id не знайдено громади",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="community not found"),
	 *    )
	 * )
	 * )
	 */
	public function get_community(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			//якщо приходить ід громади тоді віддавати дані про неї
			if(isset($request->community_id)){
				if($this->is_admin_or_approved_manager($user, $request->community_id)){
					$model = new Communities;
					$select = 'id,name,slug,chief,contact_person,phone,email,lat,lng,picture,eea_member,eea_year,eea_value';
					$list = $this->get($model, $select, [['status', 'active'],['id', $request->community_id]]);
					//якщо знайдено громаду по ід
					if(isset($list[0]) && !empty($list[0])){
						$results = array(
							'id' => $list[0]->id,
							'form' => array(
								'chief' => array(
									'field_type' => 'text',
									'label' => 'Керівник громади',
									'value' => $list[0]->chief
								),
								'contact_person' => array(
									'field_type' => 'text',
									'label' => 'Контактна персона',
									'value' => $list[0]->contact_person
								),
								'phone' => array(
									'field_type' => 'phone',
									'label' => 'Телефон',
									'value' => $list[0]->phone
								),
								'email' => array(
									'field_type' => 'email',
									'label' => 'Емейл',
									'value' => $list[0]->email
								),
								'geo' => array(
									'field_type' => 'text',
									'label' => 'Геодані',
									'value' => $list[0]->lat . ', ' . $list[0]->lng
								)
							),
							'picture' => array(
								'url' => !empty($list[0]['picture']) ? $this->url . Storage::url($list[0]['picture']) : $this->default_picture,
							),
						);
					}else{
						return $this->send_error('not found', 404);
					}
				}else{
					return $this->send_error('forbidden', 403);
				}
			}
			return $this->send_response($results, 'Зміни збережено');
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Post(
	 * path="/api/communities/{community_id}",
	 * summary="Оновити дані громади",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="CommunityUpdateByID",
	 * tags={"Громади"},
	 * security={ {"bearer": {} }},
	 * @OA\SecurityScheme(
	 *      securityScheme="bearerAuth",
	 *      in="header",
	 *      name="bearerAuth",
	 *      type="http",
	 *      scheme="bearer",
	 *      bearerFormat="JWT",
	 * ),
	 * @OA\Parameter(
	 *    parameter="community_id",
	 *    name="community_id",
	 *    in="query",
	 *    required=true,
	 *    description="id громади",
	 *     @OA\JsonContent(
	 *     @OA\Property(property="community_id", type="string", example="1"),
	 *    )
	 * ),
	 * @OA\Parameter(
	 *    parameter="form",
	 *    name="form",
	 *    in="query",
	 *    required=true,
	 *    description="form",
	 *     @OA\JsonContent(
	 *          @OA\Property(
	 *              property="chief",
	 *              type="array",
	 *              @OA\Items(
	 *	                @OA\Property(property="label", type="string", example="1"),
	 *                  @OA\Property(property="value", type="string", example="hromada"),
	 *              ),
	 *         )
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=200,
	 *    description="Буде повернено id та масив form",
	 *     @OA\JsonContent(
	 *       @OA\Property(
	 *          property="form",
	 *          type="array",
	 *          @OA\Items(
	 *              @OA\Property(
	 *                  property="chief",
	 *                  type="array",
	 *                  @OA\Items(
	 *	                    @OA\Property(property="label", type="string", example="1"),
	 *                      @OA\Property(property="value", type="string", example="hromada"),
	 *                  ),
	 *              ),
	 *          ),
	 *      ),
	 *     @OA\Property(property="id", type="string", example="1"),
	 *     @OA\Property(property="message", type="string", example="success"),
	 *     @OA\Property(property="success", type="string", example="true"),
	 *    )
	 *     ),
	 * @OA\Response(
	 *    response=401,
	 *    description="Якщо користувач не увійшов на сайт",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="unauthorised"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=403,
	 *    description="Якщо користувач не є супер адміністратором і не підходить community_id",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="method is not allowed without community_id"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=404,
	 *    description="Якщо по community_id не знайдено громади",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="community not found"),
	 *    )
	 * )
	 * )
	 */
	public function edit_communities(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			//якщо приходить ід громади тоді віддавати дані про неї
			$data = json_decode($request->getContent());
			if(isset($data->id)){
				$community_id = $data->id;
				if($this->is_admin_or_approved_manager($user, $community_id)){
					$model = new Communities;
					$select = 'id,name,slug';
					$list = $this->get($model, $select, [['status', 'active'], ['id', $community_id]]);
					//якщо знайдено громаду по ід
					if(isset($list[0]) && !empty($list[0])){
						$updates = array();
						$data = isset($data->payload) ? $data->payload : $data->data;
						foreach($data as $key => $item){
							if($key == 'geo'){
								$geo = explode(', ', $item);
								$updates['lat'] = $geo[0];
								$updates['lng'] = $geo[1];
							}else{
								$updates[$key] = $item;
							}
						}
						DB::table('communities')->where('id', $community_id)->update($updates);
						$results['data'] = $data;
						return $this->send_response($results, 'Зміни збережено');
					}else{
						return $this->send_error('community not found', 404);
					}
				}else{
					return $this->send_error('community not found', 404);
				}
			}else{
				return $this->send_error('community not found', 404);
			}
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Get(
	 * path="/api/admin/communities/year/{year}",
	 * summary="Отримати список громад по року",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="CommunitiesGetAllByYear",
	 * tags={"Громади"},
	 * security={ {"bearer": {} }},
	 * @OA\Response(
	 *    response=200,
	 *    description="Буде повернено масив з громадами та хедер таблиць",
	 *     @OA\JsonContent(
	 *       @OA\Property(property="name", type="string", example="Учасники проекту"),
	 *       @OA\Property(
	 *          property="headers",
	 *          type="array",
	 *          @OA\Items(
	 *              @OA\Property(property="name", type="string", example="Назва"),
	 *              @OA\Property(property="value", type="string", example="name"),
	 *              @OA\Property(property="sortable", type="boolean", example="true"),
	 *          ),
	 *       ),
	 *       @OA\Property(
	 *          property="row",
	 *          type="array",
	 *          @OA\Items(
	 *	            @OA\Property(property="id", type="integer", example="1"),
	 *              @OA\Property(property="slug", type="string", example="hromada"),
	 *              @OA\Property(property="name", type="string", example="Громада"),
	 *              @OA\Property(property="status", type="string", example="approved"),
	 *              @OA\Property(property="year", type="string", example="2021"),
	 *              @OA\Property(property="percent", type="string", example="99"),
	 *              @OA\Property(property="manager", type="string", example="Support")
	 *          ),
	 *      ),
	 *     @OA\Property(property="message", type="string", example="success"),
	 *     @OA\Property(property="success", type="string", example="true"),
	 *    )
	 *),
	 * @OA\Response(
	 *    response=401,
	 *    description="Якщо користувач не увійшов на сайт",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="unauthorised"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=403,
	 *    description="Якщо користувач не є супер адміністратором і не приходить community_id",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="method is not allowed without community_id"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=404,
	 *    description="Якщо по community_id не знайдено громади",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="community not found"),
	 *    )
	 * )
	 * )
	 */
	public function get_year_communities(Request $request){
		$user = Auth::user();
		$toolbar = array(
			'name' => 'Tos3ol',
			'items' => array(
				array(
					'icon' => "ant-design:info-circle-outlined",
					'name' => "podrem lorem 1",
				),
				array(
					'icon' => "mdi-light:home",
					'name' => "losti lrem2",
				)
			)
		);
		$headers = array(
			$this->table_header('Назва', 'name', true),
			$this->table_header('ЄЕВ', 'status', false, $toolbar),
			$this->table_header('Рік', 'year', true),
			$this->table_header('%', 'percent', false),
			$this->table_header('Профіль', 'profile', false),
			$this->table_header('Менеджер', 'manager', false),
			$this->table_header('', 'actions', false)
		);
		$rows = array();
		if($user){
			if(isset($request->year)){
				if(in_array($user->role_id, $this->allowed_roles)){
					$model = new Communities;
					$select = 'id,name,slug,chief,contact_person,phone,email,lat,lng,picture,eea_member';
					$list = $this->get($model, $select, [['status', 'active']]);
					if(empty($list)){
						//якщо даних не знайдено тоді видати помилку 404
						return $this->send_error('data not found', 404);
					}else{
						foreach($list as $item){
							$manager = User::where([['community_id', $item->id]])->first();
							array_push(
								$rows,
								array(
									'id' => $item->id,
									'slug' => $item->slug,
									'name' => $item->name,
									'status' => 'approved',
									'year' => $request->year,
									'percent' => '99',
									'profile' => $this->community_data_filling($item->id),
									'manager' => !empty($manager) ? $manager->name : ''
								)
							);
						}
					}
				}
			}
			$results = $this->table('Учасники проекту', $headers, $rows, 'communities');
			return $this->send_response($results, 'Зміни збережено');
		}
		return $this->send_error('unauthorised');
	}

	public function get_community_year(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			if(isset($request->community_id) && isset($request->year)){
				$model = new Communities;
				$select = 'id,name,slug,chief,contact_person,phone,email,lat,lng,picture,eea_member';
				$list = $this->get($model, $select, [['status', 'active'],['slug', $request->community_id]]);
				if(!empty($list) && $this->is_admin_or_approved_manager($user, $list[0]->id)){
					$year_id = Years::where('name', $request->year)->get()->pluck('id')->first();

					$year_status_repo = Total_values::where([['community_id', $list[0]->id], ['year_id', $year_id], ['type', 'year']])->get()->first();
					//якщо рік не прийшов, або статус не for approval чи approved, тоді дані вносяться
					$event_name = '';
					$year_icon = 'ant-design:info-circle-outlined';
					$year_type = 'text';
					$year_action = false;
					$year_status = 'dataentry';
					$year_status_name = 'Внесення даних';
					$group_button_text = 'Переглянути';
					//за інших умов потрібно додати кнопку для верифікації чи текст
					if(!empty($year_status_repo)){
						switch($year_status_repo->status){
							case 'for approval':
								$year_icon = '';
								$year_type = 'button';
								$year_action = true;
								$year_status = 'verification';
								$year_status_name = 'Затвердити';
								$event_name = 'approve';
								$group_button_text = 'Перевірити';
								break;
							case 'approved':
								$year_icon = 'codicon:check-all';
								$year_type = 'text';
								$year_action = false;
								$year_status = 'approved';
								$year_status_name = 'Верифіковано';
								break;
						}
					}

					$model_groups = new Groups;
					$select_groups = 'name as name,id as value';
					$groups = $this->get($model_groups, $select_groups, [['status', 'active']]);

					$model_sources = new Sources;
					$select_sources = 'name as name,id as value';
					$sources = $this->get($model_sources, $select_sources, [['status', 'active']]);

					//отримати файли для цього року
					$files = DB::table('files as f')
					           ->join('confidences as c', 'c.id', '=', 'f.confidence_id')
					           ->where([['f.community_id', $list[0]->id], ['f.year_id', $year_id]])
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
					$results['files'] = [
						'quantity' => 0,
						'tooltip' => array(
							'name' => 'Типи джерел',
							'items' => $confidences
						),
						'list' => []
					];
					if(!empty($files)){
						$results['files'] = array(
							'quantity' => count($files),
							'list' => $files
						);
					}

					$results['pageTitle'] = $list[0]->name;
					$results['status'] = [
						'community_id' => $list[0]->id,
						'year_id' => $year_id,
						'key' => $year_status,
						'name' => $year_status_name,
						'icon' => $year_icon,
						'type' => $year_type,
						'isAction' => $year_action,
						'evtName' => $event_name,
						'objType' => 'communities/year'
					];
					$results['community_id'] = $list[0]->id;
					$results['year_id'] = $year_id;
					$results['search'] = array(
						'name' => 'Пошук за назвою ...',
						'value' => '',
					);
					$results['filter'] = array();
					$results['filter']['measure'] = array(
						'name' => 'Група показників',
						'list' => $groups
					);
					$results['filter']['source'] = array(
						'name' => 'Джерело',
						'list' => $sources
					);

					$model_groups = new Groups;
					$select_groups = 'id,name,icon,formula';
					$groups_list = $this->get($model_groups, $select_groups, [['status', 'active']]);
					$groups = array();
					foreach($groups_list as $item){
						$item_total = Total_values::where([['community_id', $list[0]->id], ['year_id', $year_id], ['group_id', $item->id]])->get()->first();
						$item_progress = 0;
						if(!empty($item_total)){
							$item_progress = $year_status == 'dataentry' ? $item_total->percent_values : $item_total->percent_approved_values;
						}
						$groups[] = [
							'btnText' => $group_button_text,
							'name' => $item->name,
							'pic' => $item['icon'],
							'progress' => $item_progress,
							'filterValue' => $item->id
						];
					}
					$results['group'] = $groups;
					return $this->send_response($results, 'Зміни збережено');
				}
			}
			return $this->send_error('data not found', 404);
		}
		return $this->send_error('unauthorised');
	}

	public function get_community_metric(Request $request){
		try{
			$user = Auth::user();
			$results = array();
			if($user){
				//якщо приходить ід громади тоді віддавати дані про неї
				if(isset($request->community_id)){
					$model = new Communities;
					$select = 'id,name,slug';
					$list = $this->get($model, $select, [['status', 'active'],['slug', $request->community_id]]);

					//якщо користувачу можна отримати ці дані та громада існує
					if(!empty($list) && $this->is_admin_or_approved_manager($user, $list[0]->id)){
						//отримати список груп для фільтра
						$model_groups = new Groups;
						$select_groups = 'name as name,id as value';
						$groups = $this->get($model_groups, $select_groups, [['status', 'active']]);

						//отримати список джерел для фільтра
						$model_sources = new Sources;
						$select_sources = 'name as name,id as value';
						$sources = $this->get($model_sources, $select_sources, [['status', 'active']]);
						$sources_array = array();
						foreach($sources as $source){
							$sources_array[$source['value']] = $source->name;
						}

						//отримати поточний рік
						$model_year = new Years;
						$select_year = 'id,name';
						$year = $this->get($model_year, $select_year, [['name', $request->year]]);

						if(isset($year[0]) && !empty($year[0])){
							//отримати внесені значення
							$query = DB::table('measures_values as v')->where([['community_id', $list[0]->id], ['year_id', $year[0]->id]]);
							$query->join('measures as m', 'm.id', '=', 'v.measure_id');
							if(isset($request->measure)){
								$query->join('measures_x_groups as xg', 'm.id', '=', 'xg.measures_id');
								$query->where('xg.groups_id', $request->measure);
							}
							if(isset($request->source)){
								$query->join('measures_x_sources as xs', 'm.id', '=', 'xs.measures_id');
								$query->where('xs.sources_id', $request->source);
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
								$query->where('groups_id', $request->measure);
							}
							if(isset($request->source)){
								$query->join('measures_x_sources as xs', 'm.id', '=', 'xs.measures_id');
								$query->where('xs.sources_id', $request->source);
							}
							if(isset($request->search)){
								$query->where('m.name', 'LIKE', '%' . $request->search . '%');
							}
							$measures = $query->select('m.id', 'm.name', 'm.dimension')->distinct('m.id')->get();

							//отримати статус цього року
							$year_status_repo = Total_values::where([['community_id', $list[0]->id], ['year_id', $year[0]->id], ['type', 'year']])->get()->first();
							$year_status = !empty($year_status_repo) ? $year_status_repo->status : 'waiting';

							//масив загальної інформації про громаду
							$results['pageTitle'] = $list[0]->name;
							$results['yearStatus'] = $year_status;
							$results['community_id'] = $list[0]->id;
							$results['year_id'] = $year[0]->id;
							$results['quantity'] = count($measures_values);
							$results['search'] = array(
								'name' => 'Пошук за назвою ...',
								'value' => ''
							);
							$results['filter'] = array(
								'measure' => array(
									'name' => 'Група показників',
									'list' => $groups
								),
								'source' => array(
									'name' => 'Джерело',
									'list' => $sources
								)
							);

							//якщо дані року ще не подані на верифікацію, тоді їх не можна редагувати
							if($year_status == 'waiting'){
								$headers = array(
									$this->table_header_col('', 'status', false, 'ico'),
									$this->table_header_col('Назва показника', 'name', false),
									$this->table_header_col('Значення', 'value', false),
									$this->table_header('Од. виміру', 'measure', false),
									$this->table_header_col('Файл', 'file', false, 'file'),
								);
							}else{
								$headers = array(
									$this->table_header_col('', 'checked', false, 'chkb'),
									$this->table_header_col('', 'status', false, 'ico'),
									$this->table_header_col('Назва показника', 'name', false),
									$this->table_header_col('Значення', 'value', false, 'input'),
									$this->table_header('Од. виміру', 'measure', false),
									$this->table_header_col('Файл', 'file', false, 'file'),
									$this->table_header_col('', 'actions', false, 'action'),
								);
							}

							$rows = [];
							foreach($measures as $measure){
								//початково статус буде як внесення даних
								$measures_status = 'dataentry';
								if(isset($measures_values_array[$measure->id]) && isset($measures_values_array[$measure->id]['status']) && $measures_values_array[$measure->id]['status'] == 'approved'){
									$measures_status = 'approved';
								}
								$actions = $measures_status == 'approved' ? ['cancel'] : ['approve'];
								$measures_file = array('icon' => '', 'name' => 'Немає файлів', 'url' => '');
								$measures_value = '';
								if(isset($measures_values_array[$measure->id]) && !empty($measures_values_array[$measure->id])){
									$measures_value = $measures_values_array[$measure->id]['value'];
									$measures_file_query = DB::table('files as f');
									$measures_file_query->join('confidences as c', 'f.confidence_id', '=', 'c.id');
									$measures_file_query->where('f.id', $measures_values_array[$measure->id]['file_id']);
									$measures_file = $measures_file_query->select('f.id', 'f.name', 'f.file as url', 'c.icon')->get()->toArray();
									if(!empty($measures_file) && isset($measures_file[0])){
										$measures_file = $measures_file[0];
										$measures_file->url = URL::to('/') . Storage::url($measures_file->url);
										if($measures_status != 'approved'){
											$actions[] = 'unlink';
										}
									}else{
										$measures_file = array('icon' => '', 'name' => 'Немає файлів', 'url' => '');
										if($measures_status != 'approved'){
											$actions[] = 'link';
										}
									}
								}else{
									$actions[] = 'link';
								}

								$row_id = $measure->id . '_' . $list[0]->id . '_' . $year[0]->id;
								$rows[] = [
									'id' => $row_id,
									'measure_id' => $measure->id,
									'community_id' => $list[0]->id,
									'year_id' => $year[0]->id,
									'checked' => false,
									'status' => $year_status == 'waiting' ? 'dataentry' : $measures_status,
									'name' => $measure->name,
									'value' => $measures_value,
									'measure' => $measure->dimension,
									'file' => $measures_file,
									'actions' => array_reverse($actions)
								];
							}

							$results['table'] = $this->table('Показники', $headers, $rows, 'measure-values', false, $year_status == 'waiting' ? [] : ['approve', 'cancel', 'link']);
							return $this->send_response($results, 'Зміни збережено');
						}else{
							return $this->send_error('year not found', 404);
						}
					}else{
						return $this->send_error('forbidden', 403);
					}
				}
			}
			return $this->send_error('unauthorised');
		}catch(Throwable $e){
			return $this->send_error('error');
		}
	}

	public function get_community_files(Request $request){
		$user = Auth::user();
		$year = Years::where('name', $request->year)->get()->first();
		$community = Communities::where([['slug', $request->community_id], ['status', 'active']])->get()->first();
		if($user && !empty($year) && !empty($community)){
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
					->where([['f.community_id', $community->id], ['year_id', $year->id]])
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
							'source_id' => $file->source_id,
							'file' => array(
								'icon' => $file->confidence_icon,
								'name' => $file->name,
								'url' => $this->url . Storage::url($file->file)
							),
							'actions' => array('edit', 'delete'),
						)
					);
				}

				$results = array(
					'pageTitle' => $community->name . ' ' . $year->name,
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
		return $this->send_error('unauthorised');
	}

	public function edit_community_metric(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			//якщо приходить ід громади тоді віддавати дані про неї
			if(isset($request->community_id)){
				$model = new Communities;
				$select = 'id,name,slug';
				$list = $this->get($model, $select, [['status', 'active'],['slug', $request->community_id]]);
				//якщо громаду знайдено і користувачу можна робити цю дію
				if(!empty($list) && $this->is_admin_or_approved_manager($user, $list[0]->id)){
					$data = json_decode($request->getContent());
					DB::table('measures_values')->where('id', $data->payload->id)->update(
						array(
							'value' => $data->payload->value,
							'status' => 'approved',
						)
					);
					$results['data'] = $data->payload;
					return $this->send_response($results, 'Зміни збережено');
				}else{
					return $this->send_error('ви не маєте прав робити цю дію', 403);
				}
			}
		}
		return $this->send_error('unauthorised');
	}

	public function edit_communities_id(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			//якщо приходить ід громади тоді віддавати дані про неї
			if(isset($request->community_id)){
				if(in_array($user->role_id, $this->allowed_roles) || $user->community_id == $request->community_id){
					$model = new Communities;
					$select = 'id,name,slug';
					$list = $this->get($model, $select, [['status', 'active'],['slug', $request->community_id]]);
					//якщо знайдено громаду по ід
					if(isset($list[0]) && !empty($list[0])){
						$data = json_decode($request->getContent());
						if($data->payload->name == 'ЄЕВ'){
							if($data->evtName == 'approve'){
								DB::table('communities')->where('id', $list[0]->id)->update(
									array(
										'eea_member' => 1,
										'eea_year' => $data->payload->year,
										'eea_value' => $data->payload->input
									)
								);
							}
						}
					}else{
						return $this->send_error('community not found', 404);
					}
				}else{
					return $this->send_error('community not found', 404);
				}
			}
			return $this->send_response($results, 'Зміни збережено');
		}
		return $this->send_error('unauthorised');
	}

	public function edit_eea_communities(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			$data = json_decode($request->getContent());
			if(isset($request->community_id)){
				if($this->is_admin_or_approved_manager($user, $request->community_id)){
					if(isset($data->data) && !empty($data->data)){
						$results['data'] = $data->data;
						DB::table('communities')->where('id', $request->community_id)->update(
							[
								'eea_member' => $data->data->approval->value ? 1 : 0,
								'eea_year' => $data->data->approval->value ? $data->data->year->value : 0,
								'eea_value' => $data->data->approval->value ? $data->data->rating->value : 0,
								'eea_status' => 0
							]
						);
						return $this->send_response($results, 'Зміни збережено');
					}
					if(isset($data->evtName) && isset($data->payload)){
						$results['data'] = [
							'status' => 'approved',
							'name' => 'ЄЕВ',
							'id' => $data->payload->id,
							'participant' => true,
							'year' => $data->payload->year,
							'input' => $data->payload->input,
							'buttons' => ['off']
						];
						if($data->evtName == 'verify'){
							if(empty($data->payload->year) || empty($data->payload->input)){
								return $this->send_error('Заповніть рік та значення', 403);
							}
							DB::table('communities')->where('id', $request->community_id)->update(
								[
									'eea_member' => 1,
									'eea_year' => $data->payload->participant ? $data->payload->year : 0,
									'eea_value' => $data->payload->participant ? $data->payload->input : 0,
									'eea_status' => 1
								]
							);
						}
						if($data->evtName == 'off'){
							$results['data']['participant'] = false;
							$results['data']['buttons'] = ['verify'];
							$results['data']['status'] = 'dataentry';
							DB::table('communities')->where('id', $request->community_id)->update(
								['eea_status' => 0]
							);
						}
						return $this->send_response($results, 'Зміни збережено');
					}
					return $this->send_error('Даних не знайдено', 404);
				}
				return $this->send_error('Ви не маєте прав виконувати цю дію', 403);
			}
			return $this->send_error('Даних не знайдено', 404);
		}
		return $this->send_error('unauthorised');
	}

	public function edit_year_communities(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			if(isset($request->community_id) && isset($request->year_id)){
				if($this->is_admin_or_approved_manager($user, $request->community_id)){
					$community = Communities::where('id', $request->community_id)->get()->first();
					if(!empty($community)){
						$data = json_decode($request->getContent());
						$measures_values = Measures_values::where([['community_id', $request->community_id], ['year_id', $request->year_id]])->whereNull('file_id')->get()->toArray();
						if(!count($measures_values)){
							if(isset($data->evtName)){
								switch($data->evtName){
									case 'validate':
										$calculation = new CalculationController;
										$calculation->calculate_groups_total($request->community_id, $request->year_id, 'for approval');
										$calculation->calculate_year_total($request->community_id, $request->year_id, 'for approval');
										$total = Total_values::where([['community_id', $request->community_id], ['year_id', $request->year_id], ['type', 'year']])->get()->first();
										if(!empty($total)){
											$results['data'] = [
												'id' => $total->id,
												'status' => 'verification',
												'year' => $total->year_name,
												'year_id' => $total->year_id,
												'community_id' => $request->community_id,
												'totalPoints' => $total->value,
												'slug' => $total->year_name,
												'fillPercent' => $total->percent_values,
												'buttons' => ['view']
											];
										}
										break;
									case 'approve':
										if(in_array($user->role_id, $this->allowed_roles)){
											$calculation = new CalculationController;
											$calculation->calculate_groups_total($request->community_id, $request->year_id, 'approved');
											$calculation->calculate_year_total($request->community_id, $request->year_id, 'approved');
											$total = Total_values::where([['community_id', $request->community_id], ['year_id', $request->year_id], ['type', 'year']])->get()->first();
											if(!empty($total)){
												$results['data'] = [
													'status' => 'approved',
													'year' => $total->year_name,
													'year_id' => $total->year_id,
													'community_id' => $request->community_id,
													'totalPoints' => $total->value,
													'id' => $request->community_id . '_' . $total->year_id,
													'approvalPercent' => $total->percent_values,
													'verificationPercent' => $total->percent_approved_values,
													'path' => '/admin/communities/' . $community->slug . '/' . $total->year_name,
													'buttons' => ['view', 'block']
												];
											}
										}else{
											return $this->send_error('цю дію може робити лише адміністратор', 403);
										}
										break;
									case 'block':
										if(in_array($user->role_id, $this->allowed_roles)){
											$calculation = new CalculationController;
											$calculation->calculate_groups_total($request->community_id, $request->year_id, 'for approval');
											$calculation->calculate_year_total($request->community_id, $request->year_id, 'for approval');
											$total = Total_values::where([['community_id', $request->community_id], ['year_id', $request->year_id], ['type', 'year']])->get()->first();
											if(!empty($total)){
												$results['data'] = [
													'status' => 'verification',
													'year' => $total->year_name,
													'year_id' => $total->year_id,
													'community_id' => $request->community_id,
													'totalPoints' => $total->value,
													'id' => $total->id,
													'approvalPercent' => $total->percent_values,
													'verificationPercent' => $total->percent_approved_values,
													'path' => '/admin/communities/' . $community->slug . '/' . $total->year_name,
													'buttons' => ['approve', 'viewVerify']
												];
											}
										}else{
											return $this->send_error('цю дію може робити лише адміністратор', 403);
										}
										break;
								}
							}else{
								$calculation = new CalculationController;
								$calculation->calculate_groups_total($request->community_id, $request->year_id, 'for approval');
								$calculation->calculate_year_total($request->community_id, $request->year_id, 'for approval');
							}
							return $this->send_response($results, 'Зміни збережено');
						}
						return $this->send_error('До усіх показників потрібно прикріпити файл підтвердження', 403);
					}
					return $this->send_error('Громади не знайдено', 404);
				}
				return $this->send_error('Ви не маєте прав виконувати цю дію', 403);
			}
			return $this->send_error('Громади не знайдено', 404);
		}
		return $this->send_error('unauthorised');
	}

	public function edit_total_communities(Request $request){
		$user = Auth::user();
		$results = [];
		if($user){
			if(isset($request->total_id)){
				$data = json_decode($request->getContent());
				$total_id = explode('_', $request->total_id);
				$community_id = $total_id[0];
				$year_id = isset($total_id[1]) ? $total_id[1] : false;
				if(isset($data->evtName) && !empty($data->evtName) && (!empty($community_id) && !empty($year_id))){
					$measures_values = Measures_values::where([['community_id', $community_id], ['year_id', $year_id], ['value', '!=', '']])->whereNull('file_id')->get()->toArray();
					if(!count($measures_values)){
						//$row_id = $community_id . '_' . $year_id;
						$row_id = $request->total_id;
						switch($data->evtName){
							case 'approve':
								if($this->is_admin($user)){
									$calculation = new CalculationController;
									$calculation->calculate_groups_total($community_id, $year_id, 'approved');
									$calculation->calculate_year_total($community_id, $year_id, 'approved');
									$total = Total_values::where([['community_id', $community_id], ['year_id', $year_id]])->get()->first();
									if(!empty($total)){
										$community = Communities::where('id', $total->community_id)->get()->first();
										$results['data'] = [
											'status' => 'approved',
											'year' => $total->year_name,
											'year_id' => $total->year_id,
											'community_id' => $total->community_id,
											'totalPoints' => intval($total->value),
											'id' => $row_id,
											'approvalPercent' => $total->percent_values,
											'verificationPercent' => $total->percent_approved_values,
											'path' => '/admin/communities/' . $community->slug . '/' . $total->year_name,
											'buttons' => ['view', 'off']
										];
									}
								}
								break;
							case 'block':
								if($this->is_admin($user)){
									$calculation = new CalculationController;
									$calculation->calculate_groups_total($community_id, $year_id, 'for approval');
									$calculation->calculate_year_total($community_id, $year_id, 'for approval');
									$total = Total_values::where([['community_id', $community_id], ['year_id', $year_id]])->get()->first();
									if(!empty($total)){
										$community = Communities::where('id', $total->community_id)->get()->first();
										$results['data'] = [
											'status' => 'verification',
											'year' => $total->year_name,
											'year_id' => $total->year_id,
											'community_id' => $total->community_id,
											'totalPoints' => intval($total->value),
											'id' => $row_id,
											'approvalPercent' => $total->percent_values,
											'verificationPercent' => $total->percent_approved_values,
											'path' => '/admin/communities/' . $community->slug . '/' . $total->year_name,
											'buttons' => $total->percent_approved_values == 100 ? ['viewVerify', 'approve'] : ['viewVerify']
										];
									}
								}
								break;
							case 'validate':
								$calculation = new CalculationController;
								$calculation->calculate_groups_total($community_id, $year_id, 'for approval');
								$calculation->calculate_year_total($community_id, $year_id, 'for approval');
								$total = Total_values::where([['community_id', $community_id], ['year_id', $year_id]])->get()->first();
								if(!empty($total)){
									if($this->is_admin_or_approved_manager($user, $total->community_id)){
										$community = Communities::where('id', $total->community_id)->get()->first();
										$results['data'] = [
											'status' => 'verification',
											'year' => $total->year_name,
											'year_id' => $total->year_id,
											'community_id' => $total->community_id,
											'totalPoints' => '---',
											//'id' => $total->id,
											'id' => $row_id,
											'approvalPercent' => 0,
											'verificationPercent' => 0,
											'path' => '/admin/communities/' . $community->slug . '/' . $total->year_name,
											'buttons' => ['view']
										];
									}
								}
								break;
						}
						return $this->send_response($results, 'Зміни збережено');
					}
					return $this->send_error('До усіх показників потрібно прикріпити файл підтвердження', 403);
				}
				return $this->send_error('Ви не маєте прав виконувати цю дію', 403);
			}
			return $this->send_error('Даних не знайдено', 404);
		}
		return $this->send_error('unauthorised');
	}

	public function get_community_avatar(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			if(isset($request->community_id)){
				if($this->is_admin_or_approved_manager($user, $request->community_id)){
					$community = Communities::where('slug', $request->community_id)->get()->first();
					if(!empty($community)){
						if(!empty($community->picture)){
							$results['image'] = URL::to('/') . Storage::url($community->picture);
						}else{
							$results['image'] = false;
						}
						return $this->send_response($results, 'Зміни збережено');
					}else{
						return $this->send_error('not found', 404);
					}
				}else{
					return $this->send_error('forbidden', 403);
				}
			}
		}
		return $this->send_response($results, 'Зміни збережено');
	}

	public function edit_community_avatar(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			if(isset($request->community_id)){
				$community = Communities::where('slug', $request->community_id)->get()->first();
				if(!empty($community)){
					if($this->is_admin_or_approved_manager($user, $community->id)){
						if($request->hasFile('file')){
							$file = $request->file('file')->store('public/uploads');
							DB::table('communities')->where('id', $community->id)->update(
								array(
									'picture' => $file
								)
							);
						}
						return $this->send_response($results, 'Зміни збережено');
					}else{
						return $this->send_error('forbidden', 403);
					}
				}else{
					return $this->send_error('not found', 404);
				}
			}
		}
		return $this->send_response($results, 'Зміни збережено');
	}

	public function active_community(Request $request){
		$user = Auth::user();
		$results = array();
		if($user){
			if($this->is_admin($user)){
				if(isset($request->community_id)){
					//evtName
					$data = json_decode($request->getContent());
					if(isset($data->evtName) && $data->evtName == 'off'){
						DB::table('communities')->where('id', $request->community_id)->delete();
						return $this->send_response($results, 'Громаду видалено');
					}else{
						DB::table('communities')->where('id', $request->community_id)->update(['status' => 'active']);
						$community = Communities::where('id', $request->community_id)->get()->first();
						$manager = User::where([['community_id', $community->id]])->first();
						$community_status = $community->status != 'active' ? 'blocked' : 'approved';
						$results = [
							'id' => $community->id,
							'community_id' => $community->id,
							'year_id' => 0,
							'slug' => $community->slug,
							'name' => $community->name,
							'status' => $community_status,
							'eecu' => $community->eea_member == '1' && $community->eea_status == '1' ? 'так' : 'ні',
							'year' => $community->eea_year,
							'percent' => $community->eea_value,
							'profile' => $this->community_data_filling($community->id),
							'manager' => !empty($manager) ? $manager->name : '',
							'buttons' => $community->status == 'active' ? ['view'] : ['approve']
						];
						return $this->send_response($results, 'Зміни збережено');
					}
				}

			}
		}
		return $this->send_error('forbidden', 403);
	}

	public function community_data_filling($community_id){
		$fields = array('chief', 'contact_person', 'phone', 'email', 'picture');
		$community = Communities::where('id', $community_id)->get()->first();
		if(!empty($community)){
			$count = 0;
			foreach($fields as $field){
				if(isset($community->{$field}) && !empty($community->{$field})){
					$count++;
				}
			}
			if((isset($community->lat) && !empty($community->lat)) && (isset($community->lng) && !empty($community->lng))){
				$count++;
			}
			return $count . '/6';
		}else{
			return '0';
		}
	}
}