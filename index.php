<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Car</title>
    <style>
        /* Reset and General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #009579, #006644);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: bold;
            color: #fff;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .hero p {
            font-size: 1.2rem;
            line-height: 1.8;
            margin-bottom: 40px;
            color: rgba(255, 255, 255, 0.9);
        }

        .cta-button {
            display: inline-block;
            padding: 15px 50px;
            font-size: 1.3rem;
            font-weight: bold;
            color: #009579;
            background: #fff;
            border-radius: 16px;
            text-transform: uppercase;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .cta-button:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(255, 255, 255, 0.5);
        }

        .hero .car-image-container {
            margin-top: 40px;
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        }

        .hero .car-image {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 20px;
            transition: transform 0.5s ease, filter 0.5s ease;
        }

        .hero .car-image:hover {
            transform: scale(1.1);
            filter: brightness(1.2);
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(0, 149, 121, 0.7), rgba(0, 0, 0, 0.7));
            z-index: 1;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.8rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .cta-button {
                padding: 12px 30px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <section class="hero">
        <h1>twiter</h1>
        <p>Explore our fleet of premium cars tailored for your business trips, vacations, and adventures. Enjoy comfort, style, and convenience like never before.</p>
        <a href="./pages/login.php" class="cta-button">Start Twitting</a>

        <!-- Car Image -->
        <div class="car-image-container">
        </div>
    </section>
</body>
</html>
