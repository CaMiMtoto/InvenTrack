<?php

namespace App\Constants;

class Permission
{
    // User & Role Management
    public const MANAGE_USERS = 'manage_users';
    public const MANAGE_ROLES = 'manage_roles';
    public const MANAGE_PERMISSIONS = 'manage_permissions';
    public const VIEW_PERMISSIONS = 'view_permissions';

    // Purchases
    public const VIEW_PURCHASES = 'view_purchases';
    public const ADD_PURCHASE = 'add_purchase';
    public const VIEW_PURCHASE_REPORTS = 'view_purchase_reports';

    // Orders
    public const NEW_ORDER = 'new_order';
    public const VIEW_ORDERS = 'view_orders';
    public const APPROVE_ORDERS = 'approve_orders';
    public const ASSIGN_DELIVERY = 'assign_delivery';
    public const COMPLETE_ORDERS = 'complete_orders';
    public const CANCEL_SALES_ORDERS = 'cancel_sales_orders';

    // Deliveries
    public const MANAGE_DELIVERIES = 'manage_deliveries';
    public const VIEW_DELIVERIES = 'view_deliveries';
    public const DELIVER_PRODUCTS = 'deliver_products';

    // Products & Categories
    public const MANAGE_PRODUCTS = 'manage_products';
    public const MANAGE_CATEGORIES = 'manage_categories';
    public const VIEW_PRODUCT_CATALOG = 'view_product_catalog';

    // Suppliers & Customers
    public const MANAGE_SUPPLIERS = 'manage_suppliers';
    public const MANAGE_CUSTOMERS = 'manage_customers';

    // Stock
    public const REQUEST_STOCK_ADJUSTMENT = 'request_stock_adjustment';
    public const APPROVE_STOCK_ADJUSTMENT = 'approve_stock_adjustment';
    public const VIEW_STOCK_ADJUSTMENT = 'view_stock_adjustment';
    public const VIEW_STOCK_MOVEMENT = 'view_stock_movement';
    public const VIEW_STOCK_REPORTS = 'view_stock_reports';

    // Payments & Expenses
    public const MANAGE_PAYMENT_METHODS = 'manage_payment_methods';

    public const VIEW_SALES_PAYMENTS = 'view_sales_payments';
    public const VIEW_SALES_PAYMENT_REPORTS = 'view_sales_payment_reports';
    public const MANAGE_EXPENSE_CATEGORIES = 'manage_expense_categories';
    public const MANAGE_EXPENSES = 'manage_expenses';
    public const VIEW_EXPENSES_REPORTS = 'view_expenses_reports';

    // Reports
    public const VIEW_REPORTS = 'view_reports';
    public const VIEW_SALES_REPORTS = 'view_sales_reports';
    public const VIEW_ITEMS_REPORTS = 'view_items_reports';
    const ADD_ORDER_PAYMENT = 'add_order_payment';
    const VIEW_RETURNED_ITEMS = 'view returned items';
    const APPROVE_RETURNED_ITEMS = 'approve returned items';
    const CREATE_REPORT = 'create report';
    const VIEW_SHAREHOLDERS = 'view shareholders';
    const MANAGE_SHAREHOLDERS = 'manage shareholders';

    const  VIEW_STORE_KEEPER_DASHBOARD = 'view_store_keeper_dashboard';
    const  VIEW_SALES_DASHBOARD = 'view_sales_dashboard';
    const  VIEW_DELIVERY_DASHBOARD = 'view_delivery_dashboard';
    const  VIEW_CUSTOMER_CARE_DASHBOARD = 'view_customer_care_dashboard';
    const  VIEW_FINANCIAL_DASHBOARD = 'view_financial_dashboard';
    const  APPROVE_SHARES = 'approve_shares';

    /**
     * Return all permissions
     */
    public static function all(): array
    {
        return [
            // User & Role Management
            self::MANAGE_USERS,
            self::MANAGE_ROLES,
            self::MANAGE_PERMISSIONS,
            self::VIEW_PERMISSIONS,

            // Purchases
            self::VIEW_PURCHASES,
            self::ADD_PURCHASE,
            self::VIEW_PURCHASE_REPORTS,

            // Orders
            self::NEW_ORDER,
            self::VIEW_ORDERS,
            self::APPROVE_ORDERS,
            self::ASSIGN_DELIVERY,
            self::COMPLETE_ORDERS,
            self::CANCEL_SALES_ORDERS,

            // Deliveries
            self::MANAGE_DELIVERIES,
            self::VIEW_DELIVERIES,
            self::DELIVER_PRODUCTS,

            // Products & Categories
            self::MANAGE_PRODUCTS,
            self::MANAGE_CATEGORIES,
            self::VIEW_PRODUCT_CATALOG,

            // Suppliers & Customers
            self::MANAGE_SUPPLIERS,
            self::MANAGE_CUSTOMERS,

            // Stock
            self::REQUEST_STOCK_ADJUSTMENT,
            self::APPROVE_STOCK_ADJUSTMENT,
            self::VIEW_STOCK_ADJUSTMENT,
            self::VIEW_STOCK_MOVEMENT,
            self::VIEW_STOCK_REPORTS,

            // Payments & Expenses
            // payments
            self::ADD_ORDER_PAYMENT,
            self::MANAGE_PAYMENT_METHODS,
            self::VIEW_SALES_PAYMENTS,
            self::VIEW_SALES_PAYMENT_REPORTS,
            self::MANAGE_EXPENSE_CATEGORIES,
            self::MANAGE_EXPENSES,
            self::VIEW_EXPENSES_REPORTS,

            // Reports
            self::VIEW_REPORTS,
            self::VIEW_SALES_REPORTS,
            self::VIEW_ITEMS_REPORTS,
            self::CREATE_REPORT,
            // returns
            self::VIEW_RETURNED_ITEMS,
            self::APPROVE_RETURNED_ITEMS,

            self::VIEW_SHAREHOLDERS,
            self::MANAGE_SHAREHOLDERS,
            // dashboards
            self::VIEW_STORE_KEEPER_DASHBOARD,
            self::VIEW_SALES_DASHBOARD,
            self::VIEW_DELIVERY_DASHBOARD,
            self::VIEW_CUSTOMER_CARE_DASHBOARD,
            self::VIEW_FINANCIAL_DASHBOARD,
            self::APPROVE_SHARES
        ];
    }

    public static function managePurchaseOrders(): array
    {
        return [
            self::VIEW_PURCHASES,
            self::ADD_PURCHASE,
        ];
    }

    public static function manageSalesOrders(): array
    {
        return [
            self::NEW_ORDER,
            self::APPROVE_ORDERS,

            self::COMPLETE_ORDERS,
            self::CANCEL_SALES_ORDERS,
        ];
    }

    public static function manageOrderDeliveries(): array
    {
        return [
            self::ASSIGN_DELIVERY,
            self::VIEW_DELIVERIES,
            self::MANAGE_DELIVERIES,
            self::DELIVER_PRODUCTS,
        ];
    }

    public static function ManageOrderReturns(): array
    {
        return [
            self::VIEW_RETURNED_ITEMS,
            self::APPROVE_RETURNED_ITEMS,
        ];
    }


    public static function manageProducts(): array
    {
        return [
            self::MANAGE_PRODUCTS,
            self::MANAGE_CATEGORIES,
            self::VIEW_PRODUCT_CATALOG,
        ];
    }

    public static function manageStock(): array
    {
        return [
            self::REQUEST_STOCK_ADJUSTMENT,
            self::APPROVE_STOCK_ADJUSTMENT,
            self::VIEW_STOCK_ADJUSTMENT,
            self::VIEW_STOCK_MOVEMENT,
        ];
    }

    public static function manageSettings(): array
    {
        return [
            self::MANAGE_SUPPLIERS,
            self::MANAGE_CUSTOMERS,
            self::MANAGE_PAYMENT_METHODS,
            self::MANAGE_EXPENSE_CATEGORIES,
        ];
    }

    public static function manageReports(): array
    {
        return [
            self::VIEW_REPORTS,
            self::VIEW_SALES_REPORTS,
            self::VIEW_PURCHASE_REPORTS,
            self::VIEW_STOCK_REPORTS,
            self::VIEW_ITEMS_REPORTS,
            self::VIEW_EXPENSES_REPORTS,
        ];
    }

    public static function ManageOrderPayments(): array
    {
        return [
            self::ADD_ORDER_PAYMENT,
            self::VIEW_SALES_PAYMENTS,
        ];
    }
}
