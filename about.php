<?php
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>About Us - Academic Resource Portal</title>
    <script>
        // FAQ toggle script
        document.addEventListener('DOMContentLoaded', function () {
            const faqs = document.querySelectorAll('.faq');
            faqs.forEach(faq => {
                faq.querySelector('h4').addEventListener('click', function () {
                    faq.classList.toggle('active');
                });
            });
        });
    </script>
</head>
<body>
<header>
        <?php include 'includes/header.php'; ?>
</header>
    <main>
        <!-- About Us Section -->
        <div class="about-container">
            <div class="about-image">
                <img src="https://saifali.sirv.com/1up/stand/stand.spin?image=20&gif.lossy=0&w=500&h=500" alt="About Us">
            </div>
            <div class="about-content">
                <h2>About Us</h2>
                <h3>Your Gateway to Academic Success</h3>
                <p>Our portal is designed to provide students, educators, and researchers with the best academic resources available. We believe in the power of knowledge and are committed to making it accessible to everyone. Join us in our mission to make learning a lifelong journey.</p>
            </div>
        </div>

        <!-- Who We Are Section -->
        <div class="team-container">
            <h2>Who We Are</h2>
            <div class="team-card">
                <div class="team-member">
                    <img src="https://saifali.sirv.com/1up/avatar/20.%20Stylish%20Young%20Man.png" alt="Saif">
                    <h3>Saif Ali -- Founder</h3>
                    <p>Full Stack Developer with a passion for building web applications that solve real-world problems.</p>
                    <a href="https://saif.com">View Portfolio</a>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <img src="https://saifali.sirv.com/1up/avatar/1.%20Police.png" alt="saif">
                    <h3>Groot Ali</h3>
                    <p>UI/UX Designer focused on creating intuitive and user-friendly interfaces that enhance the user experience.</p>
                    <a href="https://portfolio.janesmith.com">View Portfolio</a>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-dribbble"></i></a>
                    </div>
                </div>
                <div class="team-member">
                    <img src="https://saifali.sirv.com/1up/avatar/20.%20Stylish%20Young%20Man.png" alt="Johnson">
                    <h3>Johnson</h3>
                    <p>Backend Developer with expertise in database management and server-side logic. Ensuring security and efficiency.</p>
                    <a href="https://portfolio.emilyjohnson.com">View Portfolio</a>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
        </div>

         <!-- FAQ Section -->
        <div class="faq-container">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-list">
                <div class="faq">
                    <h4><i class="fas fa-chevron-right"></i> What is the Academic Resource Portal?</h4>
                    <div class="faq-content">
                        <p>Our portal is a platform designed to provide access to high-quality academic resources for students, educators, and researchers.</p>
                    </div>
                </div>
                <div class="faq">
                    <h4><i class="fas fa-chevron-right"></i> How can I contribute to the portal?</h4>
                    <div class="faq-content">
                        <p>You can contribute by uploading educational materials such as textbooks, research papers, and study guides. Your contributions help enrich the community.</p>
                    </div>
                </div>
                <div class="faq">
                    <h4><i class="fas fa-chevron-right"></i> Is the portal free to use?</h4>
                    <div class="faq-content">
                        <p>Yes, the Academic Resource Portal is completely free for everyone. We believe in making knowledge accessible to all.</p>
                    </div>
                </div>
            <!-- Add more FAQs as needed -->
        </div>
    </main>
            <footer>
        <?php include 'includes/footer.php'; ?>
    </footer>
</body>
</html>



