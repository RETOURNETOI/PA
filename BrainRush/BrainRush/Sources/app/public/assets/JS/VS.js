class VSGame {
    constructor() {
        this.gameId = null;
        this.isWaiting = false;
        this.pollInterval = null;
        this.gameState = 'idle';
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeCarousels();
    }

    setupEventListeners() {
        const createRoomBtn = document.getElementById('createRoomBtn');
        const joinRoomBtn = document.getElementById('joinRoomBtn');

        if (createRoomBtn) {
            createRoomBtn.addEventListener('click', () => this.createRoom());
        }

        if (joinRoomBtn) {
            joinRoomBtn.addEventListener('click', () => this.joinRoom());
        }
    }

    async createRoom() {
        try {
            const response = await fetch('/BrainRush/BrainRush/vs/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'create_room'
                })
            });

            const data = await response.json();

            if (data.success) {
                this.gameId = data.game_id;
                this.showWaitingScreen(data.room_code);
                this.startPolling();
            } else {
                alert('Erreur: ' + data.message);
            }
        } catch (error) {
            console.error('Erreur création room:', error);
            alert('Erreur de connexion');
        }
    }

    async joinRoom() {
        const roomCode = document.getElementById('roomCode').value.trim();
        
        if (!roomCode) {
            alert('Veuillez entrer un code de partie');
            return;
        }

        try {
            const response = await fetch('/BrainRush/BrainRush/vs/join', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'join_room',
                    room_code: roomCode
                })
            });

            const data = await response.json();

            if (data.success) {
                this.gameId = data.game_id;
                this.startGame();
            } else {
                alert('Erreur: ' + data.message);
            }
        } catch (error) {
            console.error('Erreur join room:', error);
            alert('Erreur de connexion');
        }
    }

    showWaitingScreen(roomCode) {
        const gameArea = document.getElementById('gameArea');
        gameArea.innerHTML = `
            <div class="waiting-screen">
                <h2>🎮 Partie créée !</h2>
                <div class="room-code">
                    <h3>Code de la partie:</h3>
                    <div class="code-display">${roomCode}</div>
                    <button onclick="navigator.clipboard.writeText('${roomCode}')">📋 Copier</button>
                </div>
                <div class="waiting-animation">
                    <p>En attente d'un adversaire...</p>
                    <div class="spinner"></div>
                </div>
                <button onclick="vsGame.cancelGame()" class="btn btn-danger">Annuler</button>
            </div>
        `;
        gameArea.classList.remove('hidden');
        this.gameState = 'waiting';
    }

    startPolling() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
        }

        this.pollInterval = setInterval(async () => {
            await this.checkGameStatus();
        }, 2000);
    }

    async checkGameStatus() {
        if (!this.gameId) return;

        try {
            const response = await fetch(`/BrainRush/BrainRush/vs/status?game_id=${this.gameId}`);
            const data = await response.json();

            if (data.status === 'active' && this.gameState === 'waiting') {
                this.startGame();
            } else if (data.status === 'cancelled') {
                this.gameState = 'idle';
                this.hideGameArea();
                alert('Partie annulée');
            }
        } catch (error) {
            console.error('Erreur polling:', error);
        }
    }

    startGame() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
        }

        this.gameState = 'playing';
        this.showGameInterface();
    }

    showGameInterface() {
        const gameArea = document.getElementById('gameArea');
        gameArea.innerHTML = `
            <div class="game-interface">
                <div class="game-header">
                    <h2>🎯 Partie en cours</h2>
                    <div class="players-info">
                        <div class="player-score">
                            <span>Vous: </span>
                            <span id="yourScore">0</span>
                        </div>
                        <div class="vs-divider">VS</div>
                        <div class="player-score">
                            <span>Adversaire: </span>
                            <span id="opponentScore">0</span>
                        </div>
                    </div>
                </div>
                
                <div class="question-area">
                    <div id="questionContainer">
                        <div class="loading">Chargement de la question...</div>
                    </div>
                </div>
                
                <div class="game-controls">
                    <button onclick="vsGame.forfeit()" class="btn btn-danger">Abandonner</button>
                </div>
            </div>
        `;
        
        this.loadQuestion();
        this.startGamePolling();
    }

    async loadQuestion() {
        try {
            const response = await fetch(`/BrainRush/BrainRush/vs/question?game_id=${this.gameId}`);
            const data = await response.json();

            if (data.success) {
                this.displayQuestion(data.question);
            }
        } catch (error) {
            console.error('Erreur chargement question:', error);
        }
    }

    displayQuestion(question) {
        const container = document.getElementById('questionContainer');
        const answers = [
            question.correct_answer,
            question.wrong_answer1,
            question.wrong_answer2,
            question.wrong_answer3
        ].sort(() => Math.random() - 0.5);

        container.innerHTML = `
            <div class="question">
                <h3>${question.question}</h3>
                <div class="answers">
                    ${answers.map((answer, index) => `
                        <button class="answer-btn" data-answer="${answer}">
                            ${answer}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;

        document.querySelectorAll('.answer-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.submitAnswer(e.target.dataset.answer, question.correct_answer);
            });
        });
    }

    async submitAnswer(selectedAnswer, correctAnswer) {
        const isCorrect = selectedAnswer === correctAnswer;

        document.querySelectorAll('.answer-btn').forEach(btn => {
            btn.disabled = true;
            if (btn.dataset.answer === correctAnswer) {
                btn.classList.add('correct');
            } else if (btn.dataset.answer === selectedAnswer && !isCorrect) {
                btn.classList.add('wrong');
            }
        });

        try {
            await fetch('/BrainRush/BrainRush/vs/answer', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    game_id: this.gameId,
                    answer: selectedAnswer,
                    is_correct: isCorrect
                })
            });

            setTimeout(() => {
                this.loadQuestion();
            }, 2000);

        } catch (error) {
            console.error('Erreur submit answer:', error);
        }
    }

    startGamePolling() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
        }

        this.pollInterval = setInterval(async () => {
            await this.updateGameState();
        }, 3000);
    }

    async updateGameState() {
        try {
            const response = await fetch(`/BrainRush/BrainRush/vs/game-state?game_id=${this.gameId}`);
            const data = await response.json();

            if (data.success) {
                document.getElementById('yourScore').textContent = data.your_score || 0;
                document.getElementById('opponentScore').textContent = data.opponent_score || 0;

                if (data.status === 'completed') {
                    this.endGame(data.winner_id);
                }
            }
        } catch (error) {
            console.error('Erreur update game state:', error);
        }
    }

    endGame(winnerId) {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
        }

        const gameArea = document.getElementById('gameArea');
        const isWinner = winnerId === parseInt(localStorage.getItem('user_id'));

        gameArea.innerHTML = `
            <div class="game-end">
                <h2>${isWinner ? '🎉 Victoire !' : '😔 Défaite'}</h2>
                <p>${isWinner ? 'Félicitations, vous avez gagné !' : 'Bonne chance pour la prochaine fois !'}</p>
                <button onclick="vsGame.resetGame()" class="btn btn-primary">Nouvelle partie</button>
                <button onclick="vsGame.hideGameArea()" class="btn btn-secondary">Retour au menu</button>
            </div>
        `;

        this.gameState = 'ended';
    }

    async cancelGame() {
        if (this.gameId) {
            try {
                await fetch('/BrainRush/BrainRush/vs/cancel', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({game_id: this.gameId})
                });
            } catch (error) {
                console.error('Erreur cancel game:', error);
            }
        }

        this.resetGame();
    }

    async forfeit() {
        if (confirm('Êtes-vous sûr de vouloir abandonner ?')) {
            try {
                await fetch('/BrainRush/BrainRush/vs/forfeit', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({game_id: this.gameId})
                });
            } catch (error) {
                console.error('Erreur forfeit:', error);
            }

            this.resetGame();
        }
    }

    resetGame() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
        }
        
        this.gameId = null;
        this.gameState = 'idle';
        this.hideGameArea();
    }

    hideGameArea() {
        const gameArea = document.getElementById('gameArea');
        gameArea.classList.add('hidden');
        gameArea.innerHTML = '';
        
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
        }
    }

    initializeCarousels() {
        ['recommender', 'populaire', 'communaute'].forEach(id => {
            this.initializeInfiniteCarousel(id);
        });
    }

    initializeInfiniteCarousel(id) {
        const carousel = document.getElementById(id);
        if (!carousel) return;

        const items = [...carousel.children];
        
        items.forEach(item => {
            const before = item.cloneNode(true);
            const after = item.cloneNode(true);
            carousel.insertBefore(before, carousel.firstChild);
            carousel.appendChild(after);
        });

        setTimeout(() => {
            carousel.scrollLeft = carousel.scrollWidth / 3;
        }, 0);

        carousel.addEventListener('scroll', () => {
            const maxScroll = carousel.scrollWidth;
            const currentScroll = carousel.scrollLeft;
            const third = maxScroll / 3;

            if (currentScroll <= 0) {
                carousel.scrollLeft = third;
            } else if (currentScroll >= third * 2) {
                carousel.scrollLeft = third;
            }
        });
    }
}

function scrollgauche(id) {
    const carousel = document.getElementById(id);
    if (carousel) {
        carousel.scrollBy({ left: -200, behavior: 'smooth' });
    }
}

function scrolldroit(id) {
    const carousel = document.getElementById(id);
    if (carousel) {
        carousel.scrollBy({ left: 200, behavior: 'smooth' });
    }
}

let vsGame;

document.addEventListener('DOMContentLoaded', () => {
    vsGame = new VSGame();
});

const vsStyles = `
.waiting-screen {
    text-align: center;
    padding: 40px 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.room-code {
    margin: 30px 0;
}

.code-display {
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary-color);
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin: 10px 0;
    letter-spacing: 3px;
}

.waiting-animation {
    margin: 30px 0;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 20px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.game-interface {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.game-header {
    text-align: center;
    margin-bottom: 30px;
}

.players-info {
    display: flex;
    justify-content: space-around;
    align-items: center;
    margin-top: 20px;
}

.player-score {
    font-size: 1.2rem;
    font-weight: bold;
}

.vs-divider {
    font-size: 1.5rem;
    color: var(--primary-color);
    font-weight: bold;
}

.question-area {
    margin: 30px 0;
}

.question h3 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 1.4rem;
}

.answers {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    max-width: 600px;
    margin: 0 auto;
}

.answer-btn {
    padding: 15px 20px;
    border: 2px solid var(--primary-color);
    background: white;
    color: var(--primary-color);
    border-radius: 10px;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.answer-btn:hover {
    background: var(--primary-color);
    color: white;
}

.answer-btn.correct {
    background: #27ae60;
    border-color: #27ae60;
    color: white;
}

.answer-btn.wrong {
    background: #e74c3c;
    border-color: #e74c3c;
    color: white;
}

.answer-btn:disabled {
    cursor: not-allowed;
}

.game-controls {
    text-align: center;
    margin-top: 30px;
}

.game-end {
    text-align: center;
    padding: 40px 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.game-end h2 {
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.loading {
    text-align: center;
    padding: 40px;
    font-size: 1.2rem;
    color: #666;
}

@media (max-width: 768px) {
    .answers {
        grid-template-columns: 1fr;
    }
    
    .players-info {
        flex-direction: column;
        gap: 10px;
    }
    
    .code-display {
        font-size: 1.5rem;
        letter-spacing: 2px;
    }
}
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = vsStyles;
document.head.appendChild(styleSheet);