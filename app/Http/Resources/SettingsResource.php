<?php

namespace App\Http\Resources;

use App\Models\Cv;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingsResource extends JsonResource
{
    public static $wrap = false;

    public function createTitle($title): string
    {
        return $title ? $title . ' | ' . $this->global_title : $this->global_title;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'favicon' => $this->image('favicon'),
            'social_github' => $this->social_github,
            'social_linkedin' => $this->social_linkedin,
            'social_discord' => $this->social_discord,
            'social_email' => $this->social_email,
            'home_meta_title' => $this->home_meta_title,
            'home_meta_description' => $this->home_meta_description,
            'home_og' => $this->socialImage('home_og'),
            'projects_meta_title' => $this->createTitle($this->projects_meta_title),
            'projects_meta_description' => $this->projects_meta_description,
            'projects_og' => $this->socialImage('projects_og'),
            'about_meta_title' => $this->createTitle($this->about_meta_title),
            'about_meta_description' => $this->about_meta_description,
            'about_og' => $this->socialImage('about_og'),
            'contact_meta_title' => $this->createTitle($this->contact_meta_title),
            'contact_meta_description' => $this->contact_meta_description,
            'contact_og' => $this->socialImage('contact_og'),
            'cv' => Cv::getCv(),
        ];
    }
}
