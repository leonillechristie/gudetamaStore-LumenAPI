<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
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
       $user = new User;
       $user->userid = $request->userid;
       $user->name= $request->name;
       $user->email = $request->email;
       $user->avatar = $request->avatar;

       // $user->password= $request->password;
       
       $user->save();
       return response()->json($user);
     }
     
     public function show($id)
     {
        $user = User::find($id);
        return response()->json($user);
     }
     public function update(Request $request, $id)
     { 
        $user= User::find($id);
        \Log::info($request->all());
        
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        // $user->password = $request->input('password');
        $user->save();
        return response()->json($user);
     }
     public function destroy($id)
     {
        $user = User::find($id);
        $user->delete();
        return response()->json('User removed successfully');
     }
}