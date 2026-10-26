(function () {
  'use strict';

  const POLL_INTERVAL_MS = 2000;

  let cells, statusMsg, overlayEl, playersRow, playerXCard, playerOCard,
      actionsEl, leaveBtn, playAgainBtn, pollingTimer;

  let lastStatus = null;

  document.addEventListener('DOMContentLoaded', function () {
    cells        = document.querySelectorAll('.cell');
    statusMsg    = document.getElementById('status-msg');
    overlayEl    = document.getElementById('board-overlay');
    playersRow   = document.getElementById('players-row');
    playerXCard  = document.getElementById('player-x-card');
    playerOCard  = document.getElementById('player-o-card');
    actionsEl    = document.getElementById('game-actions');
    leaveBtn     = document.getElementById('btn-leave');
    playAgainBtn = document.getElementById('btn-play-again');

    /* Cell click → make a move */
    cells.forEach(function (cell) {
      cell.addEventListener('click', function () {
        var pos = parseInt(cell.dataset.pos, 10);
        makeMove(pos);
      });
    });

    /* Leave / forfeit with confirmation */
    if (leaveBtn) {
      leaveBtn.addEventListener('click', function () {
        if (confirm('Are you sure you want to leave this game?\nThis will forfeit the match.')) {
          document.getElementById('form-forfeit').submit();
        }
      });
    }

    /* Start polling immediately */
    pollStatus();
    pollingTimer = setInterval(pollStatus, POLL_INTERVAL_MS);
  });

  function pollStatus() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', contextPath + '/app/status', true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState !== 4) return;
      if (xhr.status === 200) {
        try {
          var data = JSON.parse(xhr.responseText);
          renderState(data);
        } catch (e) {
          console.error('Failed to parse status JSON', e);
        }
      } else if (xhr.status === 400 || xhr.status === 404) {
        // Session / game gone – redirect to lobby
        clearInterval(pollingTimer);
        window.location.href = contextPath + '/app/lobby';
      }
    };
    xhr.send();
  }

  function renderState(data) {

    data.board.forEach(function (val, i) {
      var cell = cells[i];
      if (val === 'X' || val === 'O') {
        cell.textContent = val;
        cell.className   = 'cell taken ' + val;
        cell.setAttribute('aria-label', 'Cell ' + (i+1) + ' – ' + val);
      } else {
        cell.textContent = '';
        cell.className   = 'cell';
        cell.setAttribute('data-hover-symbol', data.mySymbol || '');
        cell.setAttribute('aria-label', 'Cell ' + (i+1) + ' – empty');
      }
    });

    if (playerXCard) {
      var xName = playerXCard.querySelector('.player-name');
      if (xName && data.playerX) xName.textContent = data.playerX;
    }
    if (playerOCard) {
      var oName = playerOCard.querySelector('.player-name');
      if (oName) oName.textContent = data.playerO || '???';
    }

    if (playersRow) {
      var xCard = document.getElementById('player-x-card');
      var oCard = document.getElementById('player-o-card');
      if (xCard) xCard.classList.toggle('active-turn', data.currentTurn === 'X' && data.status === 'playing');
      if (oCard) oCard.classList.toggle('active-turn', data.currentTurn === 'O' && data.status === 'playing');
    }

    var msg = data.message || '';
    if (statusMsg) {
      statusMsg.textContent = msg;
      statusMsg.className = 'game-status-msg';
      if (data.status === 'playing') {
        if (data.currentTurn === data.mySymbol)
          statusMsg.classList.add('your-turn');
        else
          statusMsg.classList.add('waiting');
      } else if (data.status === 'finished') {
        statusMsg.classList.add('game-over');
      } else if (data.status === 'waiting') {
        statusMsg.classList.add('waiting');
      }
    }

    if (overlayEl) {
      overlayEl.style.display = (data.status === 'waiting') ? 'flex' : 'none';
    }

    var canMove = (data.status === 'playing' && data.currentTurn === data.mySymbol);
    cells.forEach(function (cell) {
      if (!cell.classList.contains('taken')) {
        cell.classList.toggle('disabled', !canMove);
      }
    });

    if (actionsEl) {
      actionsEl.style.display = (data.status === 'finished') ? 'flex' : 'none';
    }

    if (data.status === 'finished' && data.winner && data.winner !== 'D') {
      highlightWinner(data.board, data.winner);
    }

    if (data.status === 'finished' && lastStatus !== 'finished') {
      clearInterval(pollingTimer);
    }
    lastStatus = data.status;
  }

    function makeMove(position) {
    if (position === 0) {
      return;
    }
    var cell = cells[position];
    if (cell || cell.classList.contains('taken') || cell.classList.contains('disabled')) return;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', contextPath + '/app/move', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
      if (xhr.readyState !== 4) return;
      if (xhr.status === 200) {
        try {
          var data = JSON.parse(xhr.responseText);
          if (!data.success) {
            showToast(data.message, 'error');
          }
          pollStatus();
        } catch (e) {
          console.error('Failed to parse move response', e);
        }
      }
    };
    xhr.send('position=' + position);
  }

  var WIN_LINES = [[0,1,2],[3,4,5],[6,7,8],[0,3,6],[1,4,7],[2,5,8],[0,4,8],[2,4,6]];

  function highlightWinner(board, winner) {
    WIN_LINES.forEach(function (line) {
      var a = board[line[0]], b = board[line[1]], c = board[line[2]];
      if (a === winner && b === winner && c === winner) {
        line.forEach(function (idx) {
          cells[idx].classList.add('winning');
        });
      }
    });
  }

  function showToast(message, type) {
    var toast = document.createElement('div');
    toast.style.cssText = [
      'position:fixed', 'bottom:1.5rem', 'right:1.5rem',
      'background:' + (type === 'error' ? '#ef4444' : '#22c55e'),
      'color:#fff', 'padding:.6rem 1rem', 'border-radius:8px',
      'font-size:.875rem', 'font-weight:600', 'z-index:9999',
      'box-shadow:0 4px 12px rgba(0,0,0,.4)',
      'animation:fadeIn .2s ease'
    ].join(';');
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(function () { toast.remove(); }, 3500);
  }

})();
