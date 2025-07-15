<?php 
$pageTitle = "Classement";
$cssFiles = ['classement.css', 'chatbot.css'];
$baseUrl = '/BrainRush/BrainRush'; 
require_once __DIR__.'/../include/header.php'; 
?>

<div class="container leaderboard">
    <main class="leaderboard-content">
        <h1>Classement Général</h1>
        
        <div class="leaderboard-grid">
            <div class="top-players">
                <?php for ($i = 0; $i < min(3, count($topPlayers ?? [])); $i++): ?>
                <div class="podium-item rank-<?= $i+1 ?>">
                    <div class="avatar"><?= substr($topPlayers[$i]['username'] ?? 'User', 0, 1) ?></div>
                    <h3><?= htmlspecialchars($topPlayers[$i]['username'] ?? 'Joueur'.($i+1)) ?></h3>
                    <p><?= $topPlayers[$i]['score'] ?? (1000-$i*100) ?> pts</p>
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
                    <?php 
                    $defaultPlayers = [
                        ['id' => 1, 'username' => 'Joueur1', 'score' => 1000, 'last_played' => date('Y-m-d'), 'avatar' => 'avatar_def1.png'],
                        ['id' => 2, 'username' => 'Joueur2', 'score' => 900, 'last_played' => date('Y-m-d'), 'avatar' => 'lion.png'],
                        ['id' => 3, 'username' => 'Joueur3', 'score' => 800, 'last_played' => date('Y-m-d'), 'avatar' => 'tigre.png']
                    ];
                    $playersToShow = $topPlayers ?? $defaultPlayers;
                    ?>
                    <?php foreach ($playersToShow as $index => $player): ?>
                    <tr class="<?= ($player['id'] === ($_SESSION['user_id'] ?? null)) ? 'current-user' : '' ?>">
                        <td><?= $index + 1 ?></td>
                        <td>
                            <span class="player-avatar">
                                <img src="<?= $baseUrl ?>/public/assets/images/<?= htmlspecialchars($player['avatar'] ?? 'avatar_def1.png') ?>" alt="<?= htmlspecialchars($player['username']) ?>">
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

<?php 
$jsFiles = ['chatbot.js'];
require_once __DIR__.'/../include/footer.php'; 
?>