<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREEN SPROUT</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        * {
            padding: 0px;
            margin: 0px;
            box-sizing: border-box;
            list-style: none;
            text-decoration: none;
        }
        header{
            position: fixed;
            right: 0;
            top: 0;
            z-index: 1000;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 33px 9%;
            background: transparent;
        }
        body{
            background: rgb(235, 229, 197);
        }
        .logo{
            font-family: 'Helvetica', sans-serif;
            font-size: 40px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #465644;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4); 
        }
        .navlist{
            display: flex;
        }
        .navlist a{
            color: white;
            margin-left: 60px;
            font-size: 21px;
            font-weight: 600;
            border-bottom: 2px solid transparent;
            transition: all .55s ease;
        }
        .navlist a:hover{
            border-bottom: 2px solid white;
        }
        .box{
            max-width: 400px;
            width: 100%;
        }
        .box form{
            position: relative;
        }
        .search-box {
            position: relative;
            height: 50px;
            max-width: 380px;
            margin: auto;
        }

        .search-box input {
            position: absolute;
            height: 100%;
            width: 100%;
            border-radius: 50px;
            background: white;
            outline: none;
            border: none;
            padding-left: 20px;
            padding-right: 60px;
            font-size: 18px;
        }

        .search-box .icon {
            position: absolute;
            right: -10px;
            top: 0;
            width: 50px;
            background: #465644;
            height: 100%;
            text-align: center;
            line-height: 50px;
            color: white;
            font-size: 20px;
            border-radius: 50%;
            cursor: pointer;
            border: none;
            z-index: 1;
        }
        .clear-button {
            position: absolute;
            right: 50px;
            top: 0;
            width: 50px;
            background: #ccc;
            height: 100%;
            border: none;
            cursor: pointer;
            text-align: center;
            line-height: 50px;
            color: white;
            font-size: 20px;
            display: none;
        }
        .clear-button:hover {
            background: #999;
        }
        .search-filters {
            margin-top: 10px;
            text-align: center;
        }
        .filter-select {
            padding: 8px 15px;
            border-radius: 15px;
            border: 1px solid #465644;
            background: white;
            color: #465644;
            font-size: 14px;
            cursor: pointer;
            outline: none;
            transition: all 0.3s ease;
        }
        .filter-select:hover {
            background: #f5f5f5;
        }
        .card-container{
            max-width: 80%;
            margin: 150px auto 50px auto; 
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 25px;
        }
        .card{
            flex: 1 1 calc(30% - 25px); 
            max-width: calc(30% - 25px);
            background-color: aliceblue;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            display: flex;
            flex-direction: column; 
        }
        .card:hover{
            transform: scale(1.05);
        }
        .card img{
            width: 100%;
            height: 200px; 
            object-fit: cover; 
        }
        .card-content{
            padding: 20px;
            flex-grow: 1; 
        }
        .card-content h3{
            color: rgb(0, 0, 0);
            font-size: 27px;
            margin-bottom: 10px;
        }
        .description{
            color: rgb(0, 0, 0);
            font-size: 17px;
            line-height: 1.6;
            font-family: "Lato", sans-serif;
        }
        .seasonality {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        .seasonality-title {
            font-weight: 600;
            color: #465644;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .seasonality-text {
            color: #666;
            font-size: 14px;
        }
        .highlight {
            background-color: rgba(70, 86, 68, 0.1);
            padding: 2px 0;
            border-radius: 2px;
        }
        .skill-level {
            display: inline-block;
            margin-top: 15px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #465644;
            color: white;
        }
        @media screen and (max-width: 768px){
            .navlist{
                flex-direction: column;
                align-items: flex-start;
            }
            .box{
                margin-top: 20px; 
            }
            .card{
                flex: 1 1 100%;
                max-width: 100%;
            }
        }
        footer {
            margin-top: 40px;
            padding: 10px 0;
            background: #2e392d;
            color: #fff;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <a href="index2.php" class="logo">GREEN SPROUT</a>
        <ul class="navlist">
            <li><a href="index2.php">Home</a></li>
            <li><a href="seed_feed.php">Seeds</a></li>
            <li><a href="user_register.php?action=login">Community</a></li>
        </ul>
        <div class="box">
            <form action="#" method="GET">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search seeds...">
                    <button type="button" class="clear-button" id="clearSearch" title="Clear search">
                        <i class="ri-close-line"></i>
                    </button>
                    <button type="submit" class="icon" title="Search">
                        <i class="ri-search-2-line"></i>
                    </button>
                </div>
            </form>
            <div class="search-filters">
                <select id="filterCategory" class="filter-select">
                    <option value="all">Search in: All</option>
                    <option value="name">Name</option>
                    <option value="description">Description</option>
                    <option value="seasonality">Seasonality</option>
                    <option value="skill">Skill Level</option>
                </select>
            </div>
        </div>
    </header>

    <div class="card-container">
        <div class="card">
            <img src="../assets/images/sunflower1.jpg" alt="Sunflower Seeds">
            <div class="card-content">
                <h3>Sunflower</h3>
                <div class="description">
                    Tall, sun-loving annual that produces large, bright yellow flowers with edible seeds. Perfect for adding height to gardens and attracting pollinators.
                </div>
                <div class="seasonality">
                    <div class="seasonality-title">Seasonality</div>
                    <div class="seasonality-text">Spring to Late Summer</div>
                </div>
                <div class="skill-level">Beginner</div>
            </div>
        </div>

        <div class="card">
            <img src="../assets/images/basil.jpg" alt="Basil Seeds">
            <div class="card-content">
                <h3>Basil</h3>
                <div class="description">
                    Aromatic herb with tender green leaves, essential for culinary use. Grows well in containers and gardens with regular harvesting.
                </div>
                <div class="seasonality">
                    <div class="seasonality-title">Seasonality</div>
                    <div class="seasonality-text">Spring to Fall</div>
                </div>
                <div class="skill-level">Beginner</div>
            </div>
        </div>

        <div class="card">
            <img src="../assets/images/snappea.jpg" alt="Snap Pea Seeds">
            <div class="card-content">
                <h3>Snap Pea</h3>
                <div class="description">
                    Crisp, sweet climbing vines that produce edible pods. Great for vertical gardening and can be eaten straight from the vine..
                </div>
                <div class="seasonality">
                    <div class="seasonality-title">Seasonality</div>
                    <div class="seasonality-text">Early Spring to Early Summer, Fall in some climate</div>
                </div>
                <div class="skill-level">Beginner</div>
            </div>
        </div>
        <div class="card">
            <img src="../assets/images/marigold.jpg" alt="Marigold Seeds">
            <div class="card-content">
                <h3>Lavender</h3>
                <div class="description">
                    Bright, cheerful flowers that naturally repel garden pests and provide continuous blooms. Easy to grow from seeds.
                </div>
                <div class="seasonality">
                    <div class="seasonality-title">Seasonality</div>
                    <div class="seasonality-text">Spring through Fall</div>
                </div>
                <div class="skill-level">Beginner</div>
            </div>
        </div>
        <div class="card">
            <img src="../assets/images/zinnia.jpg" alt="Zinnia Seeds">
            
            <div class="card-content">
                <h3>Zinnia</h3>
                
                <div class="description">
                    Long-lasting blooms in vibrant colors, excellent for cut flowers and attracting butterflies. Heat tolerant and continuous blooming.
                </div>
                
                <div class="seasonality">
                    <div class="seasonality-title">
                        Seasonality
                    </div>
                    <div class="seasonality-text">
                        Late Spring through Winter
                    </div>
                </div>
                
                <div class="skill-level">
                    Beginner
                </div>
            </div>
        </div>
        <div class="card">
            <img src="../assets/images/tomato.jpg" alt="Cherry Tomato Seeds">
            
            <div class="card-content">
                <h3>Cherry Tomato</h3>
                
                <div class="description">
                    Compact plants perfect for containers and small gardens, producing sweet bite-sized fruits that are perfect for salads and snacking.
                </div>
                
                <div class="seasonality">
                    <div class="seasonality-title">
                        Seasonality
                    </div>
                    <div class="seasonality-text">
                        Late Spring through Fall
                    </div>
                </div>
                
                <div class="skill-level">
                    Beginner
                </div>
            </div>
        </div>
            <div class="card">
                <img src="../assets/images/lavender.jpg" alt="Lavender Seeds">
                <div class="card-content">
                    <h3>Lavender</h3>
                    <div class="description">
                        Fragrant Mediterranean herb with silvery-green foliage and purple spikes. Needs well-draining soil and full sun.
                    <div class="seasonality">
                        <div class="seasonality-title">Seasonality</div>
                        <div class="seasonality-text">Spring to Mid-Summer</div>
                    </div>
                    <div class="skill-level">Intermediate</div>
                </div>
                </div>
            </div>
            <div class="card">
                <img src="../assets/images/bell.jpg" alt="Bell Pepper Seeds">
                <div class="card-content">
                    <h3>Bell Pepper</h3>
                    <div class="description">
                        Sweet, crunchy vegetables that change colors as they ripen. Requires consistent temperatures and proper feeding.
    
                    <div class="seasonality">
                        <div class="seasonality-title">Seasonality</div>
                        <div class="seasonality-text">Late Spring through Fall</div>
                    </div>
                    <div class="skill-level">Intermediate</div>
                </div>
                </div>
            </div>
            <div class="card">
                <img src="../assets/images/dahlia.jpg" alt="Dahlia Seeds">
                <div class="card-content">
                    <h3>Dahlia</h3>
                    <div class="description">
                        Large, stunning flowers in various forms and colors. Tubers need winter storage in cold climates.
                    <div class="seasonality">
                        <div class="seasonality-title">Seasonality</div>
                        <div class="seasonality-text">Mid-Summer through Winter</div>
                    </div>
                    <div class="skill-level">Intermediate</div>
                </div>
                </div>
            </div>
            <div class="card">
                <img src="../assets/images/mint.jpg" alt="Mint Seeds">
                <div class="card-content">
                    <h3>Mint</h3>
                    <div class="description">
                        Vigorous herb that spreads rapidly. Best grown in containers to prevent garden takeover. Many varieties available.
                    <div class="seasonality">
                        <div class="seasonality-title">Seasonality</div>
                        <div class="seasonality-text">Spring through Fall</div>
                    </div>
                    <div class="skill-level">Intermediate</div>
                </div>
                </div>
            </div>
            <div class="card">
                <img src="../assets/images/echinacea.jpg" alt="Echinacea Seeds">
                <div class="card-content">
                    <h3>Echinacea</h3>
                    <div class="description">
                        Sturdy native flower with medicinal properties. Attracts pollinators and provides late-season color.
                    <div class="seasonality">
                        <div class="seasonality-title">Seasonality</div>
                        <div class="seasonality-text">Mid-Summer through Fall</div>
                    </div>
                    <div class="skill-level">Intermediate</div>
                </div>
                </div>
            </div>
            <div class="card">
                <img src="../assets/images/bushbean.jpg" alt="Bush Bean Seeds">
                <div class="card-content">
                    <h3>Bush Bean</h3>
                    <div class="description">
                        Productive vegetables that don't require support. Need consistent moisture and proper spacing.
                    <div class="seasonality">
                        <div class="seasonality-title">Seasonality</div>
                        <div class="seasonality-text">Late Spring through Summer</div>
                    </div>
                    <div class="skill-level">Intermediate</div>
                </div>
                </div>
            </div>

        <!-- Add more cards following the same structure -->
    </div>
    <footer>
        <p>&copy; 2024 GreenSprout. All rights reserved.</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get references to DOM elements
            const searchInput = document.getElementById('searchInput');
            const filterCategory = document.getElementById('filterCategory');
            const clearSearch = document.getElementById('clearSearch');
            const searchForm = document.querySelector('.box form');
            const cards = document.querySelectorAll('.card');

            // Function to perform the search and filtering
            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase();
                const filterValue = filterCategory.value;
                let visibleCards = 0;

                // Clear existing highlights
                document.querySelectorAll('.highlight').forEach(el => {
                    const parent = el.parentNode;
                    parent.textContent = parent.textContent;
                });

                // Show/hide clear button based on search input
                clearSearch.style.display = searchTerm ? 'block' : 'none';

                cards.forEach(card => {
                    const content = card.querySelector('.card-content');
                    const name = content.querySelector('h3').textContent.toLowerCase();
                    const description = content.querySelector('.description').textContent.toLowerCase();
                    const seasonality = content.querySelector('.seasonality-text').textContent.toLowerCase();
                    const skillLevel = content.querySelector('.skill-level').textContent.toLowerCase();

                    let match = false;

                    // Determine match based on filter category
                    switch(filterValue) {
                        case 'name':
                            match = name.includes(searchTerm);
                            if (match && searchTerm) highlightText(content.querySelector('h3'), searchTerm);
                            break;
                        case 'description':
                            match = description.includes(searchTerm);
                            if (match && searchTerm) highlightText(content.querySelector('.description'), searchTerm);
                            break;
                        case 'seasonality':
                            match = seasonality.includes(searchTerm);
                            if (match && searchTerm) highlightText(content.querySelector('.seasonality-text'), searchTerm);
                            break;
                        case 'skill':
                            match = skillLevel.includes(searchTerm);
                            if (match && searchTerm) highlightText(content.querySelector('.skill-level'), searchTerm);
                            break;
                        default: // 'all'
                            match = name.includes(searchTerm) || 
                                description.includes(searchTerm) || 
                                seasonality.includes(searchTerm) || 
                                skillLevel.includes(searchTerm);
                            if (match && searchTerm) {
                                if (name.includes(searchTerm)) highlightText(content.querySelector('h3'), searchTerm);
                                if (description.includes(searchTerm)) highlightText(content.querySelector('.description'), searchTerm);
                                if (seasonality.includes(searchTerm)) highlightText(content.querySelector('.seasonality-text'), searchTerm);
                                if (skillLevel.includes(searchTerm)) highlightText(content.querySelector('.skill-level'), searchTerm);
                            }
                    }

                    // Show or hide card based on match
                    card.style.display = match ? '' : 'none';
                    if (match) visibleCards++;
                });

                // Handle no results message
                updateNoResultsMessage(visibleCards);
            }

            // Function to highlight matching text
            function highlightText(element, searchTerm) {
                const text = element.textContent;
                const regex = new RegExp(`(${searchTerm})`, 'gi');
                element.innerHTML = text.replace(regex, '<span class="highlight">$1</span>');
            }

            // Function to update the no results message
            function updateNoResultsMessage(visibleCards) {
                const container = document.querySelector('.card-container');
                let message = container.querySelector('.no-results');

                if (visibleCards === 0) {
                    if (!message) {
                        message = document.createElement('div');
                        message.className = 'no-results';
                        message.textContent = 'No seeds found matching your search criteria';
                        container.appendChild(message);
                    }
                } else if (message) {
                    message.remove();
                }
            }

            // Handle form submission
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performSearch();
            });

            // Handle clear button click
            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                clearSearch.style.display = 'none';
                performSearch();
                searchInput.focus();
            });

            // Handle search input changes
            searchInput.addEventListener('input', function() {
                performSearch();
            });

            // Handle filter category changes
            filterCategory.addEventListener('change', function() {
                performSearch();
            });

            // Initialize search on page load
            performSearch();
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Get the search input
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', searchPosts);
            }
        });

        function searchPosts() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const posts = document.querySelectorAll('.post-card');

            posts.forEach(post => {
                const title = post.querySelector('.post-title').textContent.toLowerCase();
                const content = post.querySelector('.post-content').textContent.toLowerCase();
                const username = post.querySelector('.post-author').textContent.toLowerCase();

                if (title.includes(searchTerm) || 
                    content.includes(searchTerm) || 
                    username.includes(searchTerm)) {
                    post.style.display = '';
                } else {
                    post.style.display = 'none';
                }
            });
        }

        // Add this new code for search icon
        document.querySelector('.search-icon').addEventListener('click', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                // Toggle search input visibility
                if (searchInput.style.display === 'none' || searchInput.style.display === '') {
                    searchInput.style.display = 'block';
                    searchInput.focus();
                } else {
                    searchInput.style.display = 'none';
                    searchInput.value = ''; // Clear search when hiding
                    searchPosts(); // Reset search results
                }
            }
        });
    </script>
</body>
</html>