<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Cash',
                'code' => 'CASH',
                'description' => 'Payment made with physical currency.',
            ],
            [
                'name' => 'Mobile Money',
                'code' => 'MM',
                'description' => 'Payment via mobile wallet services (e.g., MTN, Airtel).',
            ],
            [
                'name' => 'Bank Transfer',
                'code' => 'BT',
                'description' => 'Direct transfer from a bank account.',
            ],
            [
                'name' => 'Credit/Debit Card',
                'code' => 'CARD',
                'description' => 'Payment made with a credit or debit card.',
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(['code' => $method['code']], $method);
        }
    }
}