<?php
session_start();

//ensure users are logged in to access this page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.html');
    exit();
}

//including connection variables   
include 'dbconnect.php';

$merchandise = [];
$error_message = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all merchandise registrations
    $stmt = $conn->prepare("SELECT * FROM merchandise ORDER BY id DESC");
    $stmt->execute();
    $merchandise = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Merchandise Registrations - UK E-Sports League</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --esports-primary: #00d4ff;
            --esports-secondary: #ff6b35;
            --esports-dark: #0a0a0a;
            --esports-gray: #1a1a1a;
            --esports-light: #f8f9fa;
            --esports-accent: #7b2cbf;
        }

        body {
            font-family: 'Exo 2', sans-serif;
            background: linear-gradient(135deg, var(--esports-dark) 0%, var(--esports-gray) 100%);
            color: var(--esports-light);
            min-height: 100vh;
        }

        .navbar {
            background: rgba(10, 10, 10, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 2px solid var(--esports-primary);
        }

        .navbar-brand {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 1.8rem;
            color: var(--esports-primary) !important;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }

        .merchandise-container {
            padding: 100px 0 50px;
        }

        .merchandise-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .merchandise-title {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 3rem;
            background: linear-gradient(45deg, var(--esports-primary), var(--esports-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .merchandise-subtitle {
            color: rgba(248, 249, 250, 0.8);
            font-size: 1.2rem;
        }

        .merchandise-table {
            background: rgba(26, 26, 26, 0.9);
            border: 2px solid var(--esports-primary);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 212, 255, 0.1);
            overflow-x: auto;
        }

        .table {
            color: var(--esports-light);
            margin-bottom: 0;
        }

        .table thead th {
            border-bottom: 2px solid var(--esports-primary);
            color: var(--esports-primary);
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(0, 212, 255, 0.2);
        }

        .table tbody tr:hover {
            background: rgba(0, 212, 255, 0.1);
        }

        .back-link {
            color: var(--esports-primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .back-link:hover {
            color: var(--esports-secondary);
            transform: translateX(-5px);
        }

        .stats-info {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--esports-primary);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .stats-info h6 {
            color: var(--esports-primary);
            font-family: 'Orbitron', monospace;
            margin-bottom: 1rem;
        }

        .stats-info p {
            color: rgba(248, 249, 250, 0.8);
            margin: 0;
            font-size: 0.9rem;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: rgba(248, 249, 250, 0.6);
        }

        .no-data i {
            font-size: 3rem;
            color: var(--esports-primary);
            margin-bottom: 1rem;
        }

        .terms-badge {
            background: linear-gradient(45deg, var(--esports-primary), var(--esports-accent));
            color: white;
            padding: 4px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .terms-badge.no {
            background: linear-gradient(45deg, var(--esports-secondary), #dc3545);
        }

        @media (max-width: 768px) {
            .merchandise-table {
                padding: 1rem;
                margin: 1rem;
            }
            
            .merchandise-title {
                font-size: 2.5rem;
            }
            
            .merchandise-container {
                padding: 80px 0 30px;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <i class="fas fa-gamepad me-2"></i>UK E-Sports League
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register_form.html">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Merchandise Section -->
    <section class="merchandise-container">
        <div class="container">
            <div class="merchandise-header">
                <h1 class="merchandise-title">Merchandise Registrations</h1>
                <p class="merchandise-subtitle">View all merchandise registrations and manage orders</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-12">
                    <a href="admin_menu.php" class="back-link">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>

                    <div class="stats-info">
                        <h6><i class="fas fa-gift me-2"></i>Merchandise Statistics</h6>
                        <p>Total Registrations: <?php echo count($merchandise); ?> | Terms Accepted: <?php echo count(array_filter($merchandise, function($item) { return $item['terms'] == 1; })); ?> | Pending: <?php echo count(array_filter($merchandise, function($item) { return $item['terms'] == 0; })); ?></p>
                    </div>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error_message; ?>
                        </div>
                    <?php elseif (empty($merchandise)): ?>
                        <div class="merchandise-table">
                            <div class="no-data">
                                <i class="fas fa-gift"></i>
                                <h4>No Merchandise Registrations</h4>
                                <p>There are currently no merchandise registrations in the database.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="merchandise-table">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-id-card me-2"></i>ID</th>
                                            <th><i class="fas fa-user me-2"></i>Name</th>
                                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                                            <th><i class="fas fa-check-circle me-2"></i>Terms</th>
                                            <th><i class="fas fa-calendar me-2"></i>Registration Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($merchandise as $item): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['id']); ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($item['firstname'] . ' ' . $item['surname']); ?></strong>
                                                </td>
                                                <td>
                                                    <a href="mailto:<?php echo htmlspecialchars($item['email']); ?>" class="text-primary">
                                                        <?php echo htmlspecialchars($item['email']); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php if ($item['terms'] == 1): ?>
                                                        <span class="terms-badge">Accepted</span>
                                                    <?php else: ?>
                                                        <span class="terms-badge no">Not Accepted</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <?php 
                                                        // If there's a timestamp field, use it. Otherwise show "Unknown"
                                                        echo isset($item['created_at']) ? date('M j, Y', strtotime($item['created_at'])) : 'Unknown';
                                                        ?>
                                                    </small>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
