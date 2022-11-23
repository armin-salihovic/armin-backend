<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'title' => $this->title,
            'image' => $this->image('image'),
            'image_lqpi' => $this->lowQualityImagePlaceholder('image'),
            'image_mobile' => $this->image('image', 'mobile'),
            'image_mobile_lqpi' => $this->lowQualityImagePlaceholder('image', 'mobile'),
            'description' => $this->description,
            'content' => $this->renderBlocks()
        ];
    }
}
