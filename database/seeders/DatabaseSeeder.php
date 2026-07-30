<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with the baseline admin, a demo
     * customer, and default site settings.
     */
    public function run(): void
    {
        // Admin account for the panel.
        User::updateOrCreate(
            ['email' => 'admin@rathnacollections.test'],
            [
                'name' => 'Store Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Demo customer account.
        User::updateOrCreate(
            ['email' => 'customer@rathnacollections.test'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        // Baseline site settings.
        $defaults = [
            'site_name' => 'Rathna Collections',
            'contact_email' => 'support@rathnacollections.test',
            'contact_phone' => '+91 00000 00000',
            'currency' => 'INR',
            'currency_symbol' => '₹',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value, 'group' => 'general']);
        }

        // Demo catalog (categories, brands, products, coupon, pages, banner).
        $this->call(DemoCatalogSeeder::class);
    }
}
