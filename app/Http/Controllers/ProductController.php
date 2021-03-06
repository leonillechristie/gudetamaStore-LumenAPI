<?php
namespace App\Http\Controllers;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function index(Request $request)
  {
    $query = $request->all();
    $products = Product::all();
    $results = [];

    foreach($products as $product) {
      $data = new \stdClass;
      $data->id = $product->id;
      $data->type = 'products';
      $data->attributes  = $product;

      $results[] = $data;
    }

  	if (array_key_exists('title', $query)) {
  		$filtered = [];
  		$title =strtolower($query['title']);

  		foreach($results as $product) {
  			if (preg_match("/($title)/", strtolower($product->attributes->title))) {
  				$filtered[] = $product;
  			}
  		}
  		$results = $filtered;
  	}

    return response()->json(['data' => $results]);
  }

  public function create(Request $request)
  {
    $results = ['statusCode' => 200];
    $message = "";
 
    try {
      $formData = $request->all();
      $requiredFields = ['title', 'owner', 'category', 'city', 'image', 'price', 'description'];
      
      $product = new Product;

      $product->title= $request->title;
      $product->owner = $request->owner;
      $product->category= $request->category;
      $product->city = $request->city;
      $product->image = $request->image;
      $product->price = $request->price;          
      $product->description= $request->description;

      foreach ($requiredFields as $field) {
        if (!array_key_exists($field, $formData)) {
          throw new \InvalidArgumentException("Missing required field: {$field}");
        }

        if (strlen($formData[$field]) < 1) {
          throw new \InvalidArgumentException("Invalid field: {$field}, must be a valid string."); 
        }
      }

      $product->save();
      $results['data'] = $product;

    } catch(\Exception $e) {
      $results['error'] = $e->getMessage();
      $results['statusCode'] = 500;
    }
    return response()->json($results, $results['statusCode']);
  }
     
  public function show($id)
  {
    $results = ['statusCode' => 200];
    $message = "";
    
    try {
      $product = Product::find($id);

      if (!is_a($product, 'App\Product')) {
        throw new \RuntimeException('Product with id: { $id } not found.');
      }
    } catch(\Exception $e) {
      $results['error'] = $e->getMessage();
      $results['statusCode'] = 500;
    }

    return response()->json($product, $results['statusCode']);
  }

  public function update(Request $request, $id)
  { 
    $results = ['statusCode' => 200];
    $message = "";
    try {
      $formData = $request->all();

      $requiredFields = ['title', 'owner', 'category', 'city', 'image', 'price', 'description'];
      
      $product = Product::find($id);

      if (!is_a($product, 'App\Product')) {
          throw new \RuntimeException('Product does not exist.');
      }

      foreach ($requiredFields as $field) {
        if (!array_key_exists($field, $formData)) {
          throw new \InvalidArgumentException("Missing required field: {$field}");
        }
        if (strlen($formData[$field]) < 1) {
          throw new \InvalidArgumentException("Invalid field: {$field}, must be a valid string.");
        }
      }

      $product->title = $formData['title'];
      $product->owner = $formData['owner'];
      $product->category = $formData['category'];
      $product->city = $formData['city'];
      $product->image = $formData['image'];
      $product->price = $formData['price'];
      $product->description = $formData['description'];
      $product->save();
      $results['data'] = $product;

    } catch(\Exception $e) {
      $results['error'] = $e->getMessage();
      $results['statusCode'] = 500;
      $results['class'] = get_class($e);
    }

    return response()->json($results, $results['statusCode']);
  }

  public function destroy($id)
  {
    $results = ['statusCode' => 200];
    $message = "";

    try {
      $product = Product::find($id);

      if (!is_a($product, 'App\Product')) {
        throw new \RuntimeException('Product with id: { $id } not found. Cannot be deleted.');
      }

      $product->delete();
      $message = "Product successfully deleted.";
    } catch(\Exception $e) {
      $results['error'] = $e->getMessage();
      $results['statusCode'] = 500;
    }

    return response()->json($message, $results['statusCode']);
  }
}