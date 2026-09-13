<?php
function autoInitDatabase() {
    try {
        require_once __DIR__ . '/config/database.php';
        $db = db();
        
        $host = getenv('DB_HOST');
        if ($host) {
            $tables = $db->fetch("SELECT tablename FROM pg_tables WHERE schemaname = 'public' AND tablename = 'requirements'");
        } else {
            $tables = $db->fetch("SHOW TABLES LIKE 'requirements'");
        }
        
        if ($tables) {
            return;
        }
        
        $sqlFile = $host 
            ? BASE_PATH . '/database/database-postgres.sql'
            : BASE_PATH . '/database/database.sql';
            
        if (!file_exists($sqlFile)) {
            return;
        }
        
        $sql = file_get_contents($sqlFile);
        $sql = preg_replace('/--[^\n]*/', '', $sql);
        
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if (!empty($stmt) && strlen($stmt) > 5) {
                try {
                    $db->query($stmt);
                } catch (Exception $e) {
                    error_log('Init failed: ' . substr($e->getMessage(), 0, 100) . ' | ' . substr($stmt, 0, 60));
                }
            }
        }
        
        error_log('Database auto-initialized');
    } catch (Exception $e) {
        error_log('DB init failed: ' . $e->getMessage());
    }
}

autoInitDatabase();
