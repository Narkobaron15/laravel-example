<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static findOrFail($id)
 * @method static create($any)
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
      "name", "image", "description",
    ];

    /**
     * **PROPERTIES** that should be publicly visible, including JSON
     * @var string[]
     */
    protected $appends = [
        "picture",
    ];

    protected $hidden = [
        "image", "created_at", "updated_at",
    ];

    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }

    // Definitions for properties that will be **appended**
    /**
     * @return array
     */
    public function getPictureAttribute(): array {
        return [
            'xs' => $this->uploadsUrl.'50_'.$this['image'],
            'sm' => $this->uploadsUrl.'150_'.$this['image'],
            'md' => $this->uploadsUrl.'300_'.$this['image'],
            'lg' => $this->uploadsUrl.'600_'.$this['image'],
            'xl' => $this->uploadsUrl.'1200_'.$this['image'],
            ];
    }

    protected string $uploadsUrl = 'http://laravel.spu123.com/uploads/';
}
