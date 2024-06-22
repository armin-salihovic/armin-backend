<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResourceBasic extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'slug' => $this->slug,
            'technologies' => $this->technologies->pluck('title'),
            'title' => $this->title,
            'image' => $this->image('image', 'default', ['w' => 910]),
            'image_mobile' => $this->image('image', 'mobile'),
            'image_lqpi' => $this->lowQualityImagePlaceholder('image'),
            'image_lqpi_mobile' => $this->lowQualityImagePlaceholder('image', 'mobile'),
            'aspect_ratio' => $this->getAspectRatioFormatted('image'),
        ];
    }
}
