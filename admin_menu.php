<?php
session_start();

//ensure users are logged in to access this page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard - UK E-Sports League</title>
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

        .dashboard-container {
            padding: 100px 0 50px;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .dashboard-title {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 3rem;
            background: linear-gradient(45deg, var(--esports-primary), var(--esports-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .dashboard-subtitle {
            color: rgba(248, 249, 250, 0.8);
            font-size: 1.2rem;
        }

        .admin-card {
            background: rgba(26, 26, 26, 0.95);
            border: 2px solid transparent;
            border-radius: 25px;
            padding: 3rem 2rem;
            transition: all 0.4s ease;
            backdrop-filter: blur(15px);
            position: relative;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .admin-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--esports-primary), var(--esports-secondary), var(--esports-accent));
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
        }

        .admin-card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: -1;
        }

        .admin-card:hover {
            transform: translateY(-15px) scale(1.02);
            border-color: var(--esports-primary);
            box-shadow: 0 25px 50px rgba(0, 212, 255, 0.25);
        }

        .admin-card:hover::before {
            opacity: 0.08;
        }

        .admin-card:hover::after {
            opacity: 1;
        }

        .admin-card .card-icon {
            font-size: 4rem;
            color: var(--esports-primary);
            margin-bottom: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            text-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
        }

        .admin-card:hover .card-icon {
            transform: scale(1.1) rotate(5deg);
            color: var(--esports-secondary);
        }

        .admin-card .card-title {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            color: var(--esports-light);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.4rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .admin-card .card-text {
            color: rgba(248, 249, 250, 0.8);
            margin-bottom: 2.5rem;
            text-align: center;
            line-height: 1.6;
            font-size: 1rem;
        }

        .btn-admin {
            background: linear-gradient(135deg, var(--esports-primary), var(--esports-secondary));
            border: none;
            color: white;
            font-weight: 700;
            padding: 15px 30px;
            border-radius: 30px;
            transition: all 0.4s ease;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            width: 100%;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 212, 255, 0.2);
        }

        .btn-admin::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-admin:hover::before {
            left: 100%;
        }

        .btn-admin:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 30px rgba(0, 212, 255, 0.4);
            color: white;
        }

        .btn-logout {
            background: linear-gradient(135deg, var(--esports-secondary), #dc3545);
            border: none;
            color: white;
            font-weight: 700;
            padding: 15px 30px;
            border-radius: 30px;
            transition: all 0.4s ease;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            width: 100%;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.2);
        }

        .btn-logout::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-logout:hover::before {
            left: 100%;
        }

        .btn-logout:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 30px rgba(255, 107, 53, 0.4);
            color: white;
        }

        .stats-section {
            background: rgba(26, 26, 26, 0.9);
            border: 2px solid var(--esports-primary);
            border-radius: 25px;
            padding: 3rem;
            margin-bottom: 4rem;
            backdrop-filter: blur(15px);
            box-shadow: 0 25px 50px rgba(0, 212, 255, 0.15);
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
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

        .stats-title {
            font-family: 'Orbitron', monospace;
            color: var(--esports-primary);
            text-align: center;
            margin-bottom: 3rem;
            font-size: 1.8rem;
            text-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
        }

        .stat-item {
            text-align: center;
            padding: 2rem 1rem;
            background: rgba(0, 212, 255, 0.05);
            border-radius: 15px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 212, 255, 0.2);
        }

        .stat-item:hover {
            transform: translateY(-5px);
            background: rgba(0, 212, 255, 0.1);
            border-color: var(--esports-primary);
            box-shadow: 0 10px 25px rgba(0, 212, 255, 0.2);
        }

        .stat-number {
            font-family: 'Orbitron', monospace;
            font-size: 3rem;
            font-weight: 900;
            color: var(--esports-primary);
            display: block;
            text-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: rgba(248, 249, 250, 0.9);
            font-size: 1.1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .welcome-section {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--esports-primary);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 3rem;
            text-align: center;
        }

        .welcome-section h4 {
            color: var(--esports-primary);
            font-family: 'Orbitron', monospace;
            margin-bottom: 1rem;
        }

        .welcome-section p {
            color: rgba(248, 249, 250, 0.8);
            margin: 0;
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
            width: 4px;
            height: 4px;
            background: var(--esports-primary);
            border-radius: 50%;
            opacity: 0.3;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { top: 60%; right: 15%; animation-delay: 2s; }
        .particle:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 4s; }
        .particle:nth-child(4) { top: 40%; right: 30%; animation-delay: 1s; }
        .particle:nth-child(5) { bottom: 60%; left: 60%; animation-delay: 3s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 0.6; }
        }

        @media (max-width: 768px) {
            .dashboard-title {
                font-size: 2.5rem;
            }
            
            .admin-card {
                margin-bottom: 2rem;
                padding: 2rem 1.5rem;
            }
            
            .dashboard-container {
                padding: 80px 0 30px;
            }

            .stats-section {
                padding: 2rem;
                margin-bottom: 2rem;
            }

            .stat-item {
                padding: 1.5rem 1rem;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .btn-admin, .btn-logout {
                padding: 12px 25px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-title {
                font-size: 2rem;
            }

            .admin-card .card-icon {
                font-size: 3rem;
            }

            .admin-card .card-title {
                font-size: 1.2rem;
            }

            .stats-title {
                font-size: 1.5rem;
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
                        <a class="nav-link active" href="#">Admin Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Section -->
    <section class="dashboard-container">
        <div class="container">
            <div class="dashboard-header">
                <h1 class="dashboard-title">Admin Dashboard</h1>
                <p class="dashboard-subtitle">Manage your E-Sports League with powerful administrative tools</p>
            </div>

            <!-- Welcome Section -->
            <div class="welcome-section">
                <h4><i class="fas fa-crown me-2"></i>Welcome, Administrator!</h4>
                <p>You have full access to manage participants, merchandise, and tournament data. Use the tools below to oversee your E-Sports League operations.</p>
            </div>

            <!-- Quick Stats -->
            <div class="stats-section">
                <h3 class="stats-title"><i class="fas fa-chart-bar me-2"></i>Quick Statistics</h3>
                <div class="row">
                    <?php
                    // Get real-time statistics from database
                    include 'dbconnect.php';
                    $participantCount = 0;
                    $merchandiseCount = 0;
                    $teamCount = 0;
                    $totalKills = 0;
                    $totalDeaths = 0;
                    
                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // Get participant count
                        $stmt = $conn->query("SELECT COUNT(*) FROM participant");
                        $participantCount = $stmt->fetchColumn();
                        
                        // Get merchandise registrations count
                        $stmt = $conn->query("SELECT COUNT(*) FROM merchandise");
                        $merchandiseCount = $stmt->fetchColumn();
                        
                        // Get team count
                        $stmt = $conn->query("SELECT COUNT(*) FROM team");
                        $teamCount = $stmt->fetchColumn();
                        
                        // Get total kills and deaths
                        $stmt = $conn->query("SELECT SUM(kills) as total_kills, SUM(deaths) as total_deaths FROM participant");
                        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
                        $totalKills = $stats['total_kills'] ?? 0;
                        $totalDeaths = $stats['total_deaths'] ?? 0;
                        
                    } catch(PDOException $e) {
                        // Use default values if database connection fails
                    }
                    ?>
                    <div class="col-md-2 col-6">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $participantCount; ?></span>
                            <div class="stat-label">Participants</div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $merchandiseCount; ?></span>
                            <div class="stat-label">Merchandise</div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $teamCount; ?></span>
                            <div class="stat-label">Teams</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $totalKills; ?></span>
                            <div class="stat-label">Total Kills</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo $totalDeaths; ?></span>
                            <div class="stat-label">Total Deaths</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Actions -->
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="admin-card">
                        <div class="card-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="card-title">Search & Find</h3>
                        <p class="card-text">Search for specific participants or teams. View detailed information and statistics for each player or team.</p>
                        <a href="search_form.php" class="btn btn-admin">
                            <i class="fas fa-search me-2"></i>Search Database
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="admin-card">
                        <div class="card-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h3 class="card-title">Manage Participants</h3>
                        <p class="card-text">View all participants, edit their information, update scores, or remove players from the tournament.</p>
                        <a href="view_participants_edit_delete.php" class="btn btn-admin">
                            <i class="fas fa-users-cog me-2"></i>Manage Players
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="admin-card">
                        <div class="card-icon">
                            <i class="fas fa-gift"></i>
                        </div>
                        <h3 class="card-title">Merchandise</h3>
                        <p class="card-text">View all merchandise registrations, manage orders, and track who has registered for free gaming gear.</p>
                        <a href="view_merchandise.php" class="btn btn-admin">
                            <i class="fas fa-gift me-2"></i>View Merchandise
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="admin-card">
                        <div class="card-icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <h3 class="card-title">Logout</h3>
                        <p class="card-text">Securely logout from the admin panel. Your session will be terminated and you'll be redirected to the home page.</p>
                        <a href="logout.php" class="btn btn-logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(10, 10, 10, 0.98)';
            } else {
                navbar.style.background = 'rgba(10, 10, 10, 0.95)';
            }
        });

        // Add loading states to buttons
        document.querySelectorAll('.btn-admin, .btn-logout').forEach(button => {
            button.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                this.disabled = true;
                
                // Re-enable after a short delay (in case of navigation issues)
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 3000);
            });
        });
    </script>
</body>
</html>