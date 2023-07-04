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
//            'image.required'=>'Вкажіть фото категорії',
            'description.required'=>'Вкажіть опис категорії'
        ];
        return Validator::make($input,[
            'name'=>'required',
//            'image'=>'required',
            'description'=>'required',
        ],$message);
    }

    // Code for image addition
    private function SaveImageToPath(Request $request): string | null {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Generate a unique filename
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $sizes = [50, 150, 300, 600, 1200];

            foreach ($sizes as $size)
            {
                $fileSave = $size.'_'.$filename; // picture's name
                // Resize the image while maintaining aspect ratio
                $resizedImage = Image::make($image)->resize($size, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode();
                // Save the resized image
                $path = public_path('uploads/' . $fileSave);
                file_put_contents($path, $resizedImage);
            }

            return $filename;
        }
        return null;
    }

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
        $input = $request->all();

        $validation = $this->ValidateCategory($input);
        if($validation->fails()){
            return $this->JsonResponse($validation->errors(), 400);
        }

        $input['image'] = $this->SaveImageToPath($request);

        $category = Category::create($input);
        return $this->JsonResponse($category, 201);
    }

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
        try {
            $category = Category::findOrFail($id);
            return $this->JsonResponse($category);
        }
        catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }

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
            $input = $request->all();

            $validation = $this->ValidateCategory($input);
            if($validation->fails()){
                return $this->JsonResponse($validation->errors(), 400);
            }

            if ($request->hasFile("image")) {
                $sizes = [50, 150, 300, 600, 1200];
                foreach ($sizes as $size) {
                    $fileDelete = $size.'_'.$category->image;
                    $removePath = public_path('uploads/' . $fileDelete);
                    if (file_exists($removePath)) {
                        unlink($removePath);
                    }
                }
            }

            $input['image'] = $this->SaveImageToPath($request) ?? $category['image'];

            $category->update($input);
            return $this->JsonResponse($category);
        }
        catch (ModelNotFoundException) {
            return $this->ModelNotFoundResponse();
        }
    }

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
