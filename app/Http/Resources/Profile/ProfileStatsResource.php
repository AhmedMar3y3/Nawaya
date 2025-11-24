<?php
namespace App\Http\Resources\Profile;

use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileStatsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $earnedBadgeIds  = $this->badges()->pluck('badges.id')->toArray();
        $nextBadge       = Badge::ordered()->whereNotIn('id', $earnedBadgeIds)->first();
        $lastThreeBadges = $this->earnedBadges()->limit(3)->get()->map(function ($userBadge) {
            return [
                'id'        => $userBadge->badge->id,
                'name'      => $userBadge->badge->name,
                'icon'      => $userBadge->badge->icon,
            ];
        });

        return [
            'user_data'         => [
                'id'            => $this->id,
                'name'          => $this->name,
                'image'         => $this->image ?? env('APP_URL') . '/defaults/profile.webp',
                'current_level' => $this->current_level ?? $this->start_level,
            ],

            'xp_data'           => [
                'user_xp'       => $this->xp,
                'next_badge_xp' => $nextBadge ? $nextBadge->required_xp : null,
            ],

            'stats'             => [
                'longest_streak' => $this->longest_streak,
                'highest_position' => $this->highest_position,
            ],

            'last_three_badges' => $lastThreeBadges,
        ];
    }
}
