document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    setInterval(updateLiveStats, 10000);
    setInterval(checkNewReports, 15000);
    setupReportHandlers();
});

async function loadDashboardData() {
    try {
        const [statsResponse, reportsResponse] = await Promise.all([
            fetch('/BrainRush/BrainRush/api/admin/live-stats'),
            fetch('/BrainRush/BrainRush/api/admin/reports')
        ]);
        
        if (statsResponse.ok) {
            const stats = await statsResponse.json();
            updateStatsDisplay(stats);
        }
        
        if (reportsResponse.ok) {
            const reportsData = await reportsResponse.json();
            updateReportsDisplay(reportsData.reports);
        }
    } catch (error) {
        console.error('Erreur chargement dashboard:', error);
        showNotification('Erreur de connexion', 'error');
    }
}

async function updateLiveStats() {
    try {
        const response = await fetch('/BrainRush/BrainRush/api/admin/live-stats');
        
        if (response.ok) {
            const stats = await response.json();
            updateStatsDisplay(stats);
        }
    } catch (error) {
        console.error('Erreur mise à jour stats:', error);
    }
}

function updateStatsDisplay(stats) {
    const elements = {
        userCount: document.querySelector('[data-stat="user-count"]'),
        liveVisitors: document.querySelector('[data-stat="live-visitors"]'),
        pendingReports: document.querySelector('[data-stat="pending-reports"]')
    };

    if (elements.userCount) {
        elements.userCount.textContent = stats.user_count || 0;
        animateNumber(elements.userCount);
    }
    
    if (elements.liveVisitors) {
        const oldValue = parseInt(elements.liveVisitors.textContent) || 0;
        const newValue = stats.live_visitors || 0;
        
        elements.liveVisitors.textContent = newValue;
        
        if (newValue !== oldValue) {
            elements.liveVisitors.classList.add('highlight');
            setTimeout(() => elements.liveVisitors.classList.remove('highlight'), 1000);
        }
    }
    
    if (elements.pendingReports) {
        const oldValue = parseInt(elements.pendingReports.textContent) || 0;
        const newValue = stats.pending_reports || 0;
        
        elements.pendingReports.textContent = newValue;
        
        if (newValue > oldValue) {
            elements.pendingReports.classList.add('new-alert');
            showNotification(`${newValue - oldValue} nouveau(x) signalement(s)`, 'warning');
            setTimeout(() => elements.pendingReports.classList.remove('new-alert'), 2000);
        }
    }

    document.querySelector('[data-stat="last-update"]').textContent = 
        new Date().toLocaleTimeString();
}

async function checkNewReports() {
    try {
        const response = await fetch('/BrainRush/BrainRush/api/admin/reports');
        
        if (response.ok) {
            const data = await response.json();
            const currentCount = document.querySelectorAll('.report-item').length;
            
            if (data.reports.length > currentCount) {
                updateReportsDisplay(data.reports);
                showNotification('Nouveaux signalements détectés', 'info');
            }
        }
    } catch (error) {
        console.error('Erreur vérification reports:', error);
    }
}

function updateReportsDisplay(reports) {
    const container = document.getElementById('reports-container');
    
    if (!container) return;

    if (reports.length === 0) {
        container.innerHTML = '<div class="no-reports">Aucun signalement en attente</div>';
        return;
    }

    container.innerHTML = reports.map(report => `
        <div class="report-item" data-id="${report.id}">
            <div class="report-header">
                <span class="report-type">${getReportTypeLabel(report.content_type)}</span>
                <span class="report-time">${formatDate(report.created_at)}</span>
                ${report.is_auto == 1 ? '<span class="auto-tag">AUTO</span>' : ''}
            </div>
            
            <div class="report-content">
                <strong>Raison:</strong> ${escapeHtml(report.reason)}
                ${report.content_preview ? `<br><strong>Contenu:</strong> ${escapeHtml(report.content_preview.substring(0, 100))}...` : ''}
                ${report.reporter_name ? `<br><strong>Signalé par:</strong> ${escapeHtml(report.reporter_name)}` : ''}
            </div>
            
            <div class="report-actions">
                <button class="btn btn-success" onclick="handleReport(${report.id}, 'ignore')">
                    ✅ Ignorer
                </button>
                <button class="btn btn-warning" onclick="handleReport(${report.id}, 'warn')">
                    ⚠️ Avertir
                </button>
                <button class="btn btn-danger" onclick="handleReport(${report.id}, 'ban')">
                    🔨 Bannir
                </button>
            </div>
        </div>
    `).join('');
}

function setupReportHandlers() {
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('report-action-btn')) {
            const reportId = e.target.dataset.reportId;
            const action = e.target.dataset.action;
            handleReport(reportId, action);
        }
    });
}

async function handleReport(reportId, action) {
    const actionLabels = {
        'ignore': 'ignorer',
        'warn': 'avertir',
        'ban': 'bannir'
    };
    
    if (!confirm(`Êtes-vous sûr de vouloir ${actionLabels[action]} ce signalement ?`)) {
        return;
    }

    try {
        const response = await fetch('/BrainRush/BrainRush/admin/handle-report', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                report_id: reportId,
                action: action
            })
        });

        if (response.ok) {
            const result = await response.json();
            
            if (result.success) {
                const reportElement = document.querySelector(`[data-id="${reportId}"]`);
                if (reportElement) {
                    reportElement.style.animation = 'fadeOut 0.5s';
                    setTimeout(() => reportElement.remove(), 500);
                }
                
                showNotification(`Signalement ${actionLabels[action]} avec succès`, 'success');
                loadDashboardData();
            } else {
                showNotification(result.message || 'Erreur lors du traitement', 'error');
            }
        } else {
            showNotification('Erreur de connexion', 'error');
        }
    } catch (error) {
        console.error('Erreur traitement report:', error);
        showNotification('Erreur lors du traitement', 'error');
    }
}

async function banUser(userId, duration) {
    if (!confirm(`Confirmer le bannissement ${duration === 'permanent' ? 'permanent' : 'pour ' + duration} ?`)) {
        return;
    }
    
    try {
        const response = await fetch('/BrainRush/BrainRush/admin/ban-user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ userId, duration })
        });
        
        if (response.ok) {
            showNotification('Utilisateur banni avec succès', 'success');
            loadDashboardData();
        } else {
            showNotification('Erreur lors du bannissement', 'error');
        }
    } catch (error) {
        console.error('Erreur bannissement:', error);
        showNotification('Erreur de connexion', 'error');
    }
}

async function deletePost(postId) {
    if (!confirm('Supprimer définitivement ce post ?')) {
        return;
    }
    
    try {
        const response = await fetch('/BrainRush/BrainRush/admin/delete-post', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ postId })
        });
        
        if (response.ok) {
            showNotification('Post supprimé avec succès', 'success');
            loadDashboardData();
        } else {
            showNotification('Erreur lors de la suppression', 'error');
        }
    } catch (error) {
        console.error('Erreur suppression:', error);
        showNotification('Erreur de connexion', 'error');
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

function animateNumber(element) {
    element.classList.add('number-update');
    setTimeout(() => element.classList.remove('number-update'), 600);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'À l\'instant';
    if (diff < 3600000) return `${Math.floor(diff / 60000)}min`;
    if (diff < 86400000) return `${Math.floor(diff / 3600000)}h`;
    
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getReportTypeLabel(type) {
    const labels = {
        'post': '📝 Post',
        'reply': '💬 Réponse',
        'user': '👤 Utilisateur'
    };
    return labels[type] || type;
}

function escapeHtml(unsafe) {
    return unsafe
        .toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

window.handleReport = handleReport;
window.banUser = banUser;
window.deletePost = deletePost;

const adminStyles = `
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    z-index: 9999;
    max-width: 300px;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification.show {
    transform: translateX(0);
}

.notification-success { background: #27ae60; }
.notification-error { background: #e74c3c; }
.notification-warning { background: #f39c12; }
.notification-info { background: #3498db; }

.notification button {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    margin-left: 10px;
}

.highlight {
    background: #3498db !important;
    color: white !important;
    animation: pulse 0.5s ease;
}

.new-alert {
    background: #e74c3c !important;
    color: white !important;
    animation: pulse 1s ease;
}

.number-update {
    animation: numberPulse 0.6s ease;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes numberPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); color: #3498db; }
    100% { transform: scale(1); }
}

@keyframes fadeOut {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(-100%); }
}

.report-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background: white;
    transition: all 0.3s ease;
}

.report-item:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.report-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.auto-tag {
    background: #f39c12;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: bold;
}

.report-actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.no-reports {
    text-align: center;
    padding: 40px;
    color: #666;
    font-style: italic;
}
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = adminStyles;
document.head.appendChild(styleSheet);