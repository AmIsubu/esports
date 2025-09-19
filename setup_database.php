<?php
// Database setup script for UK E-Sports League
// Run this file once to set up the database and tables

include 'dbconnect.php';

echo "<h2>UK E-Sports League - Database Setup</h2>";

try {
    // First, connect without specifying a database to create it
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connected to MySQL server successfully</p>";
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    $conn->exec($sql);
    echo "<p>✅ Database '$database' created or already exists</p>";
    
    // Now connect to the specific database
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Connected to database '$database' successfully</p>";
    
    // Read and execute the SQL file
    $sqlFile = file_get_contents('esports.sql');
    
    // Split the SQL file into individual statements
    $statements = explode(';', $sqlFile);
    
    $executed = 0;
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^--/', $statement) && !preg_match('/^\/\*/', $statement)) {
            try {
                $conn->exec($statement);
                $executed++;
            } catch (PDOException $e) {
                // Ignore errors for statements that might already exist
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "<p>⚠️ Warning: " . $e->getMessage() . "</p>";
                }
            }
        }
    }
    
    echo "<p>✅ Executed $executed SQL statements</p>";
    
    // Verify tables exist
    $tables = ['merchandise', 'participant', 'team', 'user'];
    foreach ($tables as $table) {
        $stmt = $conn->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        if ($stmt->rowCount() > 0) {
            echo "<p>✅ Table '$table' exists</p>";
        } else {
            echo "<p>❌ Table '$table' missing</p>";
        }
    }
    
    // Check if admin user exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM user WHERE username = 'admin'");
    $stmt->execute();
    $adminCount = $stmt->fetchColumn();
    
    if ($adminCount > 0) {
        echo "<p>✅ Admin user exists</p>";
    } else {
        // Create admin user with hashed password
        $hashedPassword = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO user (username, password) VALUES ('admin', ?)");
        $stmt->execute([$hashedPassword]);
        echo "<p>✅ Admin user created (username: admin, password: password123)</p>";
    }
    
    echo "<h3>🎉 Database setup completed successfully!</h3>";
    echo "<p><strong>Admin Login:</strong></p>";
    echo "<ul>";
    echo "<li>Username: admin</li>";
    echo "<li>Password: password123</li>";
    echo "</ul>";
    echo "<p><a href='index.html'>Go to Home Page</a> | <a href='admin_login.html'>Admin Login</a></p>";
    
} catch(PDOException $e) {
    echo "<p>❌ Database setup failed: " . $e->getMessage() . "</p>";
    echo "<h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Make sure XAMPP is running</li>";
    echo "<li>Check that MySQL service is started</li>";
    echo "<li>Verify database credentials in dbconnect.php</li>";
    echo "<li>Try accessing phpMyAdmin at <a href='http://localhost/phpmyadmin'>http://localhost/phpmyadmin</a></li>";
    echo "</ul>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: #f5f5f5;
}
h2, h3 {
    color: #333;
}
p {
    margin: 10px 0;
    padding: 5px;
}
ul {
    background: white;
    padding: 15px;
    border-radius: 5px;
}
a {
    color: #007bff;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
</style>
