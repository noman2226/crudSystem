<?php
// Database connection using PDO with automatic database creation if missing
define('DB_HOST', 'localhost');
define('DB_NAME', 'crud_system');
define('DB_USER', 'root');
define('DB_PASS', '');

function getPDO()
{
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    $dsnWithDb = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    try {
        $pdo = new PDO($dsnWithDb, DB_USER, DB_PASS, $options);
        // Ensure the students table exists (safe to run repeatedly)
        $pdo->exec("CREATE TABLE IF NOT EXISTS `students` (
            `id` int unsigned NOT NULL AUTO_INCREMENT,
            `first_name` varchar(100) NOT NULL,
            `last_name` varchar(100) NOT NULL,
            `reg_no` varchar(50) NOT NULL,
            `email` varchar(150) NOT NULL,
            `dob` date DEFAULT NULL,
            `course` varchar(150) DEFAULT NULL,
            `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email_unique` (`email`),
            UNIQUE KEY `regno_unique` (`reg_no`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        // older installations might lack reg_no column, attempt to add it
        try {
            $pdo->exec("ALTER TABLE `students` ADD COLUMN `reg_no` varchar(50) NOT NULL AFTER `last_name`");
            $pdo->exec("ALTER TABLE `students` ADD UNIQUE KEY `regno_unique` (`reg_no`)");
        } catch (PDOException $ignored) {
            // ignore errors (column/index may already exist)
        }
        return $pdo;
    } catch (PDOException $e) {
        // If database does not exist (SQLSTATE[HY000] [1049]) try to create it
        $isUnknownDb = strpos($e->getMessage(), 'Unknown database') !== false || (int)$e->getCode() === 1049;
        if ($isUnknownDb) {
            try {
                $dsnNoDb = 'mysql:host=' . DB_HOST . ';charset=utf8mb4';
                $tmp = new PDO($dsnNoDb, DB_USER, DB_PASS, $options);
                $tmp->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                // reconnect to the newly created database and create table
                $pdo = new PDO($dsnWithDb, DB_USER, DB_PASS, $options);
                $pdo->exec("CREATE TABLE IF NOT EXISTS `students` (
                    `id` int unsigned NOT NULL AUTO_INCREMENT,
                    `first_name` varchar(100) NOT NULL,
                    `last_name` varchar(100) NOT NULL,
                    `reg_no` varchar(50) NOT NULL,
                    `email` varchar(150) NOT NULL,
                    `dob` date DEFAULT NULL,
                    `course` varchar(150) DEFAULT NULL,
                    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `email_unique` (`email`),
                    UNIQUE KEY `regno_unique` (`reg_no`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
                // ensure column exists if older DB
                try {
                    $pdo->exec("ALTER TABLE `students` ADD COLUMN `reg_no` varchar(50) NOT NULL AFTER `last_name`");
                    $pdo->exec("ALTER TABLE `students` ADD UNIQUE KEY `regno_unique` (`reg_no`)");
                } catch (PDOException $ignore) {
                }
                return $pdo;
            } catch (PDOException $e2) {
                http_response_code(500);
                echo 'Database creation failed: ' . htmlspecialchars($e2->getMessage()) . 
                     '. Import schema.sql manually or ensure MySQL user has CREATE DATABASE privilege.';
                exit;
            }
        }

        http_response_code(500);
        echo 'Database connection failed: ' . htmlspecialchars($e->getMessage());
        exit;
    }
}

