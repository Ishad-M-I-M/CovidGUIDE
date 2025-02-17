<?php


namespace app\core\db;


use app\core\App;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'];
        $user = $config['user'];
        $password = $config['password'];
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations(string $dbname)
    {
        $this->createDatabase($dbname);
        $this->createMigrationTable();
        $appliedMigrations =  $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(App::$ROOT_DIR.'/migration');

        $toApplyMigrations =  array_diff($files,$appliedMigrations);


        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..'){
                continue;
            }

            require_once App::$ROOT_DIR.'/migration/'.$migration;

            $className = pathinfo($migration,PATHINFO_FILENAME);
            echo $className;
            $instance = new $className();
            $this->log("Applying migrations $migration");
            $instance->up();
            $this->log("Applied migrations $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        }else{
            $this->log("All Migrations are applied");
        }

    }

    public function createDatabase(string $dbname){
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS ".$dbname);
        $this->pdo->exec("USE ".$dbname);
    }

    public function createMigrationTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ) ENGINE=INNODB;");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {

        $str =implode(',', array_map(fn($n)=>"('$n')",$migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");

        $statement->execute();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    protected function log($message)
    {
        echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }
}
