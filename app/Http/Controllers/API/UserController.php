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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

class UserController extends BaseController
{
	public function user_row($user, $status = 'active'){
		$user = User::where('id', $user->id)->get()->first();
		$community_name = '';
		if(!empty($user->community_id)){
			$community = Communities::where('id', $user->community_id)->get()->first();
			$community_name = $community->name;
		}
		return [
			'id' => $user->id,
			'name' => $user->name,
			'phone' => $user->phone,
			'email' => $user->email,
			'role_id' => $user->role_id,
			'role' => $user->role,
			'community' => $community_name,
			'status' => $status == 'approved' ? 'approved' : 'active',
			'buttons' => $status == 'approved' ? ['block'] : ['authorize'],
			'actions' => ['edit', 'delete'],
		];
	}

	public function get_users(Request $request){
		$user = Auth::user();
		if($user){
			if(in_array($user->role_id, $this->allowed_roles)){
				$query = DB::table('users');
				switch($request->status){
					case 'active':
						$query->where('status', 'approved');
						break;
					case 'blocked':
						$query->where('status', 'blocked');
						break;
					case 'unverified':
						$query->where('status', 'unverified');
						break;
					case 'requests':
						$query->where('status', 'verified');
						break;
				}
				$list = $query->select('id', 'name as user_name', 'phone', 'email', 'community_id', 'status')->get();
				if(isset($list) && !empty($list)){
					$results = array(
						'pageTitle' => 'Користувачі'
					);
					$headers = array(
						$this->table_header_col('Ім’я', 'name', true),
						$this->table_header('Громада', 'community', true),
						$this->table_header('Телефон', 'phone', false),
						$this->table_header('Емейл', 'email', false),
						$this->table_header_col('', 'buttons', false, 'btn'),
						$this->table_header_col('', 'actions', false, 'action')
					);
					$rows = [];
					foreach($list as $item){
						if(!in_array($request->status, ['requests', 'unverified']) || !in_array($item->status, ['approved', 'blocked'])){
							$community = Communities::where('id', $item->community_id)->get()->first();
							$rows[] = [
								'id' => $item->id,
								'name' => $item->user_name,
								'community' => !empty($community) ? $community->name : '',
								'phone' => $item->phone,
								'email' => $item->email,
								'buttons' => $item->status != 'approved' ? array('authorize') : array('block'),
								'actions' => array('edit', 'delete'),
							];
						}
					}
					$results['table'] = $this->table('', $headers, $rows, 'communities/users');
					return $this->send_response($results, 'success');
				}else{
					return $this->send_error('not found', 404);
				}
			}else{
				return $this->send_error('not found', 404);
			}
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Get(
	 * path="/api/communities/users/{user_id}",
	 * summary="Отримати дані користувача вигляді form",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="UserGetID",
	 * tags={"Користувачі"},
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
	 *                  property="name",
	 *                  type="array",
	 *                  @OA\Items(
	 *	                    @OA\Property(property="label", type="string", example="Ім'я"),
	 *                      @OA\Property(property="value", type="string", example="Тест"),
	 *                  ),
	 *              ),
	 *              @OA\Property(
	 *                  property="phone",
	 *                  type="array",
	 *                  @OA\Items(
	 *	                    @OA\Property(property="label", type="string", example="Телефон"),
	 *                      @OA\Property(property="value", type="string", example="0960000000"),
	 *                  ),
	 *              ),
	 *              @OA\Property(
	 *                  property="email",
	 *                  type="array",
	 *                  @OA\Items(
	 *	                    @OA\Property(property="label", type="string", example="Email"),
	 *                      @OA\Property(property="value", type="string", example="test@email.com"),
	 *                  ),
	 *              ),
	 *              @OA\Property(
	 *                  property="role_id",
	 *                  type="array",
	 *                  @OA\Items(
	 *	                    @OA\Property(property="label", type="string", example="Роль"),
	 *                      @OA\Property(property="value", type="string", example="1"),
	 *                      @OA\Property(property="options", type="array",
	 *                          @OA\Items(
	 *	                            @OA\Property(property="label", type="string", example="Admin"),
	 *                              @OA\Property(property="value", type="string", example="1"),
	 *                          ),
	 *                      ),
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
	 *    description="Якщо користувачу заборонена ця дія",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="method is not allowed without community_id"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=404,
	 *    description="Якщо користувача не знайдено",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="community not found"),
	 *    )
	 * )
	 * )
	 */
	public function get_user_communities(Request $request){
		$user = Auth::user();
		if($user){
			if(in_array($user->role_id, $this->allowed_roles)){
				$model = new User;
				$select = 'id,role_id,name,phone,email,community_id';
				$list = $this->get($model, $select, [['id', $request->user_id]]);
				if(isset($list) && !empty($list)){
					$roles = DB::table('roles')->select('id as value', 'name as label')->get()->toArray();
					$communities = DB::table('communities')->select('id as value', 'name as label')->get()->toArray();
					$results = array(
						'id' => $list[0]->id,
						'form' => array(
							'name' => array(
								'field_type' => 'text',
								'label' => 	'Ім\'я',
								'value' => $list[0]->name
							),
							'phone' => array(
								'field_type' => 'phone',
								'label' => 	'Телефон',
								'value' => $list[0]->phone
							),
							'email' => array(
								'field_type' => 'email',
								'label' => 	'Email',
								'value' => $list[0]->email
							),
							'role_id' => array(
								'field_type' => 'select',
								'label' => 	'Роль',
								'value' => $list[0]->role_id,
								'options' => $roles
							),
							'community_id' => array(
								'field_type' => 'select',
								'label' => 	'Громада',
								'value' => intval($list[0]->community_id),
								'options' => $communities
							),
						)
					);
					return $this->send_response($results, 'success');
				}else{
					return $this->send_error('not found', 404);
				}
			}else{
				return $this->send_error('not found', 404);
			}
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Post(
	 * path="/api/communities/users/{user_id}",
	 * summary="Оновити дані користувача",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="UserUpdateByID",
	 * tags={"Користувачі"},
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
	 *    parameter="user_id",
	 *    name="user_id",
	 *    in="query",
	 *    required=true,
	 *    description="id користувача",
	 *     @OA\JsonContent(
	 *     @OA\Property(property="user_id", type="string", example="1"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=200,
	 *    description="Буде повернено id та масив form",
	 *     @OA\JsonContent(
	 *     @OA\Property(property="data", type="string", example="масив з даними, які приходили"),
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
	 *    description="Якщо користувачу заборонено використовувати цей метод",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="forbidden"),
	 *    )
	 * ),
	 * @OA\Response(
	 *    response=404,
	 *    description="Якщо не знайдено користувача",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="not found"),
	 *    )
	 * )
	 * )
	 */
	public function edit_user_communities(Request $request){
		//evtName = authorize
		//evtName = edit
		$user = Auth::user();
		$results = [];
		if($user){
			//якщо приходить id користувача тоді перевірити чи поточний користувач має права редагувати, або чи це зміна поточного користувача
			if(isset($request->user_id)){
				if(in_array($user->role_id, $this->allowed_roles) || $user->id == $request->user_id){
					$query = DB::table('users as u')->join('roles as r', 'r.id', '=', 'u.role_id')->where([['u.id', $request->user_id]]);
					$list = $query->select('u.id', 'u.name as user_name', 'u.phone', 'u.email', 'r.id as role_id', 'r.name as role', 'u.community_id', 'u.status')->get()->first();
					if(!empty($list)){
						$status = 'active';
						$data = json_decode($request->getContent());

						//якщо подія edit - тоді відредагувати значення
						if($data->evtName == 'edit'){
							$status = $list->status;
							$list->user_name = $data->payload->name->value;
							$list->phone = $data->payload->phone->value;
							$list->email = $data->payload->email->value;
							$list->role_id = $data->payload->role_id->value;
							$list->role = DB::table('roles')->where('id', $data->payload->role_id->value)->get()->pluck('name')->first();
							DB::table('users')->where('id', $request->user_id)->update(
								array(
									'name' => $data->payload->name->value,
									'phone' => $data->payload->phone->value,
									'email' => $data->payload->email->value,
									'role_id' => $data->payload->role_id->value,
									'community_id' => $data->payload->community_id->value
								)
							);
						}else{
							//якщо подія authorize чи block, тоді записуємо новий $status
							switch($data->evtName){
								case 'authorize':
									$status = 'approved';
									break;
								default:
									$status = 'blocked';
							}
							if($status != 'active'){
								DB::table('users')->where('id', $request->user_id)->update(array('status' => $status));
							}
						}
						$results['data'] = $this->user_row($list, $status);
					}else{
						return $this->send_error('not found', 404);
					}
				}else{
					return $this->send_error('forbidden', 403);
				}
				return $this->send_response($results, 'success');
			}
			return $this->send_error('not found', 404);
		}
		return $this->send_error('unauthorised');
	}

	public function delete_user_communities(Request $request){
		$user = Auth::user();
		$results = [];
		if($user){
			//якщо приходить id користувача тоді перевірити чи поточний користувач має права редагувати, або чи це зміна поточного користувача
			if(isset($request->user_id)){
				if(in_array($user->role_id, $this->allowed_roles) || $user->id == $request->user_id){
					DB::table('users')->where([['id', $request->user_id]])->delete();
				}else{
					return $this->send_error('forbidden', 403);
				}
				return $this->send_response($results, 'Користувача видалено');
			}
			return $this->send_error('not found', 404);
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Get(
	 * path="/api/users",
	 * summary="Отримати дані профіля",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="get_current_user",
	 * tags={"Користувачі"},
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
	public function get_current_user(Request $request){
		$user = Auth::user();
		if($user){
			$results = array(
				'pageTitle' => 'Мій профіль',
				'pageSubTitle' => 'Редагувати',
				'pageSubTitle2' => 'Змінити пароль',
				'user' => array(
					'id' => $user->id,
					'objType' => 'users',
					'form' => array(
						'name' => array(
							'field_type' => 'text',
							'label' => 	'Ім\'я',
							'value' => $user->name
						),
						'position' => array(
							'field_type' => 'text',
							'label' => 	'Посада',
							'value' => $user->position
						),
						'phone' => array(
							'field_type' => 'phone',
							'label' => 	'Телефон',
							'value' => $user->phone
						),
						'email' => array(
							'field_type' => 'email',
							'label' => 	'Email',
							'value' => $user->email
						)
					),
					'picture' => array(
						'url' => !empty($user->avatar) ? $this->url . Storage::url($user->avatar) : $this->default_picture,
					)
				)
			);
			return $this->send_response($results, 'success');
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Post(
	 * path="/api/users",
	 * summary="оновити дані профіля",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="edit_current_user",
	 * tags={"Користувачі"},
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
	public function edit_current_user(Request $request){
		$user = Auth::user();
		if($user){
			$results = array();
			$data = json_decode($request->getContent());
			if(in_array($user->role_id, $this->allowed_roles) || $data->id == $user->id){
				$results['data'] = array(
					'name' => $data->data->name,
					'phone' => $data->data->phone,
					'email' => $data->data->email,
					'position' => $data->data->position
				);
				DB::table('users')->where('id', $data->id)->update($results['data']);
				$results['data']['id'] = $data->id;
				return $this->send_response($results, 'success');
			}
			return $this->send_error('forbidden', 403);
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Post(
	 * path="/api/users/password",
	 * summary="оновити пароль профіля",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="edit_current_password",
	 * tags={"Користувачі"},
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
	public function edit_current_password(Request $request){
		$user = Auth::user();
		if($user){
			$data = json_decode($request->getContent());
			$hash = app('hash');
			if($data->new_password == $data->repeat_password){
				if($hash->check($data->old_password, $user->password)){
					$user->password = Hash::make($data->new_password);
					$user->save();
					$results = array();
					$results['data'] = $data;
					Auth::guard('web')->logout();
					return $this->send_response($results, 'success');
				}
				return $this->send_error('old password is not the same', 403);
			}
			return $this->send_error('new and repeat are not the same', 403);
		}
		return $this->send_error('unauthorised');
	}

	public function edit_avatar(Request $request){
		$user = Auth::user();
		if($user){
			$results = array();
			if($request->hasFile('file')){
				$file = $request->file('file')->store('public/uploads');
				$user->avatar = $file;
				$results['file'] = Storage::url($file);
				$user->save();
			}
			return $this->send_response($results, 'Зображення додано');
		}
		return $this->send_error('unauthorised');
	}
}