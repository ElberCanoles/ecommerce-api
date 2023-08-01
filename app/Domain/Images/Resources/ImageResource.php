<?php

declare(strict_types=1);

namespace App\Domain\Images\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Images\Models\Image;

/**
 * @mixin Image
 */
class ImageResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'path' => $this->path
        ];
    }
}
