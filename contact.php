<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Contact Us - Academic Resource Portal</title>
</head>
<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    
    <main>
        <section class="form-container">
            <form action="https://formspree.io/f/xjkbaglg" method="POST">
                <h2>Get in Touch</h2>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="button">Send</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Academic Resource Portal. All Rights Reserved.</p>
    </footer>
   
</body>
</html>


