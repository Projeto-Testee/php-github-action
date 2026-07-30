<?php

declare(strict_types=1);

namespace App\Models\DTO;

final class TodoData
{
    public string $title;
    public string $description;
    public ?string $status;

    public function set($data): TodoData
    {
        $this->title       = $data['title'];
        $this->description = $data['description'];

        if (!isset($data['status'])) {
            $this->status = config('todo_settings.default_status', '') ?? null;
        } else {
            $this->status = $data['status'];
        }

        return $this;
    }
}
