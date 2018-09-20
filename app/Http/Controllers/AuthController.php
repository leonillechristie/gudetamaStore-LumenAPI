<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		$formData = $request->all();
		$isAuthenticated = false;
		$hasErrors = false;
		$response = [
			'authenticated' => $isAuthenticated
		];

		$fields = ['email', 'password'];

		foreach($fields as $field) {
			if (!array_key_exists($field, $formData)) {
				$response['errors'][] = "Missing required field: {$field}";
				$hasErrors = true;
			}
		}

		$user = User::whereEmail($formData['email'])->first();

		if (is_a($user, 'App\User') && Hash::check($formData['password'], $user->password) && !$hasErrors) {
			$response = $user;
			$isAuthenticated = true;
		} else {
			$response = "";
			$isAuthenticated = false;
		}

		//bjork bork bork
		// if (!$hasErrors && $formData['email'] === 'test@test.com' && $formData['password'] === 'yeshello') {
		// 	$isAuthenticated = true;
		// 	$response['authenticated'] = $isAuthenticated;
		// }
		return response()->json([ 'data' =>  $response , "authenticated" => $isAuthenticated ]);
	}

	// validation for update users
	public function updateUser(Request $request)
	{
		$formData = $request->all();
		$isAuthenticated = false;
		$hasErrors = false;
		$response = [];

		$fields = ['name', 'email', 'password'];

		foreach($fields as $field) {
			if (!array_key_exists($field, $formData)) {
				$response['errors'][] = "Missing required field: {$field}";
				$hasErrors = true;
			}
		}

		if($hasErrors == false) {
			$isAuthenticated = true;
		}

		$response['authenticated'] = $isAuthenticated;

		// return response()->json($response);
		return response()->json([ 'data' =>  $response , "authenticated" => $isAuthenticated ]);
	}
}