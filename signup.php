<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $profile_picture = $_FILES['profile_picture']['name'];

    $target_dir = "uploads/profile_picture/";
    $target_file = $target_dir . basename($profile_picture);
    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);

    $sql = "INSERT INTO users (name, username, email, phone, profile_picture, password) 
            VALUES ('$name', '$username', '$email', '$phone', '$profile_picture', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: signin.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <title>Sign Up - Academic Resource Portal</title>
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    <main>
        <div class="form-container">
            <form action="signup.php" method="POST" enctype="multipart/form-data">
                <h2>Sign Up</h2>
                <?php if (isset($error)): ?>
                    <p class="message"><?php echo $error; ?></p>
                <?php endif; ?>
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" name="name" id="name" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" name="phone" id="phone" placeholder="Phone Number">
                </div>
                <div class="form-group">
                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" name="profile_picture" id="profile_picture" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>
                <button type="submit" class="button">Sign Up</button>
                <p>Already have an account? <a href="signin.php">Sign In</a></p>
            </form>
        </div>
    </main>
</body>
</html>

