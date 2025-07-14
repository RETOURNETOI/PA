// Sources/app/public/assets/JS/VS.js
class VSGame {
    constructor() {
        this.gameId = null;
        this.opponentFound = false;
        this.init();
    }

    init() {
        this.startMatchmaking();
        this.setupEventListeners();
    }

    startMatchmaking() {
        fetch('/vs/join', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ userId: currentUserId })
        })
        .then(response => response.json())
        .then(data => {
            this.gameId = data.gameId;
            this.waitForOpponent();
        });
    }

    waitForOpponent() {
        const checkOpponent = () => {
            fetch(`/vs/status?gameId=${this.gameId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.opponentFound) {
                        this.startGame(data.opponent);
                    } else {
                        setTimeout(checkOpponent, 2000);
                    }
                });
        };
        
        checkOpponent();
    }

    startGame(opponent) {
        // Initialisation du jeu
    }
}

new VSGame();