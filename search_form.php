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
    <title>Search Database - UK E-Sports League</title>
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

        .search-container {
            padding: 100px 0 50px;
        }

        .search-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .search-title {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 3rem;
            background: linear-gradient(45deg, var(--esports-primary), var(--esports-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .search-subtitle {
            color: rgba(248, 249, 250, 0.8);
            font-size: 1.2rem;
        }

        .search-card {
            background: rgba(26, 26, 26, 0.9);
            border: 2px solid var(--esports-primary);
            border-radius: 20px;
            padding: 3rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 212, 255, 0.1);
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .search-card::before {
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

        .search-card .card-icon {
            font-size: 3rem;
            color: var(--esports-primary);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .search-card .card-title {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            color: var(--esports-light);
            margin-bottom: 1rem;
            text-align: center;
        }

        .form-label {
            color: var(--esports-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .form-control {
            background: rgba(26, 26, 26, 0.8);
            border: 2px solid rgba(0, 212, 255, 0.3);
            border-radius: 10px;
            color: var(--esports-light);
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(26, 26, 26, 0.9);
            border-color: var(--esports-primary);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
            color: var(--esports-light);
        }

        .form-control::placeholder {
            color: rgba(248, 249, 250, 0.5);
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
            width: 100%;
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

        .search-info {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--esports-primary);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .search-info h6 {
            color: var(--esports-primary);
            font-family: 'Orbitron', monospace;
            margin-bottom: 1rem;
        }

        .search-info p {
            color: rgba(248, 249, 250, 0.8);
            margin: 0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .search-card {
                padding: 2rem;
                margin: 1rem 0;
            }
            
            .search-title {
                font-size: 2.5rem;
            }
            
            .search-container {
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

    <!-- Search Section -->
    <section class="search-container">
        <div class="container">
            <div class="search-header">
                <h1 class="search-title">Search Database</h1>
                <p class="search-subtitle">Find participants and teams in the E-Sports League database</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <a href="admin_menu.php" class="back-link">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>

                    <div class="search-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Search Options</h6>
                        <p>Use the forms below to search for individual participants by name or find teams and their members. All searches are case-insensitive and will return partial matches.</p>
                    </div>

                    <div class="row g-4">
                        <!-- Participant Search -->
                        <div class="col-md-6">
                            <div class="search-card">
                                <div class="card-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h3 class="card-title">Search Participants</h3>
                                
                                <form action="search_result.php" method="POST">
                                    <div class="mb-3">
                                        <label for="firstname_surname" class="form-label">
                                            <i class="fas fa-user me-2"></i>Participant Name
                                        </label>
                                        <input type="text" class="form-control" id="firstname_surname" name="firstname_surname" 
                                               placeholder="Enter first name or surname" required>
                                    </div>
                                    
                                    <input type="hidden" name="participant" value="1">
                                    
                                    <button type="submit" class="btn btn-esports">
                                        <i class="fas fa-search me-2"></i>Search Participants
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Team Search -->
                        <div class="col-md-6">
                            <div class="search-card">
                                <div class="card-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 class="card-title">Search Teams</h3>
                                
                                <form action="search_result.php" method="POST">
                                    <div class="mb-3">
                                        <label for="team" class="form-label">
                                            <i class="fas fa-users me-2"></i>Team Name
                                        </label>
                                        <input type="text" class="form-control" id="team" name="team" 
                                               placeholder="Enter team name" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-esports">
                                        <i class="fas fa-search me-2"></i>Search Teams
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Form enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            const inputs = document.querySelectorAll('.form-control');
            
            // Add focus effects
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });
            
            // Form submission enhancement
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const input = this.querySelector('input[type="text"]');
                    
                    if (!input.value.trim()) {
                        e.preventDefault();
                        alert('Please enter a search term.');
                        input.focus();
                        return;
                    }
                    
                    // Add loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Searching...';
                    submitBtn.disabled = true;
                    
                    // Re-enable after a short delay (in case of navigation issues)
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 3000);
                });
            });
        });
    </script>
</body>
</html>