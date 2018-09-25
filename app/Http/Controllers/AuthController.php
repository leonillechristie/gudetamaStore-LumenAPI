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
		return response()->json([ 'data' =>  $response , "authenticated" => $isAuthenticated ]);
	}

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

		return response()->json([ 'data' =>  $response , "authenticated" => $isAuthenticated ]);
	}
}