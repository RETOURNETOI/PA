// Sources/app/public/assets/JS/notification.js
class NotificationManager {
    constructor() {
        this.notificationBadge = document.getElementById('notification-badge');
        this.notificationPanel = document.getElementById('notification-panel');
        this.checkInterval = 15000; // 15 secondes
        
        if (this.notificationBadge) {
            this.init();
        }
    }

    init() {
        this.checkNotifications();
        setInterval(() => this.checkNotifications(), this.checkInterval);
        
        // Ouvrir/fermer le panel
        this.notificationBadge.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleNotificationPanel();
        });
        
        document.addEventListener('click', () => {
            this.notificationPanel.classList.add('hidden');
        });
    }

    checkNotifications() {
        fetch('/api/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                if (data.count > 0) {
                    this.notificationBadge.textContent = data.count;
                    this.notificationBadge.classList.remove('hidden');
                    this.updateNotificationPanel();
                } else {
                    this.notificationBadge.classList.add('hidden');
                }
            });
    }

    updateNotificationPanel() {
        fetch('/api/notifications/list')
            .then(response => response.json())
            .then(notifications => {
                this.notificationPanel.innerHTML = notifications.map(notif => `
                    <div class="notification-item" data-id="${notif.id}">
                        <div class="notification-content">${notif.content}</div>
                        <div class="notification-time">${new Date(notif.created_at).toLocaleTimeString()}</div>
                    </div>
                `).join('');
            });
    }

    toggleNotificationPanel() {
        this.notificationPanel.classList.toggle('hidden');
        if (!this.notificationPanel.classList.contains('hidden')) {
            this.markAllAsRead();
        }
    }

    markAllAsRead() {
        fetch('/api/notifications/mark-read', {
            method: 'POST'
        }).then(() => {
            this.notificationBadge.classList.add('hidden');
        });
    }
}

new NotificationManager();