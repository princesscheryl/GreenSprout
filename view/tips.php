<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gardening Tips - GreenSprout</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../assets/css/tips_styles.css">
    <script defer src="assets/js/tips.js"></script>
</head>
<body>
    <header>
        <a href="index2.php" class="logo">GREEN SPROUT</a>
        <ul class="navlist">
            <li><a href="index2.php">Home</a></li>
            <li><a href="seed_feed.php">Seeds</a></li>
            <li><a href="tips.php" class="active">Gardening Tips</a></li>
            <li><a href="community.php">Community</a></li>
        </ul>
        <div class="nav-button">
            <button class="btn"><a href="user_register.php">LOG IN</a></button>
            <button class="btn"><a href="userregister.php">SIGN UP</a></button>
        </div>
    </header>

    <main>
        <section class="intro">
            <div class="intro-text">
                <h3>Welcome to Gardening Tips</h3>
                <h4>Discover insights to make your garden thrive.</h4>
            </div>
        </section>

        <section class="search-section">
            <div class="search-container">
                <input type="text" id="tipSearch" placeholder="Search for gardening tips..." 
                       onkeyup="searchTips(this.value)">
                <i class='bx bx-search-alt'></i>
            </div>
        </section>

        <section id="tips">
            <h2>Explore Gardening Tips</h2>
            <div class="tips-category">
                <div class="category">
                    <h3 onclick="toggleCategory(this)">Watering</h3>
                    <div class="tips-list hidden">
                        <p>Water early in the morning to reduce evaporation <span class="tooltip" data-tooltip="Evaporation is highest during midday due to heat.">[?]</span></p>
                        <p>Check soil moisture before watering to avoid overwatering</p>
                        <p>Use a moisture meter for accurate watering needs</p>
                        <p>Group plants with similar water needs together</p>
                    </div>
                </div>
                <div class="category">
                    <h3 onclick="toggleCategory(this)">Soil Care</h3>
                    <div class="tips-list hidden">
                        <p>Maintain the perfect soil composition to nourish your garden</p>
                        <p>Test soil pH regularly <span class="tooltip" data-tooltip="Most plants prefer pH between 6.0 and 7.0">[?]</span></p>
                        <p>Add compost to improve soil nutrients</p>
                        <p>Avoid tilling soil too often to preserve beneficial organisms</p>
                    </div>
                </div>
                <div class="category">
                    <h3 onclick="toggleCategory(this)">Seasonal Tips</h3>
                    <div class="tips-list hidden">
                        <p>Prepare your garden for every season with ease</p>
                        <p>Monitor local frost dates for planting schedules</p>
                        <p>Apply season-appropriate fertilizers</p>
                        <p>Protect plants from extreme weather conditions</p>
                    </div>
                </div>
            </div>

            <div id="load-more-container">
                <button id="load-more" onclick="loadMoreTips()">Load More Tips</button>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 GreenSprout. All rights reserved.</p>
    </footer>
</body>
</html>
