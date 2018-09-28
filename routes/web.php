<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->group(['prefix'=>'api/v1'], function() use($router){
	$router->get('/products', 'ProductController@index');
	$router->post('/products', 'ProductController@create');
	$router->get('/products/{id}', 'ProductController@show');
	$router->put('/products/{id}', 'ProductController@update');
	$router->delete('/products/{id}', 'ProductController@destroy');

	$router->post('/login', 'AuthController@login');
	$router->put('/update', 'AuthController@updateUser');
	$router->delete('/destroy', 'UserController@destroy');
	$router->post('/create', 'UserController@create');

	$router->get('/users', 'UserController@index');
	$router->post('/users', 'UserController@create');
	$router->get('/users/{id}', 'UserController@show');
	$router->put('/users/{id}', 'UserController@update');
	$router->delete('/users/{id}', 'UserController@destroy');

	$router->get('/users/{id}/products', 'UserController@getAllProducts');		
	$router->post('/orders', 'OrderController@addOrder');
	$router->delete('/orders/{id}', 'OrderController@removeOrder');
	$router->delete('/orders/{id}/products/{productId}', function($id, $productId) {
		$order = App\Order::with('products')->find($id);
		$order->products()->detach($productId);

		$order->products()->get()->each(function ($product) use($productId) {
			if ($product->id == $productId) {
				$product->delete();
			}
		});
		$order = App\Order::with('products')->find($id);

		return response()->json($order);
	});
});

$router->group(['middleware' => 'auth', 'prefix'=>'api/v1'], function () use ($router) {
    $router->get('me', function(Illuminate\Http\Request $request) {
    	return response()->json(['data' => $request->user()], 200);
    });
});
