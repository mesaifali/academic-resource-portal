
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>About Us - Academic Resource Portal</title>
    <style>
        /* About Us Section */
        .about-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 100px;
        }
        .about-image {
            flex: 1;
            padding-right: 20px;
        }
        .about-image img {
            width: 100%;
            border-radius: 10px;
        }
        .about-content {
            flex: 1;
            padding-left: 20px;
        }
        .about-content h2 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 10px;
        }
        .about-content h3 {
            font-size: 1.8em;
            color: #555;
            margin-bottom: 20px;
        }
        .about-content p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #555;
        }

      /* Responsive Styles */
        @media (max-width: 768px) {
            .about-container {
                flex-direction: column;
                padding: 40px;
            }
            .about-image {
                padding-right: 0;
                margin-bottom: 20px;
            }
            .about-content {
                padding-left: 0;
            }
            .about-content h2 {
                font-size: 2em;
            }
            .about-content h3 {
                font-size: 1.5em;
            }
            .about-content p {
                font-size: 1em;
            }
        }


        /* Who We Are Section */
        .team-container {
            text-align: center;
            padding: 50px 20px;
        }
        .team-container h2 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 40px;
        }
        .team-card {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        .team-member {
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 300px;
        }
        .team-member img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .team-member h3 {
            font-size: 1.5em;
            color: #333;
        }
        .team-member p {
            font-size: 1em;
            color: #555;
            margin-bottom: 10px;
        }
        .team-member a {
            color: #007bff;
            text-decoration: none;
        }
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 10px;
        }
        .social-icons a {
            color: #555;
            font-size: 1.2em;
            transition: color 0.3s;
        }
        .social-icons a:hover {
            color: #007bff;
        }

 /* FAQ Section */
        .faq-container {
            padding: 50px 20px;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .faq-container h2 {
            text-align: center;
            font-size: 2.5em;
            color: #333;
            margin-bottom: 40px;
        }
        .faq-list {
            width: 80%;
            max-width: 800px;
        }
        .faq {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            padding: 16px;
        }
        .faq h4 {
            font-size: 20px;
            color: #007bff;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 24px;
            margin: 0;
            transition: color 0.3s ease-out;
        }
        .faq h4 i {
            margin-right: 16px;
            font-size: 1.2em;
            transition: transform 0.3s ease-out;
        }
        .faq-content {
            padding: 15px;
            display: none;
        }
        .faq.active h4 {
            color: #0056b3;
        }
        .faq.active h4 i {
            transform: rotate(90deg);
        }
        .faq.active .faq-content {
            display: block;
            max-height: 1000px; /* Adjusts height as per content */
            transition: max-height 0.5s ease-in;
        }
        .faq p {
            font-size: 1em;
            line-height: 1.6;
            color: #555;
        }
    </style>
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
</body>
</html>

<?php
include 'includes/footer.php';
?>
