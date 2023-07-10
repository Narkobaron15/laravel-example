<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, mixed $id)
 * @method static create(array $array)
 */
class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        "product_id", "name", "priority",
    ];

    protected $hidden = [
        "name", "created_at", "updated_at", "id", "product_id",
    ];

    /**
     * **PROPERTIES** that should be publicly visible, including JSON
     * @var string[]
     */
    protected $appends = [
        "xs", "sm", "md", "lg", "xl",
    ];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    // Definitions for properties that will be **appended**
    /**
     * @return string
     */
    public function getXsAttribute(): string {
        return $this->uploadsUrl.'50_'.$this['name'];
    }
    /**
     * @return string
     */
    public function getSmAttribute(): string {
        return $this->uploadsUrl.'150_'.$this['name'];
    }
    /**
     * @return string
     */
    public function getMdAttribute(): string {
        return $this->uploadsUrl.'300_'.$this['name'];
    }
    /**
     * @return string
     */
    public function getLgAttribute(): string {
        return $this->uploadsUrl.'600_'.$this['name'];
    }
    /**
     * @return string
     */
    public function getXlAttribute(): string {
        return $this->uploadsUrl.'1200_'.$this['name'];
    }

    protected string $uploadsUrl = 'http://laravel.spu123.com/uploads/';
}
