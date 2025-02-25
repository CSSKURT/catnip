<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db.php';

// Fetch user information
$stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CatNip</title>
    <link rel="stylesheet" href="style/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="bg-warning fixed-top">
        <div class="container d-flex justify-content-between align-items-center p-2">
            <h1 style="font-family: 'Pacifico', cursive; color: white; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">CatNip</h1>
            <div>
                <a href="logout.php" class="btn btn-light">Logout</a>
            </div>
        </div>
    </header>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar ml-4 mt-5">
            <!-- Profile Section -->
            <div class="profile mb-4">
                <?php
                // Check if profile picture exists and is not empty
                if (!empty($user['profile_picture']) && file_exists($user['profile_picture'])) {
                    $profile_pic = $user['profile_picture'];
                } else {
                    // Set default placeholder based on gender
                    $profile_pic = ($user['gender'] === 'male') ? 
                        'assets/male_ph.png' : 
                        'assets/female_ph.jpg';
                }
                ?>
                <div class="profile-pic-container">
                    <img src="<?php echo htmlspecialchars($profile_pic); ?>" 
                         alt="<?php echo htmlspecialchars($user['username']); ?>'s Profile" 
                         class="profile-pic"
                         onerror="this.src='assets/default_ph.png'">
                </div>
                <p class="mt-2">Hello, <span id="username"><?php echo htmlspecialchars($user['username']); ?></span>!</p>
            </div>

            <!-- Goals Section -->
            <div class="goals-section">
                <h3 class="mb-3">Your Goal</h3>
                <?php
                // Fetch user's goal
                $stmt = $pdo->prepare("SELECT * FROM Goals WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1");
                $stmt->execute([':user_id' => $_SESSION['user_id']]);
                $goal = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($goal): 
                    $percentage = ($goal['current_amount'] / $goal['target_amount']) * 100;
                ?>
                    <div class="current-goal p-3 bg-light rounded">
                        <p>Target: <span id="goalTarget"><?php echo number_format($goal['target_amount'], 2); ?></span></p>
                        <p>Current: <span id="goalCurrent"><?php echo number_format($goal['current_amount'], 2); ?></span></p>
                        <p>Progress: <span id="goalProgress"><?php echo number_format($percentage, 1); ?></span>%</p>
                        <p>Deadline: <span id="goalDeadline"><?php echo date('M d, Y', strtotime($goal['deadline'])); ?></span></p>
                    </div>
                <?php endif; ?>
                <div class="position-relative">
                    <button class="set-goal-btn btn btn-primary mt-3">Set New Goal</button>
                </div>
            </div>

            <!-- Export Button -->
            <button class="export-btn btn btn-success mt-auto">Export Savings</button>
        </div>

        <!-- Main Content -->
        <div class="main-content container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!-- Dashboard Content Container -->
                    <div class="dashboard-content-container mt-5 p-4">
                        <!-- Cat Name Input -->
                        <div class="cat-name-container text-center mb-4">
                            <input type="text" class="cat-name-input form-control w-50 mx-auto" placeholder="Name your cat">
                        </div>

                        <!-- Growing Cat Image -->
                        <div class="cat-container text-center">
                            <?php
                            // Calculate cat stage based on percentage
                            $catStage = 1;
                            if ($percentage >= 25) $catStage = 2;
                            if ($percentage >= 50) $catStage = 3;
                            if ($percentage >= 75) $catStage = 4;
                            ?>
                            <img src="assets/cat<?php echo $catStage; ?>.png" alt="Growing Cat" class="cat-image mb-3">
                            
                            <div class="progress-container mb-3">
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: <?php echo $percentage; ?>%" 
                                         aria-valuenow="<?php echo $percentage; ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <p class="progress-text mt-2">
                                    $<?php echo number_format($goal['current_amount'], 2); ?>/$<?php echo number_format($goal['target_amount'], 2); ?>
                                </p>
                            </div>

                            <!-- Feed Button and Modal -->
                            <div class="feed-container position-relative">
                                <button class="feed-btn btn btn-warning">Feed</button>
                                <div class="feed-modal">
                                    <div class="form-group">
                                        <input type="number" class="feed-amount form-control" placeholder="Enter amount">
                                    </div>
                                    <button class="submit-feed btn btn-primary">Feed</button>
                                </div>
                            </div>
                        </div>

                        <!-- Money History -->
                        <div class="money-history mt-4">
                            <h3>Money History</h3>
                            <div class="list-group">
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM MoneyHistory WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 5");
                                $stmt->execute([':user_id' => $_SESSION['user_id']]);
                                while ($transaction = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span><?php echo htmlspecialchars($transaction['description']); ?></span>
                                            <span class="badge badge-primary badge-pill">
                                                $<?php echo number_format($transaction['amount'], 2); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            
                            <!-- Start Over Button -->
                            <div class="text-center mt-4">
                                <button class="btn btn-danger" id="startOverBtn" data-toggle="modal" data-target="#startOverModal">
                                    Start Over
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-warning fixed-bottom py-2">
        <p class="text-center mb-0">&copy; 2025 CatNip. All rights reserved.</p>
    </footer>

    <!-- Add this temporarily for debugging -->
    <?php if (isset($_SESSION['debug'])): ?>
    <div style="display:none">
        Debug Info:
        <pre>
        <?php
        echo "User Gender: " . htmlspecialchars($user['gender']) . "\n";
        echo "Profile Picture Path: " . htmlspecialchars($profile_pic) . "\n";
        echo "File Exists: " . (file_exists($profile_pic) ? 'Yes' : 'No') . "\n";
        ?>
        </pre>
    </div>
    <?php endif; ?>

    <!-- Start Over Confirmation Modal -->
    <div class="modal fade" id="startOverModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Reset</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to start over? This will:</p>
                    <ul>
                        <li>Reset your current goal to 0</li>
                        <li>Clear your money history</li>
                        <li>Reset your cat's progress</li>
                    </ul>
                    <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmStartOver">Yes, Start Over</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Move this outside the sidebar, right before the closing body tag -->
    <div class="set-goal-modal">
        <h2>Update Goal</h2>
        <form id="updateGoalForm">
            <div class="form-group">
                <label>Target Amount</label>
                <input type="number" id="newTarget" required>
            </div>
            <div class="form-group">
                <label>Current Amount</label>
                <input type="number" id="newCurrent" required>
            </div>
            <div class="form-group">
                <label>Deadline</label>
                <input type="date" id="newDeadline" required>
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn btn-secondary" id="clearGoalForm">Clear</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>