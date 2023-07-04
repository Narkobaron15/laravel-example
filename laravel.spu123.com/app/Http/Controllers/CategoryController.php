<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    // Code for model validation
    private function ValidateCategory(Array $input) {
        $message = [
            'name.required'=>'Вкажіть назву категорії',
            // 'image.required'=>'Вкажіть фото категорії',
            'description.required'=>'Вкажіть опис категорії'
        ];
        // validate using validator
        return Validator::make($input,[
            'name'=>'required',
            // 'image'=>'required',
            'description'=>'required',
        ],$message);
    }

    // Code for image addition
    private function SaveImageToPath(Request $request): string | null {
        if ($request->hasFile('image')) {
            // get image
            $image = $request->file('image');
            // Generate a unique filename
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();

            // for each of sizes, create resized image and generate common file name
            // {size}_{$filename}
            $sizes = [50, 150, 300, 600, 1200];
            foreach ($sizes as $size)
            {
                $fileSave = $size.'_'.$filename; // picture's name
                // Resize the image while maintaining aspect ratio
                $resizedImage = Image::make($image)->resize($size, null,
                    fn ($constraint) => $constraint->aspectRatio() // arrow function syntax
                )->encode();
                // Save the resized image to $path
                $path = public_path('uploads/' . $fileSave);
                file_put_contents($path, $resizedImage);
            }

            return $filename;
        }
        return null;
    }

    // Swagger commentary
    /**
     * @OA\Get(
     *     tags={"Category"},
     *     path="/api/categories",
     *     @OA\Response(response="200", description="List Categories.")
     * )
     */
    public function index(): JsonResponse
    {
        $list = Category::all();
        return $this->JsonResponse($list);
    }

    // Swagger commentary
    /**
     * @OA\Post(
     *     tags={"Category"},
     *     path="/api/categories/create",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={},
     *                 @OA\Property(
     *                     property="image",
     *                     type="file"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Added a new category")
     * )
     */
    public function create(Request $request): JsonResponse
    {
        // extracting all fields from multipart form
        $input = $request->all();
        // validating form data
        $validation = $this->ValidateCategory($input);
        if($validation->fails()){
            // sending 'error' response
            return $this->JsonResponse($validation->errors(), 400);
        }

        // extracting category's image
        $input['image'] = $this->SaveImageToPath($request);

        // updating and sending the new category
        $category = Category::create($input);
        return $this->JsonResponse($category, 201);
    }

    // Swagger commentary
    /**
     * @OA\Get(
     *     tags={"Category"},
     *     path="/api/categories/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Ідентифікатор категорії",
     *         required=true,
     *         @OA\Schema(
     *             type="number",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response="200", description="List Categories."),
     * @OA\Response(
     *    response=404,
     *    description="Wrong id",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong Category Id has been sent. Pls try another one.")
     *        )
     *     )
     * )
     */
    public function getById($id): JsonResponse
    {
        // searching for the category by id in corresponding table
        // if not found, sending 'error' response
        try {
            $category = Category::findOrFail($id);
            return $this->JsonResponse($category);
        }
        catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }

    // Swagger commentary
    /**
     * @OA\Post(
     *     tags={"Category"},
     *     path="/api/categories/edit/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Ідентифікатор категорії",
     *         required=true,
     *         @OA\Schema(
     *             type="number",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(
     *                     property="image",
     *                     type="file"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Edited Category")
     * )
     */
    public function put($id, Request $request): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);

            // extracting all fields from multipart form
            $input = $request->all();

            // validating form data
            $validation = $this->ValidateCategory($input);
            if($validation->fails()){
                // sending 'error' response
                return $this->JsonResponse($validation->errors(), 400);
            }

            // erasing the previous image's files provided a new image is given
            if ($request->hasFile("image")) {
                // for each of sizes,
                $sizes = [50, 150, 300, 600, 1200];
                foreach ($sizes as $size) {
                    // getting the path
                    $fileDelete = $size.'_'.$category->image;
                    $removePath = public_path('uploads/' . $fileDelete);
                    // and deleting from storage
                    if (file_exists($removePath)) {
                        unlink($removePath);
                    }
                }
            }

            // setting the new image if there is any
            $input['image'] = $this->SaveImageToPath($request) ?? $category['image'];

            // updating and sending the updated category
            $category->update($input);
            return $this->JsonResponse($category);
        }
        catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }

    // Swagger commentary
    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Ідентифікатор категорії",
     *         required=true,
     *         @OA\Schema(
     *             type="number",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успішне видалення категорії"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Категорії не знайдено"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизований"
     *     )
     * )
     */
    public function delete($id): JsonResponse
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return $this->JsonResponse("Ok");
        }
        catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }
}
