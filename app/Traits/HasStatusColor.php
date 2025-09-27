<?php

namespace App\Traits;

use App\Constants\Status;

trait HasStatusColor
{
    public function getStatusColorFromTrait(): string
    {
        $status = strtolower($this->status);

        return match ($status) {
            strtolower(Status::Pending) => 'warning',

            strtolower(Status::Submitted),
            strtolower(Status::InProgress),
            strtolower(Status::Confirmed) => 'info',

            strtolower(Status::Completed),
            strtolower(Status::Active),
            strtolower(Status::Successful),
            strtolower(Status::Approved) => 'success',

            strtolower(Status::Rejected),
            strtolower(Status::Cancelled),
            strtolower(Status::Failed),
            strtolower(Status::Inactive) => 'danger',

            default => 'secondary',
        };
    }

    // Keep the original method (optional)
    public function getStatusColorAttribute(): string
    {
        return $this->getStatusColorFromTrait();
    }
}
