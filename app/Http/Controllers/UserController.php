<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index(Request $request)
    {
     	$query = $request->all();
      // echo $query;
      // $users = User::all();
    //   $users = '[
    //   {
    //     "id": 1,
    //     "type": "users",
    //     "attributes": {
    //       "userid": "1",
    //       "avatar": "https://png.icons8.com/color/1600/person-male.png",
    //       "name": "John Doe",
    //       "email": "test@test.com",
    //       "password": "secret"
    //     }
    //   },
    //   {
    //     "id": 2,
    //     "type": "users",
    //     "attributes": {
    //       "userid": "2",
    //       "avatar": "https://mbtskoudsalg.com/images/female-icon-png-4.png",
    //       "name": "Jane Doe",
    //       "email": "asd@test.com",
    //       "password": "secret"
    //     }
    //   },
    //   {
    //     "id": 3,
    //     "type": "users",
    //     "attributes": {
    //       "userid": "3",
    //       "avatar": "https://png2.kisspng.com/20180402/yie/kisspng-computer-icons-female-user-profile-avatar-material-5ac1fa22a5a567.4501855215226619226785.png",
    //       "name": "Leonille Christie",
    //       "email": "nikki@test.com",
    //       "password": "secret"
    //     }
    //   },
    //   {
    //     "id": 4,
    //     "type": "users",
    //    "attributes": {
    //       "userid": "4",
    //       "avatar": "https://png.icons8.com/color/1600/guest-female.png",
    //       "name": "Mariam Grazelle",
    //       "email": "yam@test.com",
    //       "password": "secret"
    //     }
    //   },
    //   {
    //     "id": 5,
    //     "type": "users",
    //     "attributes": {
    //       "userid": "5",
    //       "avatar": "http://www.wanpagu.sakura.ne.jp/summer/summer150.jpg",
    //       "name": "Polar Bear",
    //       "email": "bear@test.com",
    //       "password": "secret"
    //     }
    //   },
    //   {
    //     "id": 6,
    //     "type": "users",
    //     "attributes": {
    //       "userid": "6",
    //       "avatar": "http://www.wanpagu.sakura.ne.jp/summer/summer150.jpg",
    //       "name": "Update Me",
    //       "email": "upd@test.com",
    //       "password": "secret"
    //     }
    //   },
    //   {
    //     "id": 7,
    //     "type": "users",
    //     "attributes": {
    //       "userid": "7",
    //       "avatar": "http://www.wanpagu.sakura.ne.jp/summer/summer150.jpg",
    //       "name": "Update Me 2",
    //       "email": "upd2@test.com",
    //       "password": "secret"
    //     }
    //   },
    //   {
    //     "id": 8,
    //     "type": "users",
    //     "attributes": {
    //       "userid": "8",
    //       "avatar": "http://www.wanpagu.sakura.ne.jp/summer/summer150.jpg",
    //       "name": "Delete Me",
    //       "email": "del@test.com",
    //       "password": "secret"
    //     }
    //   }
    // ]';

  	// $users = json_decode($users);
    $users = User::all();
    $results = [];

    foreach($users as $user) {
      $data = new \stdClass;
      $data->id = $user->id;
      $data->type = 'users';
      $data->attributes  = $user;

      $results[] = $data;
    }

    // find users
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

        return response()->json($message, $results['statusCode']);
     }
}