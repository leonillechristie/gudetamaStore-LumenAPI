<?php
namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->all();
    $users = User::all();
    $results = [];

    foreach($users as $user) {
      $data = new \stdClass;
      $data->id = $user->id;
      $data->type = 'users';
      $data->attributes  = $user;

      $results[] = $data;
    }

    if (array_key_exists('name', $query)) {
    	$filtered = [];
    	$name =strtolower($query['name']);

    	foreach($results as $user) {
    		if (preg_match("/($name)/", strtolower($user->attributes->name))) {
    			$filtered[] = $user;
    		}
    	}
    	$results = $filtered;
    }
    return response()->json(['data' => $results]);
  }

  public function create(Request $request)
  {
    $results = ['statusCode' => 200];
    try {
      $formData = $request->all();
      $requiredFields = ['name', 'email', 'avatar'];

      foreach ($requiredFields as $field) {
        if (!array_key_exists($field, $formData)) {
          throw new \InvalidArgumentException("Missing required field: {$field}");
        }

        if (strlen($formData[$field]) < 1) {
          throw new \InvalidArgumentException("Invalid field: {$field}, must be a valid string.");   
        }
      }
      $user = new User;

      $user->name = $request->input('name');
      $user->email = $request->input('email');
      $user->avatar = $request->avatar;
      $user->password = Hash::make('secret');

      $existingUser = User::where('email', '=', $formData['email'])->first();

      if (is_a($existingUser, 'App\User')) {
        throw new \RuntimeException('Email is already used by another user.');
      }

      if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        throw new \RuntimeException('Not a valid email address.');
      }

      $user->save();
    } catch(\Exception $e) {
      $results['error'] = $e->getMessage();
      $results['statusCode'] = 500;
    }

    return response()->json($results, $results['statusCode']);
  }
     
  public function show($id)
  {
    $results = ['statusCode' => 200];
    
    try {
      $user = User::find($id);

      if (!is_a($user, 'App\User')) {
        throw new \RuntimeException('User with id: { $id } not found.');
      }
    } catch(\Exception $e) {
      $results['error'] = $e->getMessage();
      $results['statusCode'] = 500;
    }

    return response()->json($user, $results['statusCode']);
  }

  public function update(Request $request, $id)
  {
    $results = ['statusCode' => 200];

    try {
        $formData = $request->all();
        $requiredFields = ['name', 'email'];

        foreach ($requiredFields as $field) {
          if (!array_key_exists($field, $formData)) {
            throw new \InvalidArgumentException("Missing required field: {$field}");
          }

          if (strlen($formData[$field]) < 1) {
            throw new \InvalidArgumentException("Invalid field: {$field}, must be a valid string.");   
          }
        }

        $user = User::find($id);

        if (!is_a($user, 'App\User')) {
            throw new \RuntimeException('User does not exist.');
        }

        $existingUser = User::where('email', '=', $formData['email'])->first();
        if (is_a($existingUser, 'App\User') && $existingUser->id !== $id) {
          throw new \RuntimeException('Email is already used by another user.');
        }

        if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
          throw new \RuntimeException('Not a valid email address.');
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        $results['data'] = $user;

    } catch(\Exception $e) {
        $results['error'] = $e->getMessage();
        $results['statusCode'] = 500;
    }

    return response()->json($results, $results['statusCode']);
  }

  public function destroy($id)
  {
    $results = ['statusCode' => 200];
    $message = "";

    try {
      $user = User::find($id);
      if (!is_a($user, 'App\User')) {
        throw new \RuntimeException('User with id: { $id } not found. Cannot be deleted.');
      }
      $user->delete();
      $message = "User successfully deleted.";
    } catch(\Exception $e) {
      $results['error'] = $e->getMessage();
      $results['statusCode'] = 500;
    } 
  }

  public function getAllProducts($id)
  {  
    $results = ['statusCode' => 200];
    $message = "";

    try {
      $user = User::with('orders.products')->find($id);

    } catch(\Exception $e) {
      $results['error'] = $e->getMessage();
      $results['statusCode'] = 500;
    } 
    return response()->json($user, $results['statusCode']);
  }  
}