<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'created_at' => Carbon::parse($this->created_at)->setTimezone('America/Sao_Paulo')->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->setTimezone('America/Sao_Paulo')->format('Y-m-d H:i:s'),
        ];
    }
}
