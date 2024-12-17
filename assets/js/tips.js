// Track how many additional tip sets have been loaded
let additionalTipsLoaded = 0;

// Toggle category expansion/collapse
function toggleCategory(element) {
    const tipsList = element.nextElementSibling;
    tipsList.classList.toggle('hidden');
    
    // Rotate arrow indicator if we add one later
    element.classList.toggle('expanded');
}

// Additional tips data
const additionalTips = [
    {
        category: "Watering",
        tips: [
            "Use mulch to retain soil moisture and reduce watering frequency",
            "Water deeply but less frequently to encourage deep root growth",
            "Install a drip irrigation system for efficient watering",
            "Collect rainwater for sustainable garden maintenance",
            "Water at soil level rather than from above to prevent leaf diseases",
            "Consider using self-watering containers for consistent moisture",
            "Install moisture meters to monitor water needs accurately",
            "Group plants with similar watering needs together"
        ]
    },
    {
        category: "Soil Care",
        tips: [
            "Test soil pH regularly and adjust as needed",
            "Add organic matter like compost every growing season",
            "Use cover crops during off-seasons to improve soil health",
            "Avoid compacting soil by using designated garden paths",
            "Practice crop rotation to maintain soil nutrients",
            "Consider adding beneficial nematodes for soil health",
            "Use mulch to regulate soil temperature",
            "Add worm castings for natural fertilization"
        ]
    },
    {
        category: "Seasonal Tips",
        tips: [
            "Spring: Start seeds indoors 6-8 weeks before last frost",
            "Summer: Mulch to retain moisture and control weeds",
            "Fall: Plant bulbs for spring flowers and clean up debris",
            "Winter: Plan next year's garden and maintain tools",
            "Spring: Prune dead branches from perennials",
            "Summer: Harvest vegetables regularly to encourage production",
            "Fall: Collect seeds from favorite plants for next year",
            "Winter: Start a compost pile with fallen leaves"
        ]
    },
    {
        category: "Pest Control",
        tips: [
            "Encourage beneficial insects like ladybugs and praying mantises",
            "Use companion planting to naturally deter pests",
            "Create physical barriers for vulnerable plants",
            "Practice crop rotation to prevent pest cycles",
            "Use neem oil for organic pest control",
            "Install bird houses to attract natural pest predators",
            "Plant aromatic herbs to repel unwanted insects",
            "Use row covers during peak pest seasons"
        ]
    },
    {
        category: "Plant Care",
        tips: [
            "Prune dead or yellowing leaves regularly",
            "Support climbing plants with appropriate structures",
            "Space plants properly to prevent overcrowding",
            "Monitor for signs of disease or nutrient deficiency",
            "Deadhead flowers to encourage continuous blooming",
            "Stake tall plants before they become too heavy",
            "Clean gardening tools between uses to prevent disease spread",
            "Label plants with variety names and planting dates"
        ]
    },
    {
        category: "Composting",
        tips: [
            "Balance green and brown materials in your compost",
            "Turn compost pile regularly for faster decomposition",
            "Keep compost moist but not waterlogged",
            "Add coffee grounds for nitrogen-rich compost",
            "Avoid composting diseased plants",
            "Include crushed eggshells for calcium",
            "Maintain proper compost temperature for optimal breakdown",
            "Use compost tea for liquid fertilizer"
        ]
    },
    {
        category: "Garden Planning",
        tips: [
            "Map out your garden before planting",
            "Consider sun exposure for each plant's needs",
            "Plan for succession planting of vegetables",
            "Include pollinator-friendly flowers in your design",
            "Create microclimates for sensitive plants",
            "Design efficient irrigation systems",
            "Plan paths for easy garden maintenance",
            "Include vertical growing spaces for small gardens"
        ]
    },
    {
        category: "Sustainable Practices",
        tips: [
            "Create wildlife-friendly garden spaces",
            "Practice water conservation techniques",
            "Use local native plants when possible",
            "Implement natural pest control methods",
            "Start a seed-saving program",
            "Reduce plastic use in gardening",
            "Create habitat for beneficial insects",
            "Use renewable materials for garden structures"
        ]
    }
];

// Function to load more tips
function loadMoreTips() {
    if (additionalTipsLoaded >= additionalTips.length) {
        document.getElementById('load-more').style.display = 'none';
        return;
    }

    const tipsContainer = document.querySelector('.tips-category');
    const newCategory = additionalTips[additionalTipsLoaded];

    const categoryDiv = document.createElement('div');
    categoryDiv.className = 'category';
    categoryDiv.innerHTML = `
        <h3 onclick="toggleCategory(this)">${newCategory.category}</h3>
        <div class="tips-list hidden">
            ${newCategory.tips.map(tip => `<p>${tip}</p>`).join('')}
        </div>
    `;

    tipsContainer.appendChild(categoryDiv);
    additionalTipsLoaded++;
}

// Add search functionality
function searchTips(query) {
    // Get all category containers
    const categories = document.querySelectorAll('.category');
    
    categories.forEach(category => {
        // Get all tip paragraphs within this category
        const tips = category.querySelectorAll('p');
        let hasMatch = false;
        
        // Check each tip in the category
        tips.forEach(tip => {
            if (tip.textContent.toLowerCase().includes(query.toLowerCase())) {
                // Show tips that match the search query
                tip.style.display = 'block';
                hasMatch = true;
            } else {
                // Hide tips that don't match
                tip.style.display = 'none';
            }
        });
        
        // Show/hide the entire category based on whether it has any matching tips
        category.style.display = hasMatch ? 'block' : 'none';
    });
}
