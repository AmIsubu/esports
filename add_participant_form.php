<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Participant - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Exo 2', sans-serif; background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%); color: #f8f9fa; min-height: 100vh; }
        .navbar { background: rgba(10, 10, 10, 0.95) !important; border-bottom: 2px solid #00d4ff; }
        .navbar-brand { font-family: 'Orbitron', monospace; font-weight: 900; font-size: 1.8rem; color: #00d4ff !important; }
        .add-container { padding: 100px 0 50px; }
        .add-card { background: rgba(26, 26, 26, 0.9); border: 2px solid #00d4ff; border-radius: 20px; padding: 3rem; }
        .add-title { font-family: 'Orbitron', monospace; font-weight: 900; font-size: 2.5rem; background: linear-gradient(45deg, #00d4ff, #ff6b35); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-align: center; margin-bottom: 2rem; }
        .form-label { color: #00d4ff; font-weight: 600; margin-bottom: 0.5rem; font-size: 1.1rem; }
        .form-control { background: rgba(26, 26, 26, 0.8); border: 2px solid rgba(0, 212, 255, 0.3); border-radius: 10px; color: #f8f9fa; padding: 12px 15px; font-size: 1rem; }
        .form-control:focus { background: rgba(26, 26, 26, 0.9); border-color: #00d4ff; box-shadow: 0 0 20px rgba(0, 212, 255, 0.2); color: #f8f9fa; }
        .btn-esports { background: linear-gradient(45deg, #00d4ff, #ff6b35); border: none; color: white; font-weight: 600; padding: 15px 40px; border-radius: 25px; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 1px; font-size: 1.1rem; width: 100%; }
        .btn-esports:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0, 212, 255, 0.3); color: white; }
        .back-link { color: #00d4ff; text-decoration: none; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; margin-bottom: 2rem; }
        .back-link:hover { color: #ff6b35; transform: translateX(-5px); }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="admin_menu.php">
            <i class="fas fa-gamepad me-2"></i>UK E-Sports League
        </a>
    </div>
</nav>
<section class="add-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <a href="view_participants_edit_delete.php" class="back-link">
                    <i class="fas fa-arrow-left me-2"></i>Back to Manage Participants
                </a>
                <div class="add-card">
                    <h1 class="add-title">Add New Participant</h1>
                    <form action="add_participant.php" method="POST">
                        <div class="mb-3">
                            <label for="firstname" class="form-label"><i class="fas fa-user me-2"></i>First Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter first name" required>
                        </div>
                        <div class="mb-3">
                            <label for="surname" class="form-label"><i class="fas fa-user me-2"></i>Last Name</label>
                            <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter last name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" required>
                        </div>
                        <button type="submit" class="btn btn-esports"><i class="fas fa-user-plus me-2"></i>Add Participant</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
