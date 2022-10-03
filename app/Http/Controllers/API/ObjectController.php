<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Years;
use App\Models\Sources;
use App\Models\Numbers;
use App\Models\Groups;
use App\Models\Sectors;
use App\Models\Indicators;
use App\Models\Communities;

class ObjectController extends BaseController
{
	public function get_sources(Request $request){
		$user = Auth::user();
		$results = array();
		if($user && in_array($user->role_id, $this->allowed_roles)){
			$model = new Sources;
			$results['list'] = $this->get($model, 'id,name,code', [['public', 1],['status', 'active']]);
		}
		return $this->send_response($results, 'success');
	}

	/** @OA\Get(
	 * path="/api/numbers",
	 * summary="Отримати список показники",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="NumbersGet",
	 * tags={"Показники (numbers)"},
	 * security={ {"bearer": {} }},
	 * @OA\Response(
	 *    response=200,
	 *    description="Буде повернено масив з показники",
	 *     @OA\JsonContent(
	 *       @OA\Property(
	 *          property="list",
	 *          type="array",
	 *          @OA\Items(
	 *	            @OA\Property(property="id", type="integer", example="1"),
	 *              @OA\Property(property="name", type="string", example="Показник"),
	 *              @OA\Property(property="code", type="string", example="22"),
	 *              @OA\Property(property="dimension", type="integer", example="1"),
	 *              @OA\Property(property="precision", type="integer", example="1"),
	 *              @OA\Property(property="source_id", type="integer", example="22"),
	 *          ),
	 *      ),
	 *     @OA\Property(property="message", type="string", example="success"),
	 *     @OA\Property(property="success", type="string", example="true"),
	 *    )
	 *     ),
	 * @OA\Response(
	 *    response=401,
	 *    description="",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Not authorized"),
	 *    )
	 * )
	 * )
	 */
	public function get_numbers(Request $request){
		$user = Auth::user();
		$results = array();
		if($user && in_array($user->role_id, $this->allowed_roles)){
			$model = new Numbers;
			$results['list'] = $this->get($model, 'id,name,code,dimension,precision,source_id', [['status', 'active']]);
		}
		return $this->send_response($results, 'success');
	}

	/** @OA\Get(
	 * path="/api/groups",
	 * summary="Отримати список груп",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="GroupsGet",
	 * tags={"Групи (groups)"},
	 * security={ {"bearer": {} }},
	 * @OA\Response(
	 *    response=200,
	 *    description="Буде повернено масив з групами",
	 *     @OA\JsonContent(
	 *       @OA\Property(
	 *          property="list",
	 *          type="array",
	 *          @OA\Items(
	 *	            @OA\Property(property="id", type="integer", example="1"),
	 *              @OA\Property(property="name", type="string", example="Група"),
	 *              @OA\Property(property="code", type="string", example="22"),
	 *              @OA\Property(property="icon", type="string", example="icon"),
	 *          ),
	 *      ),
	 *     @OA\Property(property="message", type="string", example="success"),
	 *     @OA\Property(property="success", type="string", example="true"),
	 *    )
	 *     ),
	 * @OA\Response(
	 *    response=401,
	 *    description="",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Not authorized"),
	 *    )
	 * )
	 * )
	 */
	public function get_groups(Request $request){
		$user = Auth::user();
		$results = array();
		if($user && in_array($user->role_id, $this->allowed_roles)){
			$model = new Groups;
			$results['list'] = $this->get($model, 'id,name,code,icon', [['status', 'active']]);
		}
		return $this->send_response($results, 'success');
	}

	/** @OA\Get(
	 * path="/api/sectors",
	 * summary="Отримати список секторів",
	 * description="Користувач повинен бути авторизований<br><br> Щоб використати токен Headers запиту має містити:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>",
	 * operationId="SectorsGet",
	 * tags={"Сектори (sectors)"},
	 * security={ {"bearer": {} }},
	 * @OA\Response(
	 *    response=200,
	 *    description="Буде повернено масив з секторами",
	 *     @OA\JsonContent(
	 *       @OA\Property(
	 *          property="list",
	 *          type="array",
	 *          @OA\Items(
	 *	            @OA\Property(property="id", type="integer", example="1"),
	 *              @OA\Property(property="name", type="string", example="Сектор"),
	 *              @OA\Property(property="code", type="string", example="22"),
	 *              @OA\Property(property="group_id", type="integer", example="22"),
	 *          ),
	 *      ),
	 *     @OA\Property(property="message", type="string", example="success"),
	 *     @OA\Property(property="success", type="string", example="true"),
	 *    )
	 *     ),
	 * @OA\Response(
	 *    response=401,
	 *    description="",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Not authorized"),
	 *    )
	 * )
	 * )
	 */
	public function get_sectors(Request $request){
		$user = Auth::user();
		$results = array();
		if($user && in_array($user->role_id, $this->allowed_roles)){
			$model = new Sectors;
			$results['list'] = $this->get($model, 'id,name,code,group_id', [['status', 'active']]);
		}
		return $this->send_response($results, 'success');
	}
}