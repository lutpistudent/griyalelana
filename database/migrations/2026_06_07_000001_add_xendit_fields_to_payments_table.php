<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;

    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('alter table payments add column if not exists xendit_external_id varchar(100) null');
            DB::statement('alter table payments add column if not exists xendit_invoice_id varchar(100) null');
            DB::statement('alter table payments add column if not exists xendit_invoice_url varchar(500) null');
            DB::statement('alter table payments add column if not exists xendit_payment_id varchar(100) null');
            DB::statement('alter table payments add column if not exists xendit_status varchar(50) null');
            DB::statement('alter table payments add column if not exists xendit_payload text null');
            DB::statement('create index if not exists payments_xendit_external_id_index on payments (xendit_external_id)');
            DB::statement('create index if not exists payments_xendit_invoice_id_index on payments (xendit_invoice_id)');

            return;
        }

        $existingColumns = Schema::getColumnListing('payments');

        Schema::table('payments', function (Blueprint $table) use ($existingColumns) {
            if (! in_array('xendit_invoice_id', $existingColumns, true)) {
                $table->string('xendit_invoice_id', 100)->nullable();
            }

            if (! in_array('xendit_external_id', $existingColumns, true)) {
                $table->string('xendit_external_id', 100)->nullable();
            }

            if (! in_array('xendit_invoice_url', $existingColumns, true)) {
                $table->string('xendit_invoice_url', 500)->nullable();
            }

            if (! in_array('xendit_payment_id', $existingColumns, true)) {
                $table->string('xendit_payment_id', 100)->nullable();
            }

            if (! in_array('xendit_status', $existingColumns, true)) {
                $table->string('xendit_status', 50)->nullable();
            }

            if (! in_array('xendit_payload', $existingColumns, true)) {
                $table->text('xendit_payload')->nullable();
            }
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('create index if not exists payments_xendit_external_id_index on payments (xendit_external_id)');
            DB::statement('create index if not exists payments_xendit_invoice_id_index on payments (xendit_invoice_id)');
        } else {
            Schema::table('payments', function (Blueprint $table) {
                $table->index('xendit_external_id');
                $table->index('xendit_invoice_id');
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('drop index if exists payments_xendit_external_id_index');
            DB::statement('drop index if exists payments_xendit_invoice_id_index');
            DB::statement('alter table payments drop column if exists xendit_external_id');
            DB::statement('alter table payments drop column if exists xendit_invoice_id');
            DB::statement('alter table payments drop column if exists xendit_invoice_url');
            DB::statement('alter table payments drop column if exists xendit_payment_id');
            DB::statement('alter table payments drop column if exists xendit_status');
            DB::statement('alter table payments drop column if exists xendit_payload');

            return;
        }

        $existingColumns = Schema::getColumnListing('payments');

        Schema::table('payments', function (Blueprint $table) use ($existingColumns) {
            $columns = array_filter([
                in_array('xendit_external_id', $existingColumns, true) ? 'xendit_external_id' : null,
                in_array('xendit_invoice_id', $existingColumns, true) ? 'xendit_invoice_id' : null,
                in_array('xendit_invoice_url', $existingColumns, true) ? 'xendit_invoice_url' : null,
                in_array('xendit_payment_id', $existingColumns, true) ? 'xendit_payment_id' : null,
                in_array('xendit_status', $existingColumns, true) ? 'xendit_status' : null,
                in_array('xendit_payload', $existingColumns, true) ? 'xendit_payload' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
