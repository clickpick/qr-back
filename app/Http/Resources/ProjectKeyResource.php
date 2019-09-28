<?php

namespace App\Http\Resources;

use App\ProjectKey;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProjectKeyResource
 * @package App\Http\Resources
 * @mixin ProjectKey
 */
class ProjectKeyResource extends JsonResource
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
            'value' => trim($this->value),
            'order' => $this->order
        ];
    }
}
