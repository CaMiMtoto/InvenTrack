<?php

namespace App\Constants;

class Status
{

    const Completed = 'completed';
    const InProgress = 'in_progress';
    const Confirmed = 'confirmed';
    const Submitted = 'submitted';
    const Pending = 'pending';
    const Approved = 'approved';
    const Rejected = 'rejected';
    const Cancelled = 'cancelled';
    const Inactive = 'inactive';
    const Active = 'active';
    const Successful = 'successful';
    const Failed = 'failed';

    public static function getStatuses(): array
    {
        return [
            self::Completed,
            self::InProgress,
            self::Confirmed,
            self::Submitted,
            self::Pending,
            self::Approved,
            self::Rejected,
            self::Cancelled,
            self::Inactive,
        ];
    }
}
