<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class ProductController extends Controller
{
    // Code for model validation
    protected function ValidateProduct(Array $input) {
        $rules = [
            'name.required'=>'Вкажіть назву продукту',
            'price.required'=>'Вкажіть ціну продукту',
            'description.required'=>'Вкажіть опис продукту',
            'price.min' => 'Ціна не може бути менше за 0',
            'price.number' => 'Ціна має бути числовим значенням',
            'name.max' => 'Поле занадто довге',
            'description.max' => 'Поле занадто довге',
            'category_id.required' => 'Вкажіть категорію',
            'category_id.integer' => 'Вкажіть коректний ID категорії',
            'category_id.min' => 'Вкажіть коректний ID категорії',
        ];

        return Validator::make($input, [
            'name'=>'required|string|max:200',
            'price'=>'required|numeric|min:0',
            'description'=>'required|string|max:4000',
            'images' => 'array',
            'category_id' => 'required|integer|min:0',
        ], $rules);
    }

    public function index(): JsonResponse
    {
        $list = Product::all();
        return $this->JsonResponse($list);
    }

    public function create(Request $request): JsonResponse
    {
        // extracting all fields from multipart form
        $input = $request->all();
        // validating form data
        $validation = $this->ValidateProduct($input);
        if($validation->fails()) {
            // sending 'error' response
            return $this->JsonResponse($validation->errors(), Response::HTTP_BAD_REQUEST);
        }

        // updating and sending the new product
        $product = Product::create($input);

        // extracting product's images and storing locally
        $imagePaths = $this->SaveImagesToPaths($request);
        // saving them to db
        $i = 0;
        foreach ($imagePaths as $p) {
            ProductImage::create([
                "name" => $p,
                "product_id" => $product["id"],
                "priority" => $i++,
            ]);
        }

        return $this->JsonResponse($product, Response::HTTP_CREATED);
    }

    public function getById($id): JsonResponse
    {
        // searching for the category by id in corresponding table
        // if not found, sending 'error' response
        try {
            $product = Product::findOrFail($id);
            return $this->JsonResponse($product);
        }
        catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }

    public function put($id, Request $request): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);

            // extracting all fields from multipart form
            $input = $request->all();

            // validating form data
            $validation = $this->ValidateProduct($input);
            if ($validation->fails()){
                // sending 'error' response
                return $this->JsonResponse($validation->errors(), 400);
            }

            // extracting product's images and storing locally if there is any
            $imagePaths = $this->SaveImagesToPaths($request);
            // checking if there is any
            if (count($imagePaths) > 0) {
                // deleting previous images
                foreach ($product->images as $img) {
                    $this->UnlinkImage($img->name);
                    $img->delete();
                }

                // saving them to db
                $i = 0;
                foreach ($imagePaths as $p) {
                    ProductImage::create([
                        "name" => $p,
                        "product_id" => $product["id"],
                        "priority" => $i,
                    ]);

                    $i += 1;
                }
            }

            // updating and sending the updated product
             $product->update($input);
             return $this->JsonResponse(Product::findOrFail($id)); // send updated data
        }
        catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $category = Product::findOrFail($id);
            $category->delete();
            return $this->JsonResponse("Ok");
        }
        catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }
}
