<?php
function autoInitDatabase() {
    try {
        require_once __DIR__ . '/config/database.php';
        $db = db();
        
        $host = getenv('DB_HOST');
        if (!$host) {
            return;
        }
        
        $tables = $db->fetch("SELECT tablename FROM pg_tables WHERE schemaname = 'public' AND tablename = 'requirements'");
        if ($tables) {
            return;
        }
        
        $sqlFile = BASE_PATH . '/database/database-postgres.sql';
        if (!file_exists($sqlFile)) {
            return;
        }
        
        $sql = file_get_contents($sqlFile);
        $sql = preg_replace('/--[^\n]*/', '', $sql);
        
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        $ok = 0;
        $fail = 0;
        foreach ($statements as $stmt) {
            $stmt = trim($stmt);
            if (!empty($stmt) && strlen($stmt) > 5) {
                try {
                    $db->query($stmt);
                    $ok++;
                } catch (Exception $e) {
                    $fail++;
                    error_log('Init err: ' . substr($e->getMessage(), 0, 80));
                }
            }
        }
        
        error_log("DB init: {$ok} ok, {$fail} failed");
    } catch (Exception $e) {
        error_log('DB init failed: ' . $e->getMessage());
    }
}

autoInitDatabase();
