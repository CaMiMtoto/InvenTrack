<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()->count(13)->create();
        Product::factory()->count(80)->create();
        Customer::factory()->count(80)->create();
        Supplier::factory()->count(80)->create();
        Purchase::factory()->count(180)->create();
        PurchaseItem::factory()->count(1800)->create();
        Order::factory()->count(180)->create();
        OrderItem::factory()->count(1800)->create();
    }
}
