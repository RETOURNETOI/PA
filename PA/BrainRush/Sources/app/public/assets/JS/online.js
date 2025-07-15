let lastUpdate = 0;
const player = prompt("Entrez votre pseudo (player1 ou player2)");

function poll() {
  fetch(`php/poll.php?last_update=${lastUpdate}`)
    .then(res => res.json())
    .then(data => {
      if (data.status !== 'timeout') {
        lastUpdate = data.last_update;
        document.getElementById('output').innerText =
          `Player1: ${data.player1}, Player2: ${data.player2}`;
      }
      setTimeout(poll, 100);
    })
    .catch(err => {
      console.error("Erreur polling :", err);
      setTimeout(poll, 2000); // retry
    });
}

function sendAction() {
  const action = document.getElementById('action').value;
  fetch('php/update.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `player=${player}&action=${encodeURIComponent(action)}`
  }).then(() => {
    document.getElementById('action').value = "";
  });
}

window.onload = poll;