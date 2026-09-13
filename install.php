<?php
/**
 * Auto-initialize database on first run.
 * Checks if tables exist, imports schema if not.
 */

function autoInitDatabase() {
    try {
        require_once __DIR__ . '/config/database.php';
        $db = db();
        
        // Check if tables exist
        $host = getenv('DB_HOST');
        if ($host) {
            // PostgreSQL
            $tables = $db->fetch("SELECT tablename FROM pg_tables WHERE schemaname = 'public' AND tablename = 'requirements'");
        } else {
            // MySQL
            $tables = $db->fetch("SHOW TABLES LIKE 'requirements'");
        }
        
        if ($tables) {
            return; // Already initialized
        }
        
        // Import schema
        $sqlFile = $host 
            ? BASE_PATH . '/database/database-postgres.sql'
            : BASE_PATH . '/database/database.sql';
            
        if (!file_exists($sqlFile)) {
            return;
        }
        
        $sql = file_get_contents($sqlFile);
        
        if ($host) {
            // PostgreSQL: execute each statement
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($statements as $stmt) {
                if (!empty($stmt) && strpos($stmt, '--') !== 0) {
                    try {
                        $db->query($stmt);
                    } catch (Exception $e) {
                        error_log('Init statement failed: ' . $e->getMessage());
                    }
                }
            }
        } else {
            // MySQL: execute as-is
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            foreach ($statements as $stmt) {
                if (!empty($stmt)) {
                    try {
                        $db->query($stmt);
                    } catch (Exception $e) {
                        error_log('Init statement failed: ' . $e->getMessage());
                    }
                }
            }
        }
        
        error_log('Database auto-initialized successfully');
    } catch (Exception $e) {
        error_log('Database auto-init failed: ' . $e->getMessage());
    }
}

// Run on every request (safe - no-op if already initialized)
autoInitDatabase();
