console.log('Plant Manager JS loaded');

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    
    // Edit Plant Button Handlers
    document.querySelectorAll('.edit-plant').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Edit button clicked');
            const plantId = this.dataset.plantId;
            const modal = document.getElementById('edit-plant-modal');
            if (modal) {
                document.getElementById('edit-plant-id').value = plantId;
                modal.style.display = 'block';
                showToast('Edit modal opened', 'success');
            }
        });
    });

    // Log Care Button Handlers
    document.querySelectorAll('.log-care').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Care button clicked');
            const plantId = this.dataset.plantId;
            const modal = document.getElementById('care-log-modal');
            if (modal) {
                document.getElementById('care-plant-id').value = plantId;
                modal.style.display = 'block';
                showToast('Care modal opened', 'success');
            }
        });
    });

    // Log Growth Button Handlers
    document.querySelectorAll('.log-growth').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Growth button clicked');
            const plantId = this.dataset.plantId;
            const modal = document.getElementById('growth-log-modal');
            if (modal) {
                document.getElementById('growth-plant-id').value = plantId;
                modal.style.display = 'block';
                showToast('Growth modal opened', 'success');
            }
        });
    });

    // Edit Plant Form Submission
    const editPlantForm = document.getElementById('edit-plant-form');
    if (editPlantForm) {
        editPlantForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Edit form submitted');
            const formData = new FormData(this);
            
            try {
                const response = await fetch('../api/plants.php?action=update_plant', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                console.log('Response:', data);
                
                if (data.status === 'success') {
                    showToast('Plant updated successfully!', 'success');
                    location.reload(); // Reload the page to show updates
                } else {
                    showToast(data.message || 'Failed to update plant', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error updating plant', 'error');
            }
        });
    }

    // Care Log Form Submission
    const careLogForm = document.getElementById('care-log-form');
    if (careLogForm) {
        careLogForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Care form submitted');
            const formData = new FormData(this);
            
            try {
                const response = await fetch('../api/plants.php?action=log_care', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                console.log('Response:', data);
                
                if (data.status === 'success') {
                    showToast('Care logged successfully!', 'success');
                    document.getElementById('care-log-modal').style.display = 'none';
                    location.reload(); // Reload the page to show updates
                } else {
                    showToast(data.message || 'Failed to log care', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error logging care', 'error');
            }
        });
    }

    // Growth Log Form Submission
    const growthLogForm = document.getElementById('growth-log-form');
    if (growthLogForm) {
        growthLogForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Growth form submitted');
            const formData = new FormData(this);
            
            try {
                const response = await fetch('../api/plants.php?action=log_growth', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                console.log('Response:', data);
                
                if (data.status === 'success') {
                    showToast('Growth logged successfully!', 'success');
                    document.getElementById('growth-log-modal').style.display = 'none';
                    location.reload(); // Reload the page to show updates
                } else {
                    showToast(data.message || 'Failed to log growth', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error logging growth', 'error');
            }
        });
    }

    // Modal Close Handlers
    document.querySelectorAll('.modal .close').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.modal').style.display = 'none';
        });
    });

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });
}); 