document.addEventListener('DOMContentLoaded', () => {
    const voteButtons = document.querySelectorAll('.vote-btn');

    voteButtons.forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            const feedbackId = this.dataset.id;
            const countSpan = this.querySelector('.vote-count');

            // Add loading state visual (optional)
            this.style.opacity = '0.7';

            // Use the absolute site URL provided by the view
            const endpoint = (window.SITE_URL || '') + '/feedback/vote';

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ feedback_id: feedbackId })
                });

                // Check if response is JSON (it might be 404 HTML if path is wrong)
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error("Server returned non-JSON response. Possible path error.");
                }

                const data = await response.json();

                if (data.success) {
                    // Update count
                    countSpan.textContent = data.newCount;

                    // Toggle active state visual
                    if (data.action === 'added') {
                        this.style.borderColor = 'var(--primary-color)';
                        this.querySelector('svg').style.color = 'var(--primary-color)';
                    } else {
                        this.style.borderColor = 'var(--border-color)';
                        this.querySelector('svg').style.color = 'var(--text-secondary)';
                    }
                } else {
                    if (data.message === 'Unauthorized') {
                        window.location.href = '../login';
                    } else {
                        alert(data.message || 'Error occurred');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again. (' + error.message + ')');
            } finally {
                this.style.opacity = '1';
            }
        });
    });
});
