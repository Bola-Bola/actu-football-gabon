<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabase extends Command
{
    protected $signature = 'db:test';
    protected $description = 'Test la connexion à la base de données';

    public function handle()
    {
        $this->info('Test de connexion à la base de données...');

        try {
            DB::connection()->getPdo();
            $this->info('✅ Connexion réussie!');

            $dbName = DB::connection()->getDatabaseName();
            $driver = DB::connection()->getDriverName();
            $this->info("Base de données: {$dbName}");
            $this->info("Driver: {$driver}");

            // Lister les tables (compatible PostgreSQL et MySQL)
            if ($driver === 'pgsql') {
                $tables = DB::select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
                $this->info('Tables existantes: ' . count($tables));
                foreach ($tables as $table) {
                    $this->line("  - {$table->tablename}");
                }
            } else {
                $tables = DB::select('SHOW TABLES');
                $this->info('Tables existantes: ' . count($tables));
                foreach ($tables as $table) {
                    $tableName = array_values((array)$table)[0];
                    $this->line("  - {$tableName}");
                }
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Erreur de connexion: ' . $e->getMessage());
            return 1;
        }
    }
}
