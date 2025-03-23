<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceLog;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Create some sample customers
        $customer1 = Customer::create([
            'name'  => 'Acme Corporation',
            'email' => 'contact@acmecorp.com',
        ]);

        $customer2 = Customer::create([
            'name'  => 'Global Industries',
            'email' => 'info@globalindustries.com',
        ]);

        // Create sample users
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $employee = User::create([
            'name'     => 'Employee User',
            'email'    => 'employee@example.com',
            'password' => Hash::make('password'),
            'role'     => 'employee',
        ]);

        // Create some sample invoices
        $invoice1 = Invoice::create([
            'invoice_number' => 'INV-001',
            'customer_id'    => $customer1->id,
            'amount'         => 150.00,
            'tax'            => 15.00,
            'status'         => 'Pending',
            'due_date'       => Carbon::now()->addDays(30)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for consulting services.',
        ]);

        $invoice2 = Invoice::create([
            'invoice_number' => 'INV-002',
            'customer_id'    => $customer2->id,
            'amount'         => 250.00,
            'tax'            => 25.00,
            'status'         => 'Paid',
            'due_date'       => Carbon::now()->addDays(15)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for product delivery.',
        ]);

        $invoice3 = Invoice::create([
            'invoice_number' => 'INV-003',
            'customer_id'    => $customer1->id,
            'amount'         => 150.00,
            'tax'            => 15.00,
            'status'         => 'Pending',
            'due_date'       => Carbon::now()->addDays(30)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for consulting services.',
        ]);

        $invoice4 = Invoice::create([
            'invoice_number' => 'INV-004',
            'customer_id'    => $customer2->id,
            'amount'         => 250.00,
            'tax'            => 25.00,
            'status'         => 'Paid',
            'due_date'       => Carbon::now()->addDays(15)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for product delivery.',
        ]);

        $invoice5 = Invoice::create([
            'invoice_number' => 'INV-005',
            'customer_id'    => $customer1->id,
            'amount'         => 150.00,
            'tax'            => 15.00,
            'status'         => 'Pending',
            'due_date'       => Carbon::now()->addDays(30)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for consulting services.',
        ]);

        $invoice6 = Invoice::create([
            'invoice_number' => 'INV-006',
            'customer_id'    => $customer2->id,
            'amount'         => 250.00,
            'tax'            => 25.00,
            'status'         => 'Paid',
            'due_date'       => Carbon::now()->addDays(15)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for product delivery.',
        ]);

        $invoice7 = Invoice::create([
            'invoice_number' => 'INV-007',
            'customer_id'    => $customer1->id,
            'amount'         => 150.00,
            'tax'            => 15.00,
            'status'         => 'Pending',
            'due_date'       => Carbon::now()->addDays(30)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for consulting services.',
        ]);

        $invoice8 = Invoice::create([
            'invoice_number' => 'INV-008',
            'customer_id'    => $customer2->id,
            'amount'         => 250.00,
            'tax'            => 25.00,
            'status'         => 'Paid',
            'due_date'       => Carbon::now()->addDays(15)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for product delivery.',
        ]);

        $invoice9 = Invoice::create([
            'invoice_number' => 'INV-009',
            'customer_id'    => $customer1->id,
            'amount'         => 150.00,
            'tax'            => 15.00,
            'status'         => 'Pending',
            'due_date'       => Carbon::now()->addDays(30)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for consulting services.',
        ]);

        $invoice10 = Invoice::create([
            'invoice_number' => 'INV-010',
            'customer_id'    => $customer2->id,
            'amount'         => 250.00,
            'tax'            => 25.00,
            'status'         => 'Paid',
            'due_date'       => Carbon::now()->addDays(15)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for product delivery.',
        ]);

        $invoice11 = Invoice::create([
            'invoice_number' => 'INV-011',
            'customer_id'    => $customer1->id,
            'amount'         => 150.00,
            'tax'            => 15.00,
            'status'         => 'Pending',
            'due_date'       => Carbon::now()->addDays(30)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for consulting services.',
        ]);

        $invoice12 = Invoice::create([
            'invoice_number' => 'INV-012',
            'customer_id'    => $customer2->id,
            'amount'         => 250.00,
            'tax'            => 25.00,
            'status'         => 'Paid',
            'due_date'       => Carbon::now()->addDays(15)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for product delivery.',
        ]);

        $invoice13 = Invoice::create([
            'invoice_number' => 'INV-013',
            'customer_id'    => $customer1->id,
            'amount'         => 150.00,
            'tax'            => 15.00,
            'status'         => 'Pending',
            'due_date'       => Carbon::now()->addDays(30)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for consulting services.',
        ]);

        $invoice14 = Invoice::create([
            'invoice_number' => 'INV-014',
            'customer_id'    => $customer2->id,
            'amount'         => 250.00,
            'tax'            => 25.00,
            'status'         => 'Paid',
            'due_date'       => Carbon::now()->addDays(15)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for product delivery.',
        ]);

        $invoice15 = Invoice::create([
            'invoice_number' => 'INV-015',
            'customer_id'    => $customer1->id,
            'amount'         => 150.00,
            'tax'            => 15.00,
            'status'         => 'Pending',
            'due_date'       => Carbon::now()->addDays(30)->toDateString(),
            'currency'       => 'USD',
            'description'    => 'Invoice for consulting services.',
        ]);

        // Create invoice logs for the invoices (as an audit trail)
        InvoiceLog::create([
            'invoice_id' => $invoice1->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice2->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice3->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice4->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice5->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice6->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice7->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice8->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice9->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice10->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice11->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice12->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice13->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice13->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice14->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice15->id,
            'user_id'    => $admin->id,
            'action'     => 'create',
            'role'       => 'admin',
            'details'    => 'Invoice created via seeder.',
        ]);
    }
}
