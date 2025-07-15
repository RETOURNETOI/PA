<?php require_once __DIR__.'/../include/header.php'; ?>

<div class="container leaderboard">
    <nav class="main-nav">
        <a href="/" class="nav-logo">
            <img src="/assets/images/lion.png" alt="BrainRush Logo">
        </a>
        <div class="nav-links">
            <a href="/quizz_solo">Solo</a>
            <a href="/vs">1vs1</a>
            <a href="/forum">Forum</a>
            <a href="/compte">Mon Compte</a>
            <?php if(isset($_SESSION['admin']) && $_SESSION['admin']): ?>
                <a href="/admin/dashboard" class="admin-link">Admin</a>
            <?php endif; ?>
        </div>
    </nav>

    <main class="leaderboard-content">
        <h1>Classement Général</h1>
        
        <div class="leaderboard-grid">
            <div class="top-players">
                <?php for ($i = 0; $i < min(3, count($topPlayers)); $i++): ?>
                <div class="podium-item rank-<?= $i+1 ?>">
                    <div class="avatar"><?= substr($topPlayers[$i]['username'], 0, 1) ?></div>
                    <h3><?= htmlspecialchars($topPlayers[$i]['username']) ?></h3>
                    <p><?= $topPlayers[$i]['score'] ?> pts</p>
                </div>
                <?php endfor; ?>
            </div>
            
            <table class="full-leaderboard">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Joueur</th>
                        <th>Score</th>
                        <th>Dernière partie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topPlayers as $index => $player): ?>
                    <tr class="<?= ($player['id'] === ($_SESSION['user_id'] ?? null)) ? 'current-user' : '' ?>">
                        <td><?= $index + 1 ?></td>
                        <td>
                            <span class="player-avatar">
                                <img src="/assets/images/<?= htmlspecialchars($player['avatar']) ?>" alt="<?= htmlspecialchars($player['username']) ?>">
                            </span>
                            <?= htmlspecialchars($player['username']) ?>
                        </td>
                        <td><?= $player['score'] ?></td>
                        <td><?= date('d/m/Y', strtotime($player['last_played'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php require_once __DIR__.'/../include/footer.php'; ?>