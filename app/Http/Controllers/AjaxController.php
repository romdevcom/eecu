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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;

class AjaxController extends Controller
{
	public function search_communities(Request $request){
		$html = '';
		$lang = isset($request->lang) ? $request->lang : 'uk';
		if(isset($request->value)){
			if($lang == 'en'){
				$communities = Communities::where('name_en', 'LIKE', '%' . $request->value . '%')->get();
			}else{
				$communities = Communities::where('name', 'LIKE', '%' . $request->value . '%')->get();
			}
			if(!empty($communities) && count($communities)){
				foreach($communities as $community){
					if($lang == 'en'){
						$html .= '<li><a href="' . url('en/rating/communities/' . $community->slug) . '">' . $community->name_en . '</a></li>';
					}else{
						$html .= '<li><a href="' . url('rating/communities/' . $community->slug) . '">' . $community->name . '</a></li>';
					}
				}
			}
		}
		return $html;
	}

	public function form_submit(Request $request){
		if(isset($request->token)){
			$curl_data = array(
				'secret' => '6Lct7QMfAAAAAKjJQ0MsPVtiajUs5TJXHb5_HZUK',
				'response' => $request->token
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curl_data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$curl_response = curl_exec($ch);
			$captcha_response = json_decode($curl_response, true);
			if(!$captcha_response['success'] || $captcha_response['score'] < 0.5){
				return 'Виникла помилка з сервісом recaptcha';
			}
		}

		if(isset($request->fields) && !empty($request->fields)){
			$fields = json_decode($request->fields);
			if(isset($fields->form_password) && isset($fields->form_password_repeat) && $fields->form_password == $fields->form_password_repeat && mb_strlen($fields->form_password) > 7){
				if(isset($fields->form_firstname) && isset($fields->form_lastname) && isset($fields->form_position) && isset($fields->form_community) && isset($fields->form_email)){
					$user = User::where('email', $fields->form_email)->get()->first();
					if(empty($user)){
						$community = Communities::where('name', $fields->form_community)->get()->first();
						if(empty($community)){
							$community_id = DB::table('communities')->insertGetId(
								[
									'name' => $fields->form_community,
									'slug' => $this->make_transliteration($fields->form_community),
									'contact_person' => $fields->form_firstname . ' ' . $fields->form_lastname,
									'email' => $fields->form_email,
									'status' => 'waiting'
								]
							);
						}else{
							$community_id = $community->id;
						}
						$email_code = $this->generate_random_string(25);
						$hash = app('hash');
						$user_id = DB::table('users')->insertGetId(
							[
								'role_id' => 3,
								'name' => $fields->form_firstname . ' ' . $fields->form_lastname,
								'first_name' => $fields->form_firstname,
								'last_name' => $fields->form_lastname,
								'position' => $fields->form_position,
								'email' => $fields->form_email,
								'phone' => isset($fields->form_phone) ? $fields->form_phone : '',
								'email_verified' => $email_code,
								'community_id' => $community_id,
								'password' => Hash::make($fields->form_password),
								'status' => 'unverified'
							]
						);

						$mail = [
							'code' => $email_code
						];
						\Mail::to('m.romaniv@sitegist.com')->send(new \App\Mail\MailRegister(json_decode(json_encode($mail), true)));

						return 'success';
					}
					return 'Такого користувача вже створено, напишіть адміністратору.';
				}
				return 'Необхідно внести всі дані.';
			}
			return 'Помилка при введенні паролю чи повторного паролю. Пароль повинен містити більше 7-ми символів';
		}
	}

	public function form_contact(Request $request){
		if(isset($request->token)){
			$curl_data = array(
				'secret' => '6Lct7QMfAAAAAKjJQ0MsPVtiajUs5TJXHb5_HZUK',
				'response' => $request->token
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curl_data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$curl_response = curl_exec($ch);
			$captcha_response = json_decode($curl_response, true);
			if(!$captcha_response['success'] || $captcha_response['score'] < 0.5){
				return 'Виникла помилка з сервісом recaptcha';
			}
		}

		if(isset($request->fields) && !empty($request->fields)){
			$fields = json_decode($request->fields);
			$field_name = isset($fields->form_name) ? $fields->form_name : '';
			$field_phone = isset($fields->form_phone) ? $fields->form_phone : '';
			$field_email = isset($fields->form_email) ? $fields->form_email : '';
			$field_message = isset($fields->form_message) ? $fields->form_message : '';
			$request_id = DB::table('requests')->insertGetId(
				[
					'name' => $field_name,
					'phone' => $field_phone,
					'email' => $field_email,
					'message' => $field_message,
				]
			);

			\Mail::to('m.romaniv@sitegist.com')->send(new \App\Mail\MailContact(json_decode(json_encode($fields), true)));

			return 'success';
		}
	}

	public function generate_random_string($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen($characters);
		$random_string = '';
		for ($i = 0; $i < $length; $i++) {
			$random_string .= $characters[rand(0, $characters_length - 1)];
		}
		return $random_string;
	}

	function make_transliteration($string_cyr = false, $string_lat = false){
		$string = '';
		$cyr = array(
			'щ', 'ж', 'х', 'є', 'ц', 'ч', 'ш', 'ю', 'я', 'й', 'ї', 'а', 'б', 'в', 'г', 'ґ', 'д', 'е', 'з', 'и', 'і', 'к',
			'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'ий', 'ь');
		$lat = array(
			'shch', 'zh', 'kh', 'ie', 'ts', 'ch', 'sh', 'iu', 'ia', 'iy', 'ij', 'a', 'b', 'v', 'h', 'g', 'd', 'e', 'z', 'y', 'i', 'k',
			'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'ии', '');
		if(!empty($string_cyr) || !empty($string_lat)){
			if(!empty($string_cyr)){
				$string = mb_strtolower($string_cyr);
//            $string = str_replace(array(',', '.', ':', '"', '\'', '(', ')', '+', '-', '—', '–', '’', '?', '!', '«', '»', '/'), array(''), mb_strtolower($string_cyr));
				$string = str_replace(' ', '-', $string);
				$string = rtrim(str_replace($cyr, $lat, $string), '-');
				$string = preg_replace('/[^a-z0-9 \-]/ui', '', $string);
				$string = str_replace('--', '-', $string);
				$string = str_replace('--', '-', $string);
			}else{
				$string = str_replace($lat, $cyr, $string_lat);
			}
		}
		return rtrim($string, '-');
	}
}
