<?php

namespace App\Http\Resources\Admin;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProjectResource
 * @package App\Http\Resources\Admin
 * @mixin Project
 */
class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'poster_url' => $this->getFirstMedia('poster') ? $this->getFirstMedia('poster')->getFullUrl('card') : null,
            'banner_url' => $this->getFirstMedia('poster') ? $this->getFirstMedia('banner')->getFullUrl('card') : null,
        ]);
    }
}
