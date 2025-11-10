<?php

namespace App\Constants;

class UserType
{
    const CUSTOMER_CARE = 'Customer Care';
    const DELIVERY_PERSON = 'Delivery Person';
    const FINANCE_USER = 'Finance User';
    const SALES_MANAGER = 'Sales Manager';
    const STORE_KEEPER = 'Store Keeper';

    public static function all(): array
    {
        return [
            self::CUSTOMER_CARE,
            self::DELIVERY_PERSON,
            self::FINANCE_USER,
            self::SALES_MANAGER,
            self::STORE_KEEPER
        ];
    }
}
