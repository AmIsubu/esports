<?php
session_start();

//ensure users are logged in to access this page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.html');
    exit();
}

//including connection variables   
include 'dbconnect.php';

$participants = [];
$error_message = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all participants with team information
    $stmt = $conn->prepare("
        SELECT p.*, t.name as team_name, t.location as team_location 
        FROM participant p 
        LEFT JOIN team t ON p.team_id = t.id 
        ORDER BY p.surname, p.firstname
    ");
    $stmt->execute();
    $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
    <title>Manage Participants - UK E-Sports League</title>
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

        .participants-container {
            padding: 100px 0 50px;
        }

        .participants-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .participants-title {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 3rem;
            background: linear-gradient(45deg, var(--esports-primary), var(--esports-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .participants-subtitle {
            color: rgba(248, 249, 250, 0.8);
            font-size: 1.2rem;
        }

        .participants-table {
            background: rgba(26, 26, 26, 0.95);
            border: 2px solid var(--esports-primary);
            border-radius: 25px;
            padding: 3rem;
            backdrop-filter: blur(15px);
            box-shadow: 0 25px 50px rgba(0, 212, 255, 0.15);
            position: relative;
            overflow: hidden;
        }

        .participants-table::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, var(--esports-primary), var(--esports-secondary), var(--esports-accent));
            opacity: 0.03;
            z-index: -1;
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
            padding: 1.5rem 1rem;
            background: rgba(0, 212, 255, 0.1);
        }

        .table tbody tr {
            border-bottom: 1px solid rgba(0, 212, 255, 0.2);
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(0, 212, 255, 0.1);
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 1.5rem 1rem;
            vertical-align: middle;
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--esports-primary), var(--esports-accent));
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 20px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .btn-edit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-edit:hover::before {
            left: 100%;
        }

        .btn-edit:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 212, 255, 0.3);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, var(--esports-secondary), #dc3545);
            border: none;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 20px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .btn-delete::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-delete:hover::before {
            left: 100%;
        }

        .btn-delete:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
            color: white;
        }

        .back-link {
            color: var(--esports-primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            margin-bottom: 2rem;
            padding: 10px 20px;
            background: rgba(0, 212, 255, 0.1);
            border-radius: 20px;
            border: 1px solid var(--esports-primary);
        }

        .back-link:hover {
            color: var(--esports-secondary);
            transform: translateX(-5px);
            background: rgba(255, 107, 53, 0.1);
            border-color: var(--esports-secondary);
        }

        .stats-info {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--esports-primary);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .stats-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, var(--esports-primary), var(--esports-secondary));
            opacity: 0.05;
            z-index: -1;
        }

        .stats-info h6 {
            color: var(--esports-primary);
            font-family: 'Orbitron', monospace;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .stats-info p {
            color: rgba(248, 249, 250, 0.9);
            margin: 0;
            font-size: 1rem;
        }

        .no-data {
            text-align: center;
            padding: 4rem 2rem;
            color: rgba(248, 249, 250, 0.6);
        }

        .no-data i {
            font-size: 4rem;
            color: var(--esports-primary);
            margin-bottom: 2rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .badge {
            font-size: 0.8rem;
            padding: 8px 12px;
            border-radius: 15px;
            font-weight: 600;
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, var(--esports-primary), var(--esports-accent)) !important;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #28a745, #20c997) !important;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #dc3545, #e83e8c) !important;
        }

        .floating-particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: var(--esports-primary);
            border-radius: 50%;
            opacity: 0.2;
            animation: float 8s ease-in-out infinite;
        }

        .particle:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { top: 60%; right: 15%; animation-delay: 2s; }
        .particle:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 4s; }
        .particle:nth-child(4) { top: 40%; right: 30%; animation-delay: 1s; }
        .particle:nth-child(5) { bottom: 60%; left: 60%; animation-delay: 3s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.2; }
            50% { transform: translateY(-15px) rotate(180deg); opacity: 0.4; }
        }

        @media (max-width: 768px) {
            .participants-table {
                padding: 2rem 1rem;
                margin: 1rem;
            }
            
            .participants-title {
                font-size: 2.5rem;
            }
            
            .participants-container {
                padding: 80px 0 30px;
            }
            
            .table-responsive {
                font-size: 0.9rem;
            }

            .table thead th,
            .table tbody td {
                padding: 1rem 0.5rem;
            }

            .btn-edit, .btn-delete {
                padding: 8px 15px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .participants-title {
                font-size: 2rem;
            }

            .stats-info {
                padding: 1.5rem;
            }

            .no-data {
                padding: 3rem 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Floating Particles -->
    <div class="floating-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

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

    <!-- Participants Section -->
    <section class="participants-container">
        <div class="container">
            <div class="participants-header">
                <h1 class="participants-title">Manage Participants</h1>
                <p class="participants-subtitle">View, edit, and manage all tournament participants</p>
                   <a href="add_participant_form.php" class="btn btn-esports mb-4" style="float:right;">
                       <i class="fas fa-user-plus me-2"></i>Add Participant
                   </a>
            </div>

            <div class="row justify-content-center">
                <div class="col-12">
                    <a href="admin_menu.php" class="back-link">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>

                    <div class="stats-info">
                        <h6><i class="fas fa-chart-bar me-2"></i>Participant Statistics</h6>
                        <p>Total Participants: <?php echo count($participants); ?> | Teams: 4 | Total Kills: 0 | Total Deaths: 0</p>
                    </div>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error_message; ?>
                        </div>
                    <?php elseif (empty($participants)): ?>
                        <div class="participants-table">
                            <div class="no-data">
                                <i class="fas fa-users"></i>
                                <h4>No Participants Found</h4>
                                <p>There are currently no participants in the database.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="participants-table">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-id-card me-2"></i>ID</th>
                                            <th><i class="fas fa-user me-2"></i>Name</th>
                                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                                            <th><i class="fas fa-users me-2"></i>Team</th>
                                            <th><i class="fas fa-crosshairs me-2"></i>Kills</th>
                                            <th><i class="fas fa-skull me-2"></i>Deaths</th>
                                            <th><i class="fas fa-cogs me-2"></i>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($participants as $participant): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary"><?php echo htmlspecialchars($participant['id']); ?></span>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($participant['firstname'] . ' ' . $participant['surname']); ?></strong>
                                                </td>
                                                <td>
                                                    <a href="mailto:<?php echo htmlspecialchars($participant['email']); ?>" class="text-primary">
                                                        <?php echo htmlspecialchars($participant['email']); ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php if ($participant['team_name']): ?>
                                                        <span class="badge bg-primary"><?php echo htmlspecialchars($participant['team_name']); ?></span>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($participant['team_location']); ?></small>
                                                    <?php else: ?>
                                                        <span class="text-muted">No Team</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success"><?php echo $participant['kills'] ?? 0; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger"><?php echo $participant['deaths'] ?? 0; ?></span>
                                                </td>
                                                <td>
                                                    <a href="edit_participant.php?id=<?php echo $participant['id']; ?>" class="btn btn-edit me-2">
                                                        <i class="fas fa-edit me-1"></i>Edit
                                                    </a>
                                                    <a href="delete.php?id=<?php echo $participant['id']; ?>" class="btn btn-delete" 
                                                       onclick="return confirm('Are you sure you want to delete this participant?')">
                                                        <i class="fas fa-trash me-1"></i>Delete
                                                    </a>
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
    <!-- Custom JS -->
    <script>
        // Add confirmation for delete actions
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this participant? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        });

        // Add loading states to action buttons
        document.querySelectorAll('.btn-edit, .btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
                this.disabled = true;
                
                // Re-enable after a short delay (in case of navigation issues)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            });
        });

        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(10, 10, 10, 0.98)';
            } else {
                navbar.style.background = 'rgba(10, 10, 10, 0.95)';
            }
        });

        // Add table row hover effects
        document.querySelectorAll('.table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.01)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
