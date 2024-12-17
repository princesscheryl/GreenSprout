document.addEventListener("DOMContentLoaded", () => {
    console.log("Dashboard JS Loaded");

    initializeSidebar();
    initializeImages();
    initializeWeather();
    initializeReminders();

    // Add Plant Form Submission Logic
    const addPlantForm = document.getElementById('add-plant-form');

    if (addPlantForm) {
        addPlantForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Show loading state
            const submitButton = addPlantForm.querySelector('button[type="submit"]');
            const buttonText = submitButton.querySelector('.button-text');
            const originalButtonText = buttonText.innerHTML;
            buttonText.innerHTML = '<i class="ri-loader-4-line"></i> Adding...';
            submitButton.classList.add('loading');
            submitButton.disabled = true;

            const formData = new FormData(addPlantForm);

            try {
                const response = await fetch('../actions/add_plant.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'include'
                });

                const data = await response.json();
                console.log("Server Response:", data);

                if (data.status === 'success') {
                    showToast('Plant added successfully! 🌱', 'success');
                    closeModal('add-plant-modal');
                    window.location.reload(); // Refresh to show new plant
                } else {
                    showToast(data.message || 'Failed to add plant', 'error');
                }
            } catch (error) {
                console.error('Error adding plant:', error);
                showToast('An error occurred while adding the plant', 'error');
            } finally {
                // Restore button state
                buttonText.innerHTML = originalButtonText;
                submitButton.classList.remove('loading');
                submitButton.disabled = false;
            }
        });
    }

    // Modal Close Handlers
    document.querySelectorAll('.modal .close').forEach(button => {
        button.addEventListener('click', function () {
            this.closest('.modal').style.display = 'none';
        });
    });

    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });

    // Add care log form submission handler
    const careLogForm = document.getElementById('care-log-form');
    if (careLogForm) {
        careLogForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Show loading state
            const submitButton = careLogForm.querySelector('button[type="submit"]');
            const buttonText = submitButton.querySelector('.button-text');
            const originalButtonText = buttonText.innerHTML;
            buttonText.innerHTML = '<i class="ri-loader-4-line"></i> Logging...';
            submitButton.classList.add('loading');
            submitButton.disabled = true;
            
            const formData = new FormData(careLogForm);
            
            try {
                const response = await fetch('../api/dashboard_api.php?action=log_care', {
                    method: 'POST',
                    body: formData,
                    credentials: 'include'
                });
                
                const data = await response.json();
                console.log('Care log response:', data); // Debug log
                
                if (data.status === 'success') {
                    // Update streak display with animation
                    const streakDisplay = document.querySelector('.streak-count span');
                    if (streakDisplay) {
                        streakDisplay.textContent = `${data.streak} days`;
                        streakDisplay.classList.add('streak-updated');
                        setTimeout(() => streakDisplay.classList.remove('streak-updated'), 1000);
                    }
                    
                    // Show success message in a toast
                    showToast('Care logged successfully! 🌱', 'success');
                    
                    // Refresh reminders
                    initializeReminders();
                    
                    // Clear form and close modal
                    careLogForm.reset();
                    closeModal('care-log-modal');
                    
                    // Update the plant's last care date in the UI if it exists
                    const plantId = formData.get('plant_id');
                    const plantElement = document.querySelector(`[data-plant-id="${plantId}"]`);
                    if (plantElement) {
                        const dateElement = plantElement.querySelector('.last-care-date');
                        if (dateElement) {
                            dateElement.textContent = 'Just now';
                            dateElement.classList.add('recently-updated');
                        }
                    }
                } else {
                    showToast(data.message || 'Failed to log care', 'error');
                }
            } catch (error) {
                console.error('Error logging care:', error);
                showToast('An error occurred while logging care', 'error');
            } finally {
                // Restore button state
                buttonText.innerHTML = originalButtonText;
                submitButton.classList.remove('loading');
                submitButton.disabled = false;
            }
        });
    }

    document.querySelector('.log-care-btn').addEventListener('click', async function() {
        try {
            // Update streak directly
            const response = await fetch('../actions/update_streak.php');
            const data = await response.json();
            console.log('Streak Update Response:', data);

            if (data.status === 'success') {
                // Update streak display
                const streakElement = document.querySelector('.streak-count span');
                if (streakElement) {
                    streakElement.textContent = `${data.streak} days`;
                }
                showToast('Care logged successfully!', 'success');
            } else {
                console.error('Streak update failed:', data.message);
                showToast('Failed to update streak', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error updating streak', 'error');
        }
    });
});

// ------------------- Reminders -------------------
async function initializeReminders() {
    const remindersList = document.querySelector("#reminders-list");
    if (!remindersList) return;

    try {
        const response = await fetch('../api/dashboard_api.php?action=reminders', {
            method: 'GET',
            credentials: 'include'
        });
        const data = await response.json();

        if (data.status === 'success' && data.reminders.length > 0) {
            displayReminders(data.reminders);
        } else {
            remindersList.innerHTML = `<p>All plants are taken care of!</p>`;
        }
    } catch (error) {
        console.error('Error fetching reminders:', error);
        remindersList.innerHTML = `<p>Unable to load reminders.</p>`;
    }
}

function displayReminders(reminders) {
    const remindersList = document.querySelector("#reminders-list");
    remindersList.innerHTML = ""; // Clear existing content

    reminders.forEach((reminder) => {
        const reminderItem = document.createElement("div");
        reminderItem.classList.add("reminder-item");
        reminderItem.innerHTML = `
            <p><strong>${reminder.nickname}</strong> - ${reminder.care_type}</p>
            <p>Due: ${reminder.due_date}</p>
        `;
        remindersList.appendChild(reminderItem);
    });
}

// ------------------- Image Error Handling -------------------
function initializeImages() {
    console.log("Initializing image error handling...");
    document.querySelectorAll("img.profile-img").forEach((img) => {
        img.onerror = () => handleImageError(img);
    });
}

function handleImageError(img) {
    console.warn("Image failed to load, replacing with default image.");
    img.src = "../assets/images/default-profile.png";
}

// ------------------- Weather Initialization -------------------
async function initializeWeather() {
    const weatherInfo = document.querySelector("#weather-info");
    if (!weatherInfo) return;

    try {
        navigator.geolocation.getCurrentPosition(async (position) => {
            const { latitude, longitude } = position.coords;
            const apiKey = "QwjQjNwrQ1rQoCwZjCzrwLOE8VTWcBgY";
            const url = `https://api.tomorrow.io/v4/weather/realtime?location=${latitude},${longitude}&apikey=${apiKey}&units=metric`;

            const response = await fetch(url);
            const data = await response.json();
            displayWeather(data);
        });
    } catch (error) {
        console.error("Weather error:", error.message);
        weatherInfo.innerHTML = `<p>Unable to load weather data.</p>`;
    }
}

function displayWeather(data) {
    const weatherInfo = document.querySelector("#weather-info");
    if (data && data.data) {
        weatherInfo.innerHTML = `
            <p>Temperature: ${data.data.values.temperature}°C</p>
            <p>Condition: ${data.data.values.weatherCode}</p>
        `;
    } else {
        weatherInfo.innerHTML = `<p>Weather data unavailable.</p>`;
    }
}

// ------------------- Utility Functions -------------------
function openAddPlantModal() {
    document.getElementById('add-plant-modal').style.display = 'block';
}

function openCareLogModal() {
    document.getElementById('care-log-modal').style.display = 'block';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

function initializeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const collapseBtn = document.querySelector('.sidebar-collapse');
    if (collapseBtn) {
        collapseBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }
}

// Toast notification function
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type === 'error' ? 'toast-error' : ''}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
