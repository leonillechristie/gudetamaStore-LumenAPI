<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		$formData = $request->all();
		$isAuthenticated = false;
		$hasErrors = false;
		$response = [];

		$fields = ['email', 'password'];

		foreach($fields as $field) {
			if (!array_key_exists($field, $formData)) {
				$response['errors'][] = "Missing required field: {$field}";
				$hasErrors = true;
			}
		}

		if (!$hasErrors && $formData['email'] === 'test@test.com' && $formData['password'] === 'yeshello') {
			$isAuthenticated = true;
			$response['authenticated'] = $isAuthenticated;
		}


		return response()->json($response);
	}
}