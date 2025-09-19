<?php
//including connection variables  
include 'dbconnect.php';

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $firstname = trim($_POST['firstname']);
        $surname = trim($_POST['surname']);
        $email = trim($_POST['email']);
        $terms = isset($_POST['terms']) ? 1 : 0;
        
        // Validate input
        if (empty($firstname) || empty($surname) || empty($email)) {
            $error_message = "Please fill in all required fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Please enter a valid email address.";
        } elseif (!$terms) {
            $error_message = "You must accept the terms and conditions to register.";
        } else {
            // Check if email already exists
            $check_stmt = $conn->prepare("SELECT id FROM merchandise WHERE email = ?");
            $check_stmt->execute([$email]);
            
            if ($check_stmt->fetch()) {
                $error_message = "This email address is already registered.";
            } else {
                // Insert new registration
                $stmt = $conn->prepare("INSERT INTO merchandise (firstname, surname, email, terms) VALUES (?, ?, ?, ?)");
                $stmt->execute([$firstname, $surname, $email, $terms]);
                
                $success_message = "Registration successful! You will receive your free merchandise soon.";
            }
        }
    }
    catch(PDOException $e) {
        $error_message = "Registration failed: " . $e->getMessage();
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
    <title>Registration Result - UK E-Sports League</title>
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

        .result-container {
            padding: 100px 0 50px;
        }

        .result-card {
            background: rgba(26, 26, 26, 0.9);
            border: 2px solid var(--esports-primary);
            border-radius: 20px;
            padding: 3rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 212, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .result-card.success {
            border-color: #28a745;
        }

        .result-card.error {
            border-color: var(--esports-secondary);
        }

        .result-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, var(--esports-primary), var(--esports-secondary), var(--esports-accent));
            opacity: 0.05;
            z-index: -1;
        }

        .result-icon {
            font-size: 4rem;
            text-align: center;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        .result-icon.success {
            color: #28a745;
        }

        .result-icon.error {
            color: var(--esports-secondary);
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .result-title {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        .result-title.success {
            color: #28a745;
        }

        .result-title.error {
            color: var(--esports-secondary);
        }

        .result-message {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--esports-primary);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .result-message.success {
            background: rgba(40, 167, 69, 0.1);
            border-color: #28a745;
            color: #28a745;
        }

        .result-message.error {
            background: rgba(255, 107, 53, 0.1);
            border-color: var(--esports-secondary);
            color: var(--esports-secondary);
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

        .next-steps {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--esports-primary);
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .next-steps h6 {
            color: var(--esports-primary);
            font-family: 'Orbitron', monospace;
            margin-bottom: 1rem;
        }

        .next-steps ul {
            color: rgba(248, 249, 250, 0.8);
            margin: 0;
            padding-left: 1.5rem;
        }

        .next-steps li {
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .result-card {
                padding: 2rem;
                margin: 1rem;
            }
            
            .result-title {
                font-size: 2rem;
            }
            
            .result-container {
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
                        <a class="nav-link active" href="#">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_login.html">Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Result Section -->
    <section class="result-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-xl-5">
                    <a href="index.html" class="back-link">
                        <i class="fas fa-arrow-left me-2"></i>Back to Home
                    </a>
                    
                    <div class="result-card <?php echo $success_message ? 'success' : 'error'; ?>">
                        <div class="result-icon <?php echo $success_message ? 'success' : 'error'; ?>">
                            <?php if ($success_message): ?>
                                <i class="fas fa-check-circle"></i>
                            <?php else: ?>
                                <i class="fas fa-exclamation-triangle"></i>
                            <?php endif; ?>
                        </div>
                        
                        <h1 class="result-title <?php echo $success_message ? 'success' : 'error'; ?>">
                            <?php echo $success_message ? 'Registration Successful!' : 'Registration Failed'; ?>
                        </h1>
                        
                        <div class="result-message <?php echo $success_message ? 'success' : 'error'; ?>">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php echo $success_message ? $success_message : $error_message; ?>
                        </div>

                        <?php if ($success_message): ?>
                            <div class="next-steps">
                                <h6><i class="fas fa-star me-2"></i>What's Next?</h6>
                                <ul>
                                    <li>Check your email for confirmation</li>
                                    <li>Merchandise will be shipped within 5-7 business days</li>
                                    <li>Follow us on social media for updates</li>
                                    <li>Join our community for exclusive content</li>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="text-center">
                            <?php if ($success_message): ?>
                                <a href="index.html" class="btn btn-esports">
                                    <i class="fas fa-home me-2"></i>Return Home
                                </a>
                            <?php else: ?>
                                <a href="register_form.html" class="btn btn-esports">
                                    <i class="fas fa-redo me-2"></i>Try Again
                                </a>
                            <?php endif; ?>
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