<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $appends = [
        "picture_xs", "picture_s","picture_m","picture_l","picture_xl",
    ];

    /**
     * @return string
     */
    public function getPictureXsAttribute(): string {
        return $this->uploadsUrl.'50_'.$this['image'];
    }
    /**
     * @return string
     */
    public function getPictureSAttribute(): string {
        return $this->uploadsUrl.'150_'.$this['image'];
    }
    /**
     * @return string
     */
    public function getPictureMAttribute(): string {
        return $this->uploadsUrl.'300_'.$this['image'];
    }
    /**
     * @return string
     */
    public function getPictureLAttribute(): string {
        return $this->uploadsUrl.'600_'.$this['image'];
    }
    /**
     * @return string
     */
    public function getPictureXlAttribute(): string {
        return $this->uploadsUrl.'1200_'.$this['image'];
    }

    protected $uploadsUrl = 'http://laravel.spu123.com/uploads/';
}
