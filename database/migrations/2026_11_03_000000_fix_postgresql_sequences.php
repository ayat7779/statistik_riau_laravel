<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $tablesWithIntegerId = DB::select("
            SELECT c.table_name, c.column_default
            FROM information_schema.columns c
            JOIN information_schema.tables t ON c.table_name = t.table_name
            WHERE t.table_schema = 'public'
              AND t.table_type = 'BASE TABLE'
              AND c.column_name = 'id'
              AND c.data_type IN ('integer', 'bigint', 'smallint')
            ORDER BY c.table_name
        ");

        foreach ($tablesWithIntegerId as $t) {
            $tableName = $t->table_name;
            $currDefault = $t->column_default;

            if ($currDefault === null || strpos($currDefault, 'nextval') === false) {
                $seqName = "{$tableName}_id_seq";
                DB::statement("CREATE SEQUENCE IF NOT EXISTS \"{$seqName}\"");
                DB::statement("ALTER TABLE \"{$tableName}\" ALTER COLUMN id SET DEFAULT nextval('\"{$seqName}\"'::regclass)");
                DB::statement("ALTER SEQUENCE \"{$seqName}\" OWNED BY \"{$tableName}\".id");

                $maxId = (int) (DB::table($tableName)->max('id') ?? 0);
                $nextId = $maxId + 1;
                DB::statement("SELECT setval('\"{$seqName}\"', {$nextId}, false)");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert auto-increment sequence in down
    }
};
