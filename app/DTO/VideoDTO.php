<?php

namespace App\DTO;

class VideoDTO
{
    public int $user;
    public int|null $group;

    public function __construct($model)
    {
        $this->user = $this->getUserAndGroup($model)->user;
        $this->group = $this->getUserAndGroup($model)->group ?? null;
    }

    private function getUserAndGroup($model)
    {
        if (str_starts_with($model, 'group')) {
            $ids = str_replace('group', '', $model);
            list($groupId, $userId) = explode('-', $ids);
            return (object) [
                'user' => $userId,
                'group' => $groupId,
            ];
        } else if (str_starts_with($model, 'user')) {
            $userId = str_replace('user', '', $model);

            return (object) [
                'user' => $userId,
            ];
        }
    }
}
