<?php

namespace App\Services;

use App\Constants\Status;
use App\Models\FlowHistory;

class FlowHistoryService
{
    public function saveFlow(string $type, int $id, $comment, string $status = null, bool $isComment = false, $user_id = null): void
    {
        FlowHistory::query()
            ->create([
                'reference_type' => $type,
                'reference_id' => $id,
                'comment' => $comment,
                'user_id' => $user_id ?? auth()->id(),
                'status' => $status ?? Status::Pending,
                'is_comment' => $isComment,
            ]);
    }
}
