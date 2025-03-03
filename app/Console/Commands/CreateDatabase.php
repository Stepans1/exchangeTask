<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create DB';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = Config::get('database.connections.mysql.database');
        $username = Config::get('database.connections.mysql.username');
        $password = Config::get('database.connections.mysql.password');
        $host = Config::get('database.connections.mysql.host');
        $port = Config::get('database.connections.mysql.port');

        try {
            $pdo = new \PDO("mysql:host=$host;port=$port", $username, $password);

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        } catch (\PDOException $e) {
            $this->error($e->getMessage());
        }
    }
}
