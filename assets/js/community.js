console.log('Community JS loaded');

document.addEventListener('DOMContentLoaded', function() {
    // Create Post Button Handler
    const createPostBtn = document.querySelector('.create-post-btn');
    if (createPostBtn) {
        createPostBtn.addEventListener('click', function() {
            console.log('Create post button clicked');
            const modal = document.getElementById('createPostModal');
            if (modal) {
                modal.style.display = 'block';
            }
        });
    }

    // Create Post Form Submission
    const createPostForm = document.getElementById('createPostForm');
    if (createPostForm) {
        createPostForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Create post form submitted');
            const formData = new FormData(this);

            try {
                const response = await fetch('../actions/create_post.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                console.log('Response:', data);

                if (data.status === 'success') {
                    showToast('Post created successfully!', 'success');
                    document.getElementById('createPostModal').style.display = 'none';
                    location.reload(); // Reload to show new post
                } else {
                    showToast(data.message || 'Failed to create post', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error creating post', 'error');
            }
        });
    }

    // Modal Close Handlers
    document.querySelectorAll('.modal .close').forEach(button => {
        button.addEventListener('click', function() {
            // Clear the comments container when closing
            const commentsContainer = document.getElementById('comments-container');
            if (commentsContainer) {
                commentsContainer.innerHTML = '';
            }
            
            // Clear the comment form
            const commentContent = document.getElementById('comment-content');
            if (commentContent) {
                commentContent.value = '';
            }
            
            // Hide both create post and comments modals
            document.getElementById('createPostModal').style.display = 'none';
            document.getElementById('commentsModal').style.display = 'none';
        });
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            // Clear the comments container when closing
            const commentsContainer = document.getElementById('comments-container');
            if (commentsContainer) {
                commentsContainer.innerHTML = '';
            }
            
            // Clear the comment form
            const commentContent = document.getElementById('comment-content');
            if (commentContent) {
                commentContent.value = '';
            }
            
            // Hide both modals
            document.getElementById('createPostModal').style.display = 'none';
            document.getElementById('commentsModal').style.display = 'none';
        }
    });

    // Like Post Handler
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            try {
                const response = await fetch('../actions/like_post.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ post_id: postId })
                });

                const data = await response.json();
                if (data.status === 'success') {
                    // Update the likes count in the UI
                    const likeCount = this.querySelector('.like-count');
                    likeCount.textContent = data.likes;
                    this.classList.toggle('liked');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error liking post', 'error');
            }
        });
    });

    // Comment Handler
    document.querySelectorAll('.comment-btn').forEach(button => {
        button.addEventListener('click', async function() {
            const postId = this.dataset.postId;
            const modal = document.getElementById('commentsModal');
            const commentsContainer = document.getElementById('comments-container');
            document.getElementById('comment-post-id').value = postId;
            
            try {
                const response = await fetch(`../actions/get_comments.php?post_id=${postId}`);
                const data = await response.json();
                
                if (data.status === 'success') {
                    commentsContainer.innerHTML = data.comments.map(comment => `
                        <div class="comment">
                            <div class="comment-header">
                                <span class="username">${comment.username}</span>
                                <span class="comment-date">${new Date(comment.created_at).toLocaleDateString()}</span>
                            </div>
                            <p class="comment-content">${comment.content}</p>
                        </div>
                    `).join('');
                    
                    modal.style.display = 'block';
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading comments', 'error');
            }
        });
    });

    // Comment Form Submission
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const postId = document.getElementById('comment-post-id').value;
            const content = document.getElementById('comment-content').value;
            
            try {
                const response = await fetch('../actions/add_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ post_id: postId, content: content })
                });
                
                const data = await response.json();
                if (data.status === 'success') {
                    // Add new comment to the container
                    const commentsContainer = document.getElementById('comments-container');
                    const commentHtml = `
                        <div class="comment">
                            <div class="comment-header">
                                <span class="username">${data.comment.username}</span>
                                <span class="comment-date">${new Date(data.comment.created_at).toLocaleDateString()}</span>
                            </div>
                            <p class="comment-content">${data.comment.content}</p>
                        </div>
                    `;
                    commentsContainer.insertAdjacentHTML('afterbegin', commentHtml);
                    
                    // Update comment count
                    const commentBtn = document.querySelector(`.comment-btn[data-post-id="${postId}"]`);
                    const countSpan = commentBtn.querySelector('.comment-count');
                    countSpan.textContent = parseInt(countSpan.textContent) + 1;
                    
                    // Clear form
                    document.getElementById('comment-content').value = '';
                    showToast('Comment added successfully!', 'success');
                } else {
                    showToast(data.message || 'Failed to add comment', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error adding comment', 'error');
            }
        });
    }
});

// Toast function
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