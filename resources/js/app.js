import './bootstrap';

// ✅ ADD THIS BELOW
window.Echo.channel('user-status')
    .listen('.status.changed', (e) => {

        let user = document.getElementById('user-' + e.userId);

        if (user) {
            let statusIcon = user.querySelector('.bg-img-status');

            if (statusIcon) {
                statusIcon.classList.remove('bg-success', 'bg-warning', 'bg-red');

                if (e.status === 'online') {
                    statusIcon.classList.add('bg-success');
                } else {
                    statusIcon.classList.add('bg-red');
                }
            }
        }
    });