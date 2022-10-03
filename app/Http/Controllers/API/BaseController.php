<?php
namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

/**
 * @OA\Info(
 *    title="EECU",
 *    version="1.0.0",
 * )
 */

/**
 * @OAS\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 **/

class BaseController extends Controller
{
	/**
	 * success response method.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public $allowed_roles = array(1, 2);
	public $url = 'https://eea-benchmark.enefcities.org.ua';
	public $default_picture = 'https://eea-benchmark.enefcities.org.ua/dashboard/_nuxt/img/avatar-big.09b3d4d.png';

	public function get($model, $select, $where = false){
		if($where){
			$results = $model::where($where)->select(explode(',', $select))->get();
		}else{
			$results = $model::select(explode(',', $select))->get();
		}
		return $results;
	}

	public function save($model, $table, $data){
		foreach($data as $key => $value){
			if(Schema::hasColumn($table, $key)){
				$model->{$key} = $value;
			}
		}
		$model->save();
	}

	public function table($name, $headers, $rows, $type = false, $quantity = false, $actions = false){
		$results = array(
			'name' => $name,
			'headers' => $headers,
			'rows' => $rows,
		);
		if(!empty($type)){
			$results['objType'] = $type;
		}
		if(!empty($actions)){
			$results['groupActions'] = $actions;
		}
		if(!empty($quantity)){
			$results['quantity'] = $quantity;
		}
		return $results;
	}

	public function table_header_col($text, $value, $sortable, $col_type = 'textBold', $tooltip = false, $type = false, $payload = '', $editable = false){
		$result = array(
			'name' => $text,
			'colType' => array(
				'name' => $col_type,
				'payload' => $payload
			),
			'value' => $value,
			'sortable' => $sortable,
		);
		if($type){
			$result['type'] = $type;
		}
		if($tooltip){
			$result['tooltip'] = $tooltip;
		}
		if(!empty($editable)){
			$result['editable'] = $editable;
		}
		return $result;
	}

	public function table_header($text, $value, $sortable, $tooltip = false, $type = false, $editable = false){
		$result = array(
			'name' => $text,
			'value' => $value,
			'sortable' => $sortable,
		);
		if($type){
			$result['type'] = $type;
		}
		if($tooltip){
			$result['tooltip'] = $tooltip;
		}
		if(!empty($editable)){
			$result['editable'] = $editable;
		}
		return $result;
	}

	public function is_admin_or_approved_manager($user, $community = false){
		return $user->status == 'approved' && (($community && $community == $user->community_id) || in_array($user->role_id, $this->allowed_roles));
	}

	public function is_admin($user){
		return $user->status == 'approved' && in_array($user->role_id, $this->allowed_roles);
	}

	public function is_approved_user($user){
		return $user->status == 'approved';
	}

	public function write_log($type, $object, $action_needed, $action_done, $user_id){
		//
	}

	public function send_response($result, $message){
		$result['success'] = true;
		$result['message'] = $message;
		$response = $result;

		return response()->json($response, 200);
	}


	/**
	 * return error response.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function send_error($error, $code = 404){
		$response = array(
			'success' => false,
			'message' => $error,
		);
		return response()->json($response, $code);
	}
}