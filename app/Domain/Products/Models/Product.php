<?php

namespace App\Domain\Products\Models;

use App\Domain\Images\Models\Image;
use App\Domain\Products\QueryBuilders\ProductQueryBuilder;
use Carbon\Carbon;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property float $price
 * @property int $stock
 * @property string $status
 * @property-read Collection|Builder|Image[]|null $images
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static ProductQueryBuilder query()
 */
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'status'
    ];

    protected static function newFactory(): Factory
    {
        return ProductFactory::new();
    }

    public function newEloquentBuilder($query): ProductQueryBuilder
    {
        return new ProductQueryBuilder($query);
    }

    public function images(): MorphMany|Builder
    {
        return $this->morphMany(related: Image::class, name: 'imageable');
    }
}
