<?php
session_start();

//ensure users are logged in to access this page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.html');
    exit();
}

include 'dbconnect.php';

$success_message = "";
$error_message = "";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $participant_id = (int)$_GET['id'];
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Double-check participant exists
        $check = $conn->prepare("SELECT id FROM participant WHERE id = ?");
        $check->execute([$participant_id]);
        if (!$check->fetch()) {
            $error_message = "Participant not found or already deleted.";
        } else {
            // Delete the participant
            $stmt = $conn->prepare("DELETE FROM participant WHERE id = ?");
            $stmt->execute([$participant_id]);
            if ($stmt->rowCount() > 0) {
                $success_message = "Participant deleted successfully.";
            } else {
                $error_message = "Participant not found or already deleted.";
            }
        }
    } catch(PDOException $e) {
        $error_message = "Database error: " . $e->getMessage();
    }
} else {
    $error_message = "Invalid participant ID.";
}

// Redirect back to participants list after a short delay
if ($success_message) {
    header("refresh:2;url=view_participants_edit_delete.php");
} else {
    header("refresh:3;url=view_participants_edit_delete.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete Participant - UK E-Sports League</title>
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

        .redirect-info {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--esports-primary);
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            color: rgba(248, 249, 250, 0.8);
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
                        <a class="nav-link" href="register_form.html">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Admin</a>
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
                    <div class="result-card <?php echo $success_message ? 'success' : 'error'; ?>">
                        <div class="result-icon <?php echo $success_message ? 'success' : 'error'; ?>">
                            <?php if ($success_message): ?>
                                <i class="fas fa-check-circle"></i>
                            <?php else: ?>
                                <i class="fas fa-exclamation-triangle"></i>
                            <?php endif; ?>
                        </div>
                        
                        <h1 class="result-title <?php echo $success_message ? 'success' : 'error'; ?>">
                            <?php echo $success_message ? 'Participant Deleted' : 'Delete Failed'; ?>
                        </h1>
                        
                        <div class="result-message <?php echo $success_message ? 'success' : 'error'; ?>">
                            <i class="fas fa-info-circle me-2"></i>
                            <?php echo $success_message ? $success_message : $error_message; ?>
                        </div>

                        <div class="redirect-info">
                            <i class="fas fa-clock me-2"></i>
                            Redirecting back to participants list...
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