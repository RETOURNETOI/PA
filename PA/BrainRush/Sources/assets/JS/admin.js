document.addEventListener('DOMContentLoaded', function() {
  loadDashboardData();
  setInterval(updateLiveStats, 5000);
});

async function loadDashboardData() {
  try {
    const response = await fetch('/admin/api/dashboard');
    const data = await response.json();
    
    renderStats(data.stats);
    renderFlaggedPosts(data.flaggedPosts);
    renderLogs(data.logs);
  } catch (error) {
    console.error('Erreur:', error);
  }
}

function renderStats(stats) {
  const container = document.getElementById('stats-container');
  container.innerHTML = `
    <div class="stat-card">
      <h3>👥 Utilisateurs</h3>
      <p>${stats.users}</p>
    </div>
    <div class="stat-card">
      <h3>👑 Admins</h3>
      <p>${stats.admins}</p>
    </div>
    <div class="stat-card">
      <h3>👁️ Visiteurs actifs</h3>
      <p id="live-visitors">${stats.visitors}</p>
    </div>
  `;
}

function renderFlaggedPosts(posts) {
  const container = document.getElementById('flagged-container');
  
  if (posts.length === 0) {
    container.innerHTML = '<p>Aucune publication signalée.</p>';
    return;
  }

  container.innerHTML = posts.map(post => `
    <div class="flagged-post">
      <div class="post-header">
        <strong>${escapeHtml(post.pseudo)}</strong>
        <span class="post-date">${new Date(post.date_creation).toLocaleString()}</span>
      </div>
      <p class="post-content">${escapeHtml(post.contenu)}</p>
      <div class="post-actions">
        <button class="btn btn-danger" onclick="banUser(${post.user_id}, '1d')">Bannir 1j</button>
        <button class="btn btn-danger" onclick="banUser(${post.user_id}, '7d')">Bannir 7j</button>
        <button class="btn btn-danger" onclick="banUser(${post.user_id}, 'permanent')">Bannir</button>
        <button class="btn btn-secondary" onclick="deletePost(${post.id})">Supprimer</button>
      </div>
    </div>
  `).join('');
}

function renderLogs(logs) {
  const container = document.getElementById('logs-container');
  
  if (logs.length === 0) {
    container.innerHTML = '<p>Aucune action récente.</p>';
    return;
  }

  container.innerHTML = `
    <div class="logs-list">
      ${logs.map(log => `
        <div class="log-entry">
          <strong>${escapeHtml(log.pseudo)}</strong>: ${escapeHtml(log.action)}
          <span class="log-date">(${new Date(log.created_at).toLocaleString()})</span>
        </div>
      `).join('')}
    </div>
  `;
}

async function updateLiveStats() {
  try {
    const response = await fetch('/admin/api/live-visitors');
    const count = await response.text();
    document.getElementById('live-visitors').textContent = count;
  } catch (error) {
    console.error('Erreur mise à jour stats:', error);
  }
}

async function banUser(userId, duration) {
  if (!confirm('Confirmer le bannissement ?')) return;
  
  try {
    const response = await fetch('/admin/api/ban-user', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ userId, duration })
    });
    
    if (response.ok) {
      loadDashboardData();
    }
  } catch (error) {
    console.error('Erreur:', error);
  }
}

async function deletePost(postId) {
  if (!confirm('Supprimer cette publication ?')) return;
  
  try {
    const response = await fetch('/admin/api/delete-post', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ postId })
    });
    
    if (response.ok) {
      loadDashboardData();
    }
  } catch (error) {
    console.error('Erreur:', error);
  }
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