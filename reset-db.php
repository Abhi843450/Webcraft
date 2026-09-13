<?php
require_once __DIR__ . '/config/database.php';
$db = db();
$host = getenv('DB_HOST');
if (!$host) { echo "No DB_HOST"; exit; }

$tables = $db->fetchAll("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
foreach ($tables as $t) {
    try {
        $db->query("DROP TABLE IF EXISTS public.{$t['tablename']} CASCADE");
        echo "Dropped: {$t['tablename']}\n";
    } catch (Exception $e) {
        echo "Error dropping {$t['tablename']}: {$e->getMessage()}\n";
    }
}
echo "All tables dropped. Schema will reimport on next page load.";
