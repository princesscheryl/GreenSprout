// Search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const userSearch = document.getElementById('userSearch');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');

    // Add event listeners
    if (userSearch) userSearch.addEventListener('input', filterUsers);
    if (roleFilter) roleFilter.addEventListener('change', filterUsers);
    if (statusFilter) statusFilter.addEventListener('change', filterUsers);

    function filterUsers() {
        const searchTerm = userSearch.value.toLowerCase();
        const roleValue = roleFilter.value.toLowerCase();
        const statusValue = statusFilter.value;

        const rows = document.querySelectorAll('.users-table tbody tr');

        rows.forEach(row => {
            const username = row.cells[0].textContent.toLowerCase();
            const email = row.cells[1].textContent.toLowerCase();
            const role = row.cells[2].textContent.toLowerCase();
            const status = row.cells[3].textContent.toLowerCase();

            const matchesSearch = username.includes(searchTerm) || 
                                email.includes(searchTerm);
            const matchesRole = !roleValue || role === roleValue;
            const matchesStatus = !statusValue || 
                                (statusValue === '1' && status === 'active') ||
                                (statusValue === '0' && status === 'inactive');

            row.style.display = matchesSearch && matchesRole && matchesStatus ? '' : 'none';
        });
    }
});

// Function to edit user
async function editUser(userId) {
    console.log('Editing user:', userId); // Debug log
    try {
        const response = await fetch(`../actions/get_user.php?id=${userId}`);
        console.log('Response:', response); // Debug log
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const user = await response.json();
        console.log('User data:', user); // Debug log
        
        // Create and show modal
        const modal = document.createElement('div');
        modal.className = 'modal';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h2>Edit User</h2>
                <form id="editUserForm">
                    <input type="hidden" name="user_id" value="${user.user_id}">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" value="${user.username}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="${user.email}" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" required>
                            <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                            <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                            <option value="super_admin" ${user.role === 'super_admin' ? 'selected' : ''}>Super Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="is_active" required>
                            <option value="1" ${user.is_active === '1' ? 'selected' : ''}>Active</option>
                            <option value="0" ${user.is_active === '0' ? 'selected' : ''}>Inactive</option>
                        </select>
                    </div>
                    <div class="modal-buttons">
                        <button type="submit" class="save-btn">Save Changes</button>
                        <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                    </div>
                </form>
            </div>
        `;
        document.body.appendChild(modal);

        // Handle form submission
        document.getElementById('editUserForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            console.log('Form submitted'); // Debug log
            
            const formData = new FormData(e.target);
            try {
                const response = await fetch('../actions/update_user.php', {
                    method: 'POST',
                    body: formData
                });
                console.log('Update response:', response); // Debug log
                
                const result = await response.json();
                console.log('Update result:', result); // Debug log
                
                if (result.status === 'success') {
                    closeModal();
                    location.reload();
                } else {
                    alert(result.message || 'Error updating user');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating user');
            }
        });
    } catch (error) {
        console.error('Error:', error);
        alert('Error fetching user data');
    }
}

// Function to toggle user status
async function toggleUserStatus(userId, currentStatus) {
    if (confirm('Are you sure you want to ' + (currentStatus ? 'deactivate' : 'activate') + ' this user?')) {
        try {
            const response = await fetch('../actions/toggle_user_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: userId,
                    status: !currentStatus
                })
            });
            const result = await response.json();
            if (result.status === 'success') {
                location.reload(); // Refresh to show updated status
            } else {
                alert(result.message || 'Error updating user status');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error updating user status');
        }
    }
}

// Function to close modal
function closeModal() {
    const modal = document.querySelector('.modal');
    if (modal) {
        modal.remove();
    }
} 