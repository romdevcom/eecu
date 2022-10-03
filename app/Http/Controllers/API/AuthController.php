<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends BaseController
{
	public $recaptcha_private_key = '6Lct7QMfAAAAAKjJQ0MsPVtiajUs5TJXHb5_HZUK';

	/**
	 * Register api
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email',
			'password' => 'required',
			'c_password' => 'required|same:password',
		]);

		if($validator->fails()){
			return $this->send_error('Validation Error.', $validator->errors());
		}

		$input = $request->all();
		$input['password'] = bcrypt($input['password']);
		$user = User::create($input);
		$success['token'] =  $user->createToken('EECU')->plainTextToken;
		$success['name'] =  $user->name;

		return $this->send_response($success, 'User register successfully.');
	}

	/**
	 * Login api
	 *
	 * @return \Illuminate\Http\Response
	 */

	/**
	 * @OA\Post(
	 * path="/api/login",
	 * summary="Login",
	 * description="Enter with email and password",
	 * operationId="authLogin",
	 * tags={"Аутинтифікація"},
	 * @OA\SecurityScheme(
	 *      securityScheme="bearerAuth",
	 *      in="header",
	 *      name="bearerAuth",
	 *      type="http",
	 *      scheme="bearer",
	 *      bearerFormat="JWT",
	 * ),
	 * @OA\RequestBody(
	 *    required=true,
	 *    description="Enter data for login",
	 *    @OA\JsonContent(
	 *       required={"email","password"},
	 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
	 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
	 *    ),
	 * ),
	 * @OA\Response(
	 *    response=422,
	 *    description="Unauthorised",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="unauthorised")
	 *        )
	 *     )
	 * )
	 */
	public function login(Request $request){
		if(isset($request->recaptchaToken)){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $this->recaptcha_private_key, 'response' => $request->recaptchaToken)));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
			$recaptcha_response = json_decode($response, true);
			if(!$recaptcha_response['success'] || $recaptcha_response['score'] < 0.5){
				return $this->send_error('Помилка recaptcha');
			}
		}

		if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
			$user = Auth::user();
			if($user->status == 'approved'){
				DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();
				$success['token'] = $user->createToken('EECU')->plainTextToken;
				$success['name'] = $user->name;
				return $this->send_response($success, 'user login successfully');
			}
		}
		return $this->send_error('unauthorised');
	}

	/** @OA\Delete(
	 * path="/api/logout",
	 * summary="Logout",
	 * description="User logout",
	 * operationId="authLogout",
	 * tags={"Аутинтифікація"},
	 * security={ {"bearer": {} }},
	 * @OA\Response(
	 *    response=200,
	 *    description="User should be authorized to get information<br><br> Headers have to contain:
	 *    <br><br> <em>Accept=application/json<br>Authorization=Bearer token_here</em>"
	 *     ),
	 * @OA\Response(
	 *    response=401,
	 *    description="",
	 *    @OA\JsonContent(
	 *       @OA\Property(property="message", type="string", example="Unauthenticated."),
	 *    )
	 * )
	 * )
	 */
	public function logout()
	{
		$user = Auth::user();
		if($user){
			Auth::guard('web')->logout();
			return $this->send_response(array(), 'success');
		}
		return $this->send_error('unauthorised');
	}
}