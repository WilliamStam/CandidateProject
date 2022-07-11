<?php

namespace update\database;

use update\AbstractBase;
use update\RunInterface;

class Run extends AbstractBase implements RunInterface {

    protected $migration_indent = 0;

    static function cmd() {
        return "database";
    }

    function run() {

        $this->label("DATABASE", 0);

         $this->db()->exec("
            CREATE TABLE IF NOT EXISTS `migrations` (
                `migration` VARCHAR(250) DEFAULT NULL,
                `result` LONGTEXT DEFAULT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP(),
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP(),
                UNIQUE KEY `unq` (`system`,`migration`) USING BTREE
            );
        ");


        if (!$this->is_dev()) {
            $this->migrations();
        }
//        $this->migrations();
        $this->every();

    }

    function every() {
        $this->label("EVERY", 1);
        foreach ($this->get_files("every") as $file) {
            $key = pathinfo($file, PATHINFO_FILENAME);
            $sql = file_get_contents($file);
            $line = str_pad($key, 30, " ", STR_PAD_RIGHT);
            $status = "SUCCESS";
            try {
                $this->db()->exec($sql);
            } catch (\Exception $e) {
                $status = $e->getMessage() . " [" . $e->getFile() . ":" . $e->getLine() . "]";
            }
            $this->update_migrations_table($key, $status);
            $this->label($line . ": " . $status, 2);
//            var_dump($sql);
        }
    }

    function get_done_migrations() {
        $migrations = $this->db()->select("
            SELECT
                *
            FROM
                migrations
        ");

        return $migrations;
    }

    function migrations() {
        $this->label("MIGRATIONS", 1);
        $migrations = array();

        $done_migrations = array();
        foreach ($migrations as $migration) {
            $done_migrations[$migration['migration']] = $migration;
        }


        foreach ($this->get_files("migration") as $file) {
            $key = pathinfo($file, PATHINFO_FILENAME);
            $sql = file_get_contents($file);
            $line = str_pad($key, 30, " ", STR_PAD_RIGHT);

            if (in_array($key, array_keys($done_migrations))) {
                $status = "EXISTS";
            } else {
                $status = "SUCCESS";
                try {
                    $this->db()->exec($sql);
                } catch (\Exception $e) {
                    $status = $e->getMessage();
                }
                $this->update_migrations_table($key, $status);
            }


            $this->label($line . ": " . $status, 2);
//            var_dump($sql);
        }

    }

    private function update_migrations_table($key, $result) {
        $this->db()->exec("
            INSERT INTO migrations (
                `migration`,
                `result`
            ) VALUES (
                :migration,
                :result
            ) ON DUPLICATE KEY UPDATE
                `updated_at` = NOW(),
                `result` = VALUES(result)
        ", array(
            ":migration" => $key,
            ":result" => $result,
        ));
    }

    private function get_files($folder) {
        $db_updates_path = realpath("../update/database/" . $folder);
        $dir = new \DirectoryIterator($db_updates_path);
        $files = array();
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $filename = $fileinfo->getFilename();
                $files[] = $fileinfo->getPath() . DIRECTORY_SEPARATOR . $filename;
            }
        }
        return $files;

    }


}
