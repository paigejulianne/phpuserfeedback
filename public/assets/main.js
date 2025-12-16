document.addEventListener('DOMContentLoaded', () => {
    const voteButtons = document.querySelectorAll('.vote-btn');

    voteButtons.forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            const feedbackId = this.dataset.id;
            const countSpan = this.querySelector('.vote-count');

            // Add loading state visual (optional)
            this.style.opacity = '0.7';

            try {
                const response = await fetch('feedback/vote', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ feedback_id: feedbackId })
                });

                const data = await response.json();

                if (data.success) {
                    countSpan.textContent = data.newCount;

                    // Simple visual feedback for toggle
                    if (data.action === 'added') {
                        this.classList.add('voted');
                    } else {
                        this.classList.remove('voted');
                    }
                } else {
                    console.error('Vote failed:', data.message);
                    alert('Could not process vote. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
            } finally {
                this.style.opacity = '1';
            }
        });
    });
});
