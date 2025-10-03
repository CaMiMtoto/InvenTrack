<?php

namespace App\Constants;

class Permission
{
    const MANAGE_USERS = 'MANAGE_USERS';
    const MANAGE_ROLES = 'MANAGE_ROLES';
    const MANAGE_PERMISSIONS = 'MANAGE_PERMISSIONS';
    const VIEW_PURCHASES = 'VIEW_PURCHASES';
    const ADD_PURCHASE = 'ADD_PURCHASE';
    const NEW_ORDER = 'NEW_ORDER';
    const VIEW_ORDERS = 'VIEW_ORDERS';
    const APPROVE_ORDERS = 'APPROVE_ORDERS';
    const ASSIGN_DELIVERY = 'ASSIGN_DELIVERY';
    const DELIVERY_PRODUCTS = 'DELIVERY_PRODUCTS';
    public const MANAGE_DELIVERIES = 'MANAGE_DELIVERIES';

    const VIEW_DELIVERIES = 'VIEW_DELIVERIES';
    public const  VIEW_PERMISSIONS = 'view_permissions';
    public const MANAGE_CATEGORIES = 'manage_categories';
    public const MANAGE_PRODUCTS = 'manage_products';
    const VIEW_PRODUCT_CATALOG = 'VIEW_PRODUCT_CATALOG';
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
            self::VIEW_PURCHASES,
            self::ADD_PURCHASE,
            self::NEW_ORDER,
            self::VIEW_ORDERS,
            self::APPROVE_ORDERS,
            self::VIEW_PERMISSIONS,
            self::MANAGE_CATEGORIES,
            self::MANAGE_PRODUCTS,
            self::DELIVERY_PRODUCTS,
            self::VIEW_DELIVERIES,
            self::MANAGE_SUPPLIERS,
            self::MANAGE_STOCK,
            self::MANAGE_CUSTOMERS,
            self::VIEW_PRODUCT_CATALOG
        ];
    }

    public static function managePurchaseOrders(): array
    {
        return [
            self::VIEW_PURCHASES,
            self::ADD_PURCHASE
        ];
    }

    public static function ManageSalesOrders(): array
    {
        return [
            self::NEW_ORDER,
        ];
    }

    public static function ManageOrderDeliveries(): array
    {
        return [
            self::VIEW_DELIVERIES,
            self::MANAGE_DELIVERIES,
        ];
    }

    public static function ManageProducts(): array
    {
        return [
            self::MANAGE_PRODUCTS,
            self::MANAGE_CATEGORIES
        ];
    }

    public static function ManageStock(): array
    {
        return [
            self::MANAGE_STOCK,
            self::MANAGE_STOCK_ADJUSTMENT,
            self::VIEW_STOCK_ADJUSTMENT,
            self::VIEW_STOCK_MOVEMENT
        ];
    }

    public static function ManageSettings(): array
    {
        return [
            self::MANAGE_SUPPLIERS,
            self::MANAGE_CUSTOMERS,
            self::MANAGE_PAYMENT_METHODS,
            self::MANAGE_EXPENSE_CATEGORIES
        ];
    }
}
