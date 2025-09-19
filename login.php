<?php
session_start();

include 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $input_username = trim($_POST['username']);
        $input_password = $_POST['password'];

        // Prepare and execute the query to check credentials (fetch hash only)
        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$input_username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $input_password === $user['password']) {
            // Login successful
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];
            header('Location: admin_menu.php');
            exit();
        } else {
            // Login failed
            $error_message = "Invalid username or password. Please try again.";
        }
    }
    catch(PDOException $e) {
        $error_message = "Database connection error: " . $e->getMessage();
    }
} else {
    $error_message = "Invalid request method.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Error - UK E-Sports League</title>
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

        .error-container {
            padding: 100px 0 50px;
        }

        .error-card {
            background: rgba(26, 26, 26, 0.9);
            border: 2px solid var(--esports-secondary);
            border-radius: 20px;
            padding: 3rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(255, 107, 53, 0.1);
            position: relative;
            overflow: hidden;
        }

        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, var(--esports-secondary), #dc3545);
            opacity: 0.05;
            z-index: -1;
        }

        .error-icon {
            font-size: 4rem;
            color: var(--esports-secondary);
            text-align: center;
            margin-bottom: 1rem;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .error-title {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 2.5rem;
            color: var(--esports-secondary);
            text-align: center;
            margin-bottom: 2rem;
        }

        .error-message {
            background: rgba(255, 107, 53, 0.1);
            border: 1px solid var(--esports-secondary);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            color: rgba(248, 249, 250, 0.9);
            text-align: center;
        }

        .btn-esports {
            background: linear-gradient(45deg, var(--esports-primary), var(--esports-secondary));
            border: none;
            color: white;
            font-weight: 600;
            padding: 15px 40px;
            border-radius: 25px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-block;
        }

        .btn-esports:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 212, 255, 0.3);
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
        }

        .back-link:hover {
            color: var(--esports-secondary);
            transform: translateX(-5px);
        }

        @media (max-width: 768px) {
            .error-card {
                padding: 2rem;
                margin: 1rem;
            }
            
            .error-title {
                font-size: 2rem;
            }
            
            .error-container {
                padding: 80px 0 30px;
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

    <!-- Error Section -->
    <section class="error-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-xl-5">
                    <a href="admin_login.html" class="back-link">
                        <i class="fas fa-arrow-left me-2"></i>Back to Login
                    </a>
                    
                    <div class="error-card">
                        <div class="error-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        
                        <h1 class="error-title">Login Failed</h1>
                        
                        <div class="error-message">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php echo isset($error_message) ? $error_message : 'An unknown error occurred.'; ?>
                        </div>

                        <div class="text-center">
                            <a href="admin_login.html" class="btn btn-esports">
                                <i class="fas fa-redo me-2"></i>Try Again
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>