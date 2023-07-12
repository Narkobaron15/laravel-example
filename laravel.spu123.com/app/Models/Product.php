<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static findOrFail($id)
 * @method static create(array $input)
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "category_id", "name", "price", "description",
    ];

    protected $appends = [
        "category_name", "primary_image",
    ];

    protected $hidden = [
        "category", "created_at", "updated_at",
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getCategoryNameAttribute(): string
    {
        try {
            return Category::findOrFail($this["category_id"])->name;
        } catch (ModelNotFoundException) {
            return "";
        }
    }

    public function getPrimaryImageAttribute(): mixed
    {
        $images = $this["images"];
        $arr_length = count($images);

        if ($arr_length == 0) {
            return null;
        }
        // else
        $highest_priority = -1;
        $topImg = null;
        foreach ($images as $image) {
            if ($image["priority"] > $highest_priority) {
                $highest_priority = $image["priority"];
                $topImg = $image;
            }
        }
        return $topImg;
    }
}
