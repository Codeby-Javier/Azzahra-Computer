<?php
// Simple database test
try {
    $pdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '');
    echo "Database connection: OK\n";
    
    // Check if database exists
    $result = $pdo->query("SHOW DATABASES LIKE 'cv_azzahra'");
    $db_exists = $result->fetch();
    
    if ($db_exists) {
        echo "Database 'cv_azzahra': EXISTS\n";
        
        // Connect to database
        $pdo = new PDO('mysql:host=localhost;dbname=cv_azzahra;charset=utf8mb4', 'root', '');
        
        // Check table exists
        $result = $pdo->query("SHOW TABLES LIKE 'poin_performa'");
        $table_exists = $result->fetch();
        
        if ($table_exists) {
            echo "Table 'poin_performa': EXISTS\n";
            
            // Count current records
            $result = $pdo->query("SELECT COUNT(*) as cnt FROM poin_performa WHERE periode_minggu = '2026-W02'");
            $row = $result->fetch(PDO::FETCH_ASSOC);
            echo "Current records for 2026-W02: " . $row['cnt'] . "\n";
        } else {
            echo "Table 'poin_performa': NOT FOUND\n";
        }
    } else {
        echo "Database 'cv_azzahra': NOT FOUND\n";
    }
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
