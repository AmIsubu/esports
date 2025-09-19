<?php
session_start();

//ensure users are logged in to access this page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.html');
    exit();
}

//including connection variables   
include 'dbconnect.php';

$participant = null;
$error_message = "";
$success_message = "";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle form submission - UPDATE section
        $id = $_POST['id'] ?? '';
        $kills = $_POST['kills'] ?? 0;
        $email = trim($_POST['email'] ?? '');
        $firstname = trim($_POST['firstname'] ?? '');
        $surname = trim($_POST['surname'] ?? '');
        $deaths = $_POST['deaths'] ?? 0;
        
        // Validate input
        if (empty($id) || !is_numeric($id)) {
            $error_message = "Invalid participant ID.";
        } elseif (!is_numeric($kills) || !is_numeric($deaths)) {
            $error_message = "Kills and deaths must be numeric values.";
        } elseif ($kills < 0 || $deaths < 0) {
            $error_message = "Kills and deaths cannot be negative.";
        } else {
            // Update participant scores
            $stmt = $conn->prepare("UPDATE participant SET kills = ?, deaths = ? WHERE id = ?");
            $result = $stmt->execute([$kills, $deaths, $id]);
            
            if ($result) {
                $success_message = "Participant scores updated successfully!";
                // Redirect after 2 seconds
                header("refresh:2;url=view_participants_edit_delete.php");
            } else {
                $error_message = "Failed to update participant scores.";
            }
        }
    } else {
        // Handle GET request - SELECT section
        $id = $_GET['id'] ?? '';
        
        if (empty($id) || !is_numeric($id)) {
            $error_message = "Invalid participant ID.";
        } else {
            // Fetch participant data
            $stmt = $conn->prepare("SELECT * FROM participant WHERE id = ?");
            $stmt->execute([$id]);
            $participant = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$participant) {
                $error_message = "Participant not found.";
            }
        }
    }
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
    <title>Edit Participant - UK E-Sports League</title>
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

        .edit-container {
            padding: 100px 0 50px;
        }

        .edit-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .edit-title {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 3rem;
            background: linear-gradient(45deg, var(--esports-primary), var(--esports-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .edit-subtitle {
            color: rgba(248, 249, 250, 0.8);
            font-size: 1.2rem;
        }

        .edit-card {
            background: rgba(26, 26, 26, 0.95);
            border: 2px solid var(--esports-primary);
            border-radius: 25px;
            padding: 3rem;
            backdrop-filter: blur(15px);
            box-shadow: 0 25px 50px rgba(0, 212, 255, 0.15);
            position: relative;
            overflow: hidden;
            max-width: 600px;
            margin: 0 auto;
        }

        .edit-card::before {
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

        .form-group {
            margin-bottom: 2rem;
            position: relative;
        }

        .form-label {
            color: var(--esports-primary);
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.8rem;
            display: block;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-control {
            background: rgba(26, 26, 26, 0.8);
            border: 2px solid rgba(0, 212, 255, 0.3);
            border-radius: 15px;
            color: var(--esports-light);
            padding: 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .form-control:focus {
            background: rgba(26, 26, 26, 0.9);
            border-color: var(--esports-primary);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
            color: var(--esports-light);
        }

        .form-control:disabled {
            background: rgba(26, 26, 26, 0.5);
            border-color: rgba(0, 212, 255, 0.2);
            color: rgba(248, 249, 250, 0.6);
            cursor: not-allowed;
        }

        .form-control::placeholder {
            color: rgba(248, 249, 250, 0.5);
        }

        .btn-update {
            background: linear-gradient(135deg, var(--esports-primary), var(--esports-secondary));
            border: none;
            color: white;
            font-weight: 700;
            padding: 15px 40px;
            border-radius: 30px;
            transition: all 0.4s ease;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            width: 100%;
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 212, 255, 0.2);
        }

        .btn-update::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-update:hover::before {
            left: 100%;
        }

        .btn-update:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 212, 255, 0.4);
            color: white;
        }

        .btn-update:active {
            transform: translateY(-1px) scale(1.01);
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

        .alert {
            border-radius: 20px;
            border: none;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            font-weight: 600;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 2px solid rgba(40, 167, 69, 0.3);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 2px solid rgba(220, 53, 69, 0.3);
        }

        .participant-info {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--esports-primary);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .participant-info::before {
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

        .participant-info h6 {
            color: var(--esports-primary);
            font-family: 'Orbitron', monospace;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .participant-info p {
            color: rgba(248, 249, 250, 0.9);
            margin: 0;
            font-size: 1rem;
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

        .loading {
            display: none;
        }

        .loading.show {
            display: inline-block;
        }

        @media (max-width: 768px) {
            .edit-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .edit-title {
                font-size: 2.5rem;
            }
            
            .edit-container {
                padding: 80px 0 30px;
            }
        }

        @media (max-width: 576px) {
            .edit-title {
                font-size: 2rem;
            }

            .participant-info {
                padding: 1.5rem;
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

    <!-- Edit Section -->
    <section class="edit-container">
        <div class="container">
            <div class="edit-header">
                <h1 class="edit-title">Edit Participant</h1>
                <p class="edit-subtitle">Update participant scores and statistics</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-12">
                    <a href="view_participants_edit_delete.php" class="back-link">
                        <i class="fas fa-arrow-left me-2"></i>Back to Participants
                    </a>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success_message): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
                            <br><small>Redirecting back to participants list...</small>
                        </div>
                    <?php endif; ?>

                    <?php if ($participant && !$success_message): ?>
                        <div class="participant-info">
                            <h6><i class="fas fa-user me-2"></i>Participant Information</h6>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($participant['firstname'] . ' ' . $participant['surname']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($participant['email']); ?></p>
                            <p><strong>Current Kills:</strong> <?php echo $participant['kills'] ?? 0; ?> | <strong>Current Deaths:</strong> <?php echo $participant['deaths'] ?? 0; ?></p>
                        </div>

                        <div class="edit-card">
                            <form action="edit_participant.php" method="POST" id="editForm">
                                <div class="form-group">
                                    <label class="form-label" for="firstname">
                                        <i class="fas fa-user me-2"></i>First Name
                                    </label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" 
                                           value="<?php echo htmlspecialchars($participant['firstname']); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="surname">
                                        <i class="fas fa-user me-2"></i>Last Name
                                    </label>
                                    <input type="text" class="form-control" id="surname" name="surname" 
                                           value="<?php echo htmlspecialchars($participant['surname']); ?>" disabled>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="kills">
                                        <i class="fas fa-crosshairs me-2"></i>Kills
                                    </label>
                                    <input type="number" class="form-control" id="kills" name="kills" 
                                           value="<?php echo $participant['kills'] ?? 0; ?>" min="0" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="deaths">
                                        <i class="fas fa-skull me-2"></i>Deaths
                                    </label>
                                    <input type="number" class="form-control" id="deaths" name="deaths" 
                                           value="<?php echo $participant['deaths'] ?? 0; ?>" min="0" required>
                                </div>

                                <input type="hidden" name="id" value="<?php echo $participant['id']; ?>">

                                <button type="submit" class="btn btn-update">
                                    <i class="fas fa-save me-2"></i>
                                    <span class="btn-text">Update Participant</span>
                                    <i class="fas fa-spinner fa-spin loading"></i>
                                </button>
                            </form>
                        </div>
                    <?php elseif (!$participant && !$error_message && !$success_message): ?>
                        <div class="edit-card">
                            <div class="text-center">
                                <i class="fas fa-user-slash" style="font-size: 4rem; color: var(--esports-primary); margin-bottom: 2rem;"></i>
                                <h4>No Participant Selected</h4>
                                <p>Please select a participant to edit from the participants list.</p>
                                <a href="view_participants_edit_delete.php" class="btn btn-update">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Participants
                                </a>
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
        // Form submission with loading state
        document.getElementById('editForm')?.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.btn-update');
            const btnText = submitBtn.querySelector('.btn-text');
            const loading = submitBtn.querySelector('.loading');
            
            // Show loading state
            btnText.style.display = 'none';
            loading.classList.add('show');
            submitBtn.disabled = true;
            
            // Re-enable after 5 seconds (in case of issues)
            setTimeout(() => {
                btnText.style.display = 'inline';
                loading.classList.remove('show');
                submitBtn.disabled = false;
            }, 5000);
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

        // Form validation
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value < 0) {
                    this.value = 0;
                }
            });
        });

        // Auto-hide success message after redirect
        <?php if ($success_message): ?>
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
            }
        }, 1500);
        <?php endif; ?>
    </script>
</body>
</html>
