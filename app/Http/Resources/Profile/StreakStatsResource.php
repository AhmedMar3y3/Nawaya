<?php
namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StreakStatsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Handle array data - when resource is created from array, $this->resource is the array
        if (is_array($this->resource)) {
            $data = $this->resource;
        } elseif (is_object($this->resource)) {
            // If it's an object, try to get array representation
            if (method_exists($this->resource, 'toArray')) {
                $data = $this->resource->toArray();
            } else {
                $data = (array) $this->resource;
            }
        } else {
            // Fallback for primitive types - shouldn't happen but handle gracefully
            $data = [];
        }
        
        return [
            'current_streak'         => (int) ($data['current_streak'] ?? 0),
            'longest_streak'         => (int) ($data['longest_streak'] ?? 0),
            'badges'                 => (int) ($data['badges'] ?? 0),
            'can_use_streak_restore' => (bool) ($data['can_restore'] ?? false),
            'restores_remaining'     => (int) ($data['restores_remaining'] ?? 0),
        ];
    }
}