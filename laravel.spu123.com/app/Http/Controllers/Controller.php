<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Response;
use Validator;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="SPU123 Shop",
 *      description="Demo my Project ",
 *      @OA\Contact(
 *          email="admin@gmail.com"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // Common functions

    protected function JsonResponse($data, int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_PRETTY_PRINT);
    }

    protected function ModelNotFoundResponse(): JsonResponse
    {
        return $this->JsonResponse(['message' => 'Not found', "status" => Response::HTTP_NOT_FOUND], Response::HTTP_NOT_FOUND);
    }

    // Add a single image
    protected function SaveImageToPath(Request $request): string|null
    {
        if ($request->hasFile('image')) {
            // get image
            $image = $request->file('image');
            // check if this is image (to be 100% sure)
            if (!$this->ValidateImg($image)->fails()) {
                return $this->SaveImgCore($image);
            }
        }
        return null;
    }

    /** Add multiple images
     * @returns string[]
     */
    protected function SaveImagesToPaths(Request $request): array
    {
        $paths = [];

        // getting all the files
        $images = $request->file('images');

        if ($images === null)
            return $paths;

        // saving each of them
        foreach ($images as $image) {
            if (!$this->ValidateImg($image)->fails()) {
                $path = $this->SaveImgCore($image);
                // array push syntax (weird)
                $paths[] = $path;
            }
        }

        return $paths;
    }

    private function ValidateImg(Mixed $image)
    {
        $rules = [
            'image.required' => 'Загрузіть фото',
            'image.mimes' => 'Виберіть картинку формату jpeg, jpg, png або gif',
            'image.max' => 'Картинка занадто великого розміру. Виберіть меншу за 50 МБ',
        ];

        return Validator::make(['image' => $image], $rules);
    }

    // Code for image addition
    private function SaveImgCore(Mixed $image): string
    {
        // Generate a unique filename
        $filename = uniqid() . '.' . $image->getClientOriginalExtension();

        // for each of sizes, create resized image and generate common file name
        // {size}_{$filename}
        $sizes = [50, 150, 300, 600, 1200];
        foreach ($sizes as $size) {
            $fileSave = $size . '_' . $filename; // picture's name
            // Resize the image while maintaining aspect ratio
            $resizedImage = Image::make($image)->resize($size, null,
                fn($constraint) => $constraint->aspectRatio() // arrow function syntax
            )->encode();
            // Save the resized image to $path
            $path = public_path('uploads/' . $fileSave);
            file_put_contents($path, $resizedImage);
        }

        return $filename;
    }

    // erasing the previous image's files provided a new image is given
    protected function UnlinkImage(string $imgName): void
    {
        // for each of sizes,
        $sizes = [50, 150, 300, 600, 1200];
        foreach ($sizes as $size) {
            // getting the path
            $fileDelete = $size . '_' . $imgName;
            $removePath = public_path('uploads/' . $fileDelete);
            // and deleting from storage
            if (file_exists($removePath)) {
                unlink($removePath);
            }
        }
    }
}
