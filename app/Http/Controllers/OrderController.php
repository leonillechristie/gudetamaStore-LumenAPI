<?php
namespace App\Http\Controllers;

use App\User;
use App\Order;
use App\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class OrderController extends Controller
{
  public function addOrder(Request $request)
  {
    $results = ['statusCode' => 200];

    try {
      $params = $request->all();
      
      $order = new Order;
      $order->user()->associate(User::find($params['user_id']));

      if (!is_a($order, 'App\Order')) {
        throw new \RuntimeException('Order with id: { $id } not found.');
      }

      $order->products()->attach($params['products']);
      $order->save();

      $order = Order::with('user', 'products')->find($order->id);
    } catch(\Exception $e) {
      $results['error'] = $e->getMessage();
      $results['statusCode'] = 500;
    }
    return response()->json($order, $results['statusCode']);
  }

  public function removeOrder($id)
  {
    $results = ['statusCode' => 200];
    $message = "";
    $productIds = [];

    try {
      $orders = Order::with('user', 'products')->find($id);

      if (!is_a($order, 'App\Order')) {
        throw new \RuntimeException('Order with id: { $id } not found.');
      }

      foreach($order->products as $product) {
        $productIds[] = $product->id;
      }

      $order->products()->detach($productIds);
      $order->delete();

      $message = "User successfully deleted.";

    } catch(\Exception $e) {
      $results['error'] = $e->getMessage();
      $results['statusCode'] = 500;
    } 

    return response()->json($orders, $results['statusCode']);
  }
}