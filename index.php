<?php
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="icon" type="image/jpg" href="https://saifali.sirv.com/favicon/favicon-32x32.png">

    <title>Academic Resource Portal</title>
    <!-- <script src="//code.tidio.co/lnyjageljxyuxvgexte5odsbsuzoajyx.js" async></script> -->
    <style>
        /* body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
        } */
        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            height: 80vh;
            background-color: var(--color-gray);
        }


        .hero-h1 {
            font-size: 48px;
            font-weight: bold;
            margin: 20px 0;
            max-width: 800px;
        }

        .hero-p {

            margin-bottom: 30px;
            max-width: 600px;
        }

        .home-cta-button {
            position: relative;
            display: inline-block;
            padding: 2px;
            background: linear-gradient(45deg, #ff00ff, #00ffff);
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
            transition: all 0.3s ease;
            color: black;
        }

        .home-cta-button span {
            display: block;
            padding: 13px 28px;
            background-color: #000;
            color: #fff;
            border-radius: 28px;
            transition: all 0.3s ease;
        }

        .home-cta-button::before {
            opacity: 0;
            transition: all 0.3s ease;
        }

        .home-cta-button:hover::before {
            opacity: 1;
            filter: blur(5px);
        }

        .home-cta-button:hover span {
            background-color: rgba(0, 0, 0, 0.8);
        }
    </style>
</head>

<body>
    <header>
        <?php include 'includes/header.php'; ?>
    </header>
    <main>
        <div class="container">
            <!-- How It Works Section -->
            <div class="hero">
                <div class="tag">Made by Student, for Students</div>
                <h1 class="hero-h1">Quality resources shared by the community</h1>
                <p class="hero-p">Explore and share top educational resources.Discover resources, Events, and Course. Join us to access, contribute, and excel in your academic journey.</p>
                <a href="resources.php" class="home-cta-button"><span>Get access to <!--4,958--> resources</span></a>
            </div>

    </main>
    <section class="how-it-works">
        <div class="how-it">
            <div class="header-container">
                <div class="tag">OUR STREAMLINED APPROACH</div>
                <h2 class="how-head">How It Works</h2>
            </div>
            <p class="subtitle">Simplify your academic journey with our efficient three-step process</p>
            <div class="steps">
                <div class="step">
                    <div class="step-icon">
                        <img src="https://saifali.sirv.com/1up/business/MANIK%20-%20Business%20%26%20Teamwork%20Illustration%20Pack-06.png" alt="Sign Up">
                    </div>
                    <h3>Sign Up</h3>
                    <p>Create your free account to get started and unlock a world of academic resources.</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <img src="https://saifali.sirv.com/1up/business/MANIK%20-%20Business%20%26%20Teamwork%20Illustration%20Pack-06.png" alt="Browse Resources">
                    </div>
                    <h3>Browse Resources</h3>
                    <p>Explore our vast library of academic materials tailored to your needs.</p>
                </div>
                <div class="step">
                    <div class="step-icon">
                        <img src="https://saifali.sirv.com/1up/business/MANIK%20-%20Business%20%26%20Teamwork%20Illustration%20Pack-06.png" alt="Download or Share">
                    </div>
                    <h3>Download or Share</h3>
                    <p>Access resources instantly or contribute your own to help fellow students.</p>
                </div>
            </div>
        </div>
    </section>
    <?php include "includes/footer.php"; ?>

</body>

</html>