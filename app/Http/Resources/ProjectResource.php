<?php

namespace App\Http\Resources;

use App\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProjectResource
 * @package App\Http\Resources
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'raised_funds' => $this->raised_funds,
            'goal_funds' => $this->goal_funds,
            'prize' => $this->prize,
            'winners_count' => $this->winners_count,
            'is_finished' => $this->is_finished,
            'poster_url' => $this->getFirstMedia('poster')->getFullUrl('card'),
            'banner_url' => $this->getFirstMedia('banner')->getFullUrl('card'),
        ];
    }
}
