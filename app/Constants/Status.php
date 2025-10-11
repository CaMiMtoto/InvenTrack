<?php

namespace App\Constants;

class Status
{

    const Completed = 'completed';
    const Transit = 'transit';
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
    const Delivered = 'delivered';
    const PartiallyDelivered = 'partially delivered';
    const Reconciled = 'reconciled';
    const Returned = 'returned';

    public static function getStatuses(): array
    {
        return [
            self::Completed,
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
