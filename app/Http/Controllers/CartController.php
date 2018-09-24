<?php
namespace App\Http\Controllers;
use App\Cart;
use Illuminate\Http\Request;
class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index(Request $request)
    {
    
    $query = $request->all();

    $cartItems = Cart::all();
    $results[] = $data;
 

  	if (array_key_exists('productname', $query)) {
  		$filtered = [];
  		$productname =strtolower($query['productname']);

  		foreach($results as $cartItem) {
  			if (preg_match("/($productname)/", strtolower($cartItem->attributes->productname))) {
  				$filtered[] = $cartItem;
  			}
  		}
  		$results = $filtered;
  	}

    return response()->json(['data' => $results]);
    }

     public function add(Request $request)
     {
       $results = ['statusCode' => 200];

       try {
            $formData = $request->all();
            $requiredFields = ['productname'];
            
            $cartItem = new Product;

            $cartItem->title= $request->productname;

            foreach ($requiredFields as $field) {
              if (!array_key_exists($field, $formData)) {
                throw new \InvalidArgumentException("Missing required field: {$field}");
              }

              if (strlen($formData[$field]) < 1) {
                throw new \InvalidArgumentException("Invalid field: {$field}, must be a valid string.");   
              }
            }

            $cartItem->save();
            $results['data'] = $cartItem;
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
          $cartItem = Cart::find($id);

          if (!is_a($cartItem, 'App\Cart')) {
            throw new \RuntimeException('Product with id: { $id } not in cart.');
          }
        } catch(\Exception $e) {
          $results['error'] = $e->getMessage();
          $results['statusCode'] = 500;
        }

        return response()->json($cartItem, $results['statusCode']);
     }

     public function remove($id)
     {
      $results = ['statusCode' => 200];
      $message = "";
        try {
          $cartItem = Cart::find($id);

          if (!is_a($cartItem, 'App\Cart')) {
            throw new \RuntimeException('CartItem with id: { $id } not found. Cannot be deleted.');
          }

          $cartItem->delete();
          $message = "Product successfully deleted.";

        } catch(\Exception $e) {
          $results['error'] = $e->getMessage();
          $results['statusCode'] = 500;
        }

        return response()->json($message, $results['statusCode']);
     }
}