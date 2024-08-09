<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
  public function index()
  {
    return view('index');
  }
  //insert product function
  public function store(Request $request)
  {
    //setting the file name and the storage path
    $file = $request->file('product_image');
    $fileName = time() . '.' . $file->getClientOriginalExtension();
    $file->storeAs('public/images', $fileName);

    //inserting the product data into the database
    $productData = [
      'product_name' => $request->product_name,
      'description' => $request->description,
      'price' => $request->price,
      'id_catalog' => $request->id_catalog,
      'product_image' => $fileName
    ];

    Product::create($productData);
    return response()->json([
      'status' => 200,
      'message' => 'Product added successfully'
    ]);
  }

  //fetch all products
  public function fetchAll()
  {
    //sql query to fetch all products with category name
    $products = DB::table('products')
      ->join('catalog', 'products.id_catalog', '=', 'catalog.id')
      ->select('products.id', 'products.product_name', 'products.description', 'products.price',  'catalog.catalog_name', 'products.product_image')
      ->get();
    $output = '';
    if ($products->count() > 0) {
      $output .= '<table class="table table-striped table-sm text-center align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Imagen del producto</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Categoria</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>';
      foreach ($products as $product) {
        $output .= '<tr>
                <td>' . $product->id . '</td>
                <td><img src="storage/images/' . $product->product_image . '" width="50" class="img-thumbnail rounded-circle"></td>
                <td>' . $product->product_name . '</td>
                <td>' . $product->description . '</td>
                <td>' . $product->price . '</td>
                <td>' . $product->catalog_name . '</td>
                <td>
                  <a href="#" id="' . $product->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editProductModal"><i class="bi-pencil-square h4"></i></a>

                  <a href="#" id="' . $product->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                </td>
              </tr>';
      }
      $output .= '</tbody></table>';
      echo $output;
    } else {
      echo '<h1 class="text-center text-secondary my-5">No hay productos registrados</h1>';
    }
  }
  //find product by id
  public function edit(Request $request)
  {
    $id = $request->id;
    $product = Product::find($id);
    return response()->json($product);
  }
  //update product function
  public function update(Request $request)
  {
    $fileName = '';
    $product = Product::find($request->product_id);
    if ($request->hasFile('product_image')) {
      $file = $request->file('product_image');
      $fileName = time() . '.' . $file->getClientOriginalExtension();
      $file->storeAs('public/images', $fileName);
      if ($product->product_image) {
        Storage::delete('public/images/' . $product->product_image);
      }
    } else {
      $fileName = $request->product_image;
    }

    $productData = ['product_name' => $request->product_name, 'price' => $request->price, 'description' => $request->description, 'id_catalog' => $request->id_catalog, 'product_image' => $fileName];

    $product->update($productData);
    return response()->json([
      'status' => 200,
    ]);
  }

  //delete product
  public function delete(Request $request)
  {
    $id = $request->id;
    $product = Product::find($id);
    if (Storage::delete('public/images/' . $product->product_image)) {
      Product::destroy($id);
    }
  }
}
