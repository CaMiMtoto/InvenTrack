<?php

namespace App\Constants;

class Permission
{
    const MANAGE_USERS = 'MANAGE_USERS';
    const MANAGE_ROLES = 'MANAGE_ROLES';
    const MANAGE_PERMISSIONS = 'MANAGE_PERMISSIONS';
    const MANAGE_MERCHANTS = 'MANAGE_MERCHANTS';
    const VIEW_PURCHASES = 'VIEW_PURCHASES';
    const ADD_PURCHASE = 'ADD_PURCHASE';
    const ADD_SALES = 'ADD_SALES';
    public const  VIEW_PERMISSIONS = 'view_permissions';
    public const MANAGE_CATEGORIES = 'manage_categories';
    public const MANAGE_PRODUCTS = 'manage_products';
    public const MANAGE_SALES_DELIVERY = 'manage_sales_delivery';
    public const VIEW_SALES = 'view_sales';
    public const MANAGE_SUPPLIERS = 'manage_suppliers';
    public const MANAGE_STOCK = 'manage_stock';
    public const MANAGE_STOCK_ADJUSTMENT = 'manage_stock_adjustment';
    public const VIEW_STOCK_ADJUSTMENT = 'view_stock_adjustment';
    public const VIEW_STOCK_MOVEMENT = 'view_stock_movement';
    const MANAGE_CUSTOMERS = 'manage_customers';
    const MANAGE_PAYMENT_METHODS = 'manage_payment_methods';

    const VIEW_REPORTS = 'view_reports';
    const VIEW_SALES_REPORTS = 'view_sales_reports';
    const VIEW_STOCK_REPORTS = 'view_stock_reports';
    const VIEW_PURCHASE_REPORTS = 'view_purchase_reports';
    const ADD_SALE_PAYMENT = 'add_sale_payment';
    const VIEW_SALES_PAYMENTS = 'view_sales_payments';
    const VIEW_SALES_PAYMENT_REPORTS = 'view_sales_payment_reports';
    const VIEW_ITEMS_REPORTS = 'view_items_reports';
    const CANCEL_SALES_ORDERS = 'cancel_sales_orders';
    const MANAGE_EXPENSE_CATEGORIES = 'manage_expense_categories';
    const MANAGE_EXPENSES = 'manage_expenses';
    const VIEW_EXPENSES_REPORTS = 'view_expenses_reports';

    public static function all(): array
    {
        return [
            self::MANAGE_USERS,
            self::MANAGE_ROLES,
            self::MANAGE_PERMISSIONS,
            self::MANAGE_MERCHANTS
        ];
    }

    public static function managePurchaseOrders(): array
    {
        return [
            self::VIEW_PURCHASES,
            self::ADD_PURCHASE
        ];
    }

    public static function ManageSalesOrders()
    {
        return [
            self::ADD_SALES,
        ];
    }
}
