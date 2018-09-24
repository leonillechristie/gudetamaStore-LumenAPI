<?php
namespace App\Http\Controllers;
use App\Product;
use Illuminate\Http\Request;
class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index(Request $request)
    {
     	$query = $request->all();
     // $products = Product::all();
  //   $products = '[
  //   {
  //     "type": "products",
  //     "id": "meh-sticker",
  //     "attributes": {
  //       "title": "Gudetama Meh",
  //       "owner": "Sanrio Ltd.",
  //       "city": "Japan",
  //       "category": "Sticker",
  //       "price": 15,
  //       "image": "https://sdl-stickershop.line.naver.jp/products/0/0/1/5030/LINEStorePC/main.png?__=20161019",
  //       "description": "Nullam ac tortor vitae purus faucibus ornare suspendisse. Placerat duis ultricies lacus sed turpis tincidunt id aliquet. Velit euismod in pellentesque massa. Purus in massa tempor nec. Nec ullamcorper sit amet risus nullam eget. Cras ornare arcu dui vivamus arcu. Dui sapien eget mi proin sed libero."
  //     }
  //   },
  //   {
  //     "type": "products",
  //     "id": "gudetama-butt",
  //     "attributes": {
  //       "title": "Gudetama Butt",
  //       "owner": "Sanrio Ltd.",
  //       "city": "Singapore",

  //       "category": "Sticker",
  //       "price": 10,
  //       "image": "https://i1.wp.com/ilooklikethis.com/wp-content/uploads/2017/08/gudetama-9-e1503461031143.png?resize=304%2C333&ssl=1",
  //       "description": "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt"
  //     }
  //   },
  //   {
  //     "type": "products",
  //     "id": "gudetama-sleep",
  //     "attributes": {
  //       "title": "Sleepy Gudetama",
  //       "owner": "Sanrio Ltd.",
  //       "city": "Japan",
  //       "category": "Sticker",
  //       "price": 12,
  //       "image": "http://r.lnwfile.com/_/r/_raw/su/qc/ee.jpg",
  //       "description": "At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus."
  //     }
  //   },
  //   {
  //     "type": "products",
  //     "id": "sling-bag",
  //     "attributes": {
  //       "title": "Sling Bag",
  //       "owner": "Sanrio Ltd.",
  //       "city": "Japan",
  //       "category": "Bag",
  //       "price": 550,
  //       "image": "http://www.puffmonkey.com/thumbnail.asp?file=assets/images/gttb0006_front.jpg&maxx=400&maxy=0",
  //       "description": "Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat."
  //     }
  //   },
  //   {
  //     "type": "products",
  //     "id": "porcelein-mug",
  //     "attributes": {
  //       "title": "Porcelein Mug",
  //       "owner": "Sanrio Ltd.",
  //       "city": "Japan",
  //       "category": "Mug",
  //       "price": 250,
  //       "image": "https://i.ebayimg.com/images/g/Q7YAAOSwImRYd0Il/s-l300.jpg",
  //       "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
  //     }
  //   }
  // ]';

  	// $products = json_decode($products);

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

        try {
            $formData = $request->all();

            $requiredFields = ['title', 'owner', 'category', 'city', 'image', 'price', 'description'];

            $product->title = $request->input('title');
            $product->owner = $request->input('owner');
            $product->category = $request->input('category');
            $product->city = $request->input('city');
            $product->image = $request->input('image');
            $product->price = $request->input('price');
            $product->description = $request->input('description');

            foreach ($requiredFields as $field) {
              if (!array_key_exists($field, $formData)) {
                throw new \InvalidArgumentException("Missing required field: {$field}");
              }

              if (strlen($formData[$field]) < 1) {
                throw new \InvalidArgumentException("Invalid field: {$field}, must be a valid string.");   
              }
            }

            $product= Product::find($id);

            if (!is_a($product, 'App\Product')) {
                throw new \RuntimeException('Product does not exist.');
            }

            $product->save();
            $results['data'] = $product;
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