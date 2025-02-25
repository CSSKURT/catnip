<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catnip</title>
    <link rel="stylesheet" href="style/styles.css"> <!-- Custom styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet"> <!-- Cursive font -->
</head>
<body>
    <div class="content-wrapper">
        <header class="bg-warning sticky-top">
            <div class="container d-flex justify-content-between align-items-center p-2">
                <h1 style="font-family: 'Pacifico', cursive; color: white;">CatNip</h1>
                <div class="header-buttons">
                    <button class="btn btn-light rounded-button" id="loginBtn">Login</button>
                    <button class="btn btn-light rounded-button" id="registerBtn">Register</button>
                </div>
            </div>
        </header>

        <main class="d-flex flex-column align-items-center">
            <div class="landing-content">
                <img src="assets/molly.png" alt="Cat Logo" class="cat-logo">
                <div class="text-content">
                    <h2>Purrfect Savings!</h2>
                    <p>Understand expenses better and save efficiently.</p>
                </div>
            </div>
        </main>
    </div>

    <footer class="bg-warning text-center">
        <p class="mb-0">&copy; 2025 CatNip. All rights reserved.</p>
    </footer>

    <!-- Modal for Login -->
    <div id="loginModal" class="modal">
        <div class="modal-wrapper">
            <div class="modal-content">
                <span class="close" id="closeLogin">&times;</span>
                <h2>Login</h2>
                <div class="form-container">
                    <form action="login.php" method="POST" id="login-form">
                        <div class="form-group">
                            <input type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Register -->
    <div id="registerModal" class="modal">
        <div class="modal-wrapper">
            <div class="modal-content">
                <span class="close" id="closeRegister">&times;</span>
                <h2>Register</h2>
                <div class="form-container">
                    <form action="register.php" method="POST" id="register-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" placeholder="Password" required>
                        </div>
                        
                        <div class="form-group gender-selection">
                            <label>Gender:</label>
                            <div class="radio-group">
                                <input type="radio" id="male" name="gender" value="male" required>
                                <label for="male">Male</label>
                                <input type="radio" id="female" name="gender" value="female" required>
                                <label for="female">Female</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Profile Picture (Optional):</label>
                            <input type="file" name="profile_picture" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label>Set Your Savings Goal:</label>
                            <input type="number" name="initial_goal" placeholder="Enter your target amount" required>
                            <input type="date" name="goal_deadline" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="js/scripts.js"></script> <!-- Ensure this is at the end of the body -->
</body>
</html> 