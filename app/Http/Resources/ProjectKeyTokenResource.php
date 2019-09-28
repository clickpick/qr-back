<?php

namespace App\Http\Resources;

use App\ProjectKey;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProjectKeyTokenResource
 * @package App\Http\Resources
 * @mixin ProjectKey
 */
class ProjectKeyTokenResource extends JsonResource
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
            'token' => $this->pivot->token
        ];
    }
}
