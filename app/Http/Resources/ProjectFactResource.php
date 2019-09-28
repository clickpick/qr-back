<?php

namespace App\Http\Resources;

use App\ProjectFact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProjectFactResource
 * @package App\Http\Resources
 * @mixin ProjectFact
 */
class ProjectFactResource extends JsonResource
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
            'text' => $this->text
        ];
    }
}
