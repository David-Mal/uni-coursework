<%@ page contentType="text/html;charset=UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<%-- Convert char to String once so EL comparisons work reliably --%>
<c:set var="mySymbolStr" value="${mySymbol == 88 ? 'X' : (mySymbol == 79 ? 'O' : '')}" />
<c:set var="turnStr"     value="${game.currentTurn == 88 ? 'X' : 'O'}" />
<c:set var="statusStr"   value="${game.status.name()}" />
<c:set var="isMyTurn"    value="${mySymbolStr == turnStr && statusStr == 'playing'}" />
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>X-O Game – Playing</title>
  <link rel="stylesheet" href="${pageContext.request.contextPath}/css/style.css">
</head>
<body>

<nav class="navbar">
  <div class="navbar-brand">
    <span>X</span>-<span style="color:#3b82f6">O</span>&nbsp;Game
  </div>
  <div class="navbar-user">
    <span>Logged in as <strong>${sessionScope.username}</strong></span>
    <span class="badge ${mySymbolStr == 'X' ? 'badge-x' : 'badge-o'}">${mySymbolStr}</span>
    <a href="${pageContext.request.contextPath}/logout" class="btn btn-ghost"
       onclick="return confirm('Log out?')">Log out</a>
  </div>
</nav>

<main class="game-page">

  <div class="players-row" id="players-row">
    <div class="player-card ${turnStr == 'X' && statusStr == 'playing' ? 'active-turn' : ''}" id="player-x-card">
      <div class="player-symbol X">&#10005;</div>
      <div class="player-name">
        <c:choose>
          <c:when test="${not empty game.playerX}">${game.playerX.username}</c:when>
          <c:otherwise>&#8212;</c:otherwise>
        </c:choose>
      </div>
    </div>
    <div class="vs-divider">VS</div>
    <div class="player-card ${turnStr == 'O' && statusStr == 'playing' ? 'active-turn' : ''}" id="player-o-card">
      <div class="player-symbol O">&#9675;</div>
      <div class="player-name">
        <c:choose>
          <c:when test="${not empty game.playerO}">${game.playerO.username}</c:when>
          <c:otherwise>Waiting&#8230;</c:otherwise>
        </c:choose>
      </div>
    </div>
  </div>

  <div class="game-status-msg
    <c:choose>
      <c:when test="${statusStr == 'waiting'}">waiting</c:when>
      <c:when test="${statusStr == 'finished'}">game-over</c:when>
      <c:when test="${isMyTurn}">your-turn</c:when>
      <c:otherwise>waiting</c:otherwise>
    </c:choose>" id="status-msg">
    <c:choose>
      <c:when test="${statusStr == 'waiting'}">Waiting for an opponent to join&#8230;</c:when>
      <c:when test="${statusStr == 'finished'}">Game over!</c:when>
      <c:when test="${isMyTurn}">Your turn</c:when>
      <c:otherwise>Waiting for opponent&#8217;s move&#8230;</c:otherwise>
    </c:choose>
  </div>

  <div class="board-wrapper">
    <div class="board" role="grid" aria-label="Tic-Tac-Toe board">
      <c:forEach var="i" begin="0" end="8">
        <c:set var="cell" value="${game.boardArray[i]}" />
        <c:set var="cellStr" value="${cell == 88 ? 'X' : (cell == 79 ? 'O' : '')}" />
        <c:choose>
          <c:when test="${cellStr == 'X'}">
            <button class="cell taken X" data-pos="${i}" aria-label="Cell ${i+1} X" disabled>&#10005;</button>
          </c:when>
          <c:when test="${cellStr == 'O'}">
            <button class="cell taken O" data-pos="${i}" aria-label="Cell ${i+1} O" disabled>&#9675;</button>
          </c:when>
          <c:otherwise>
            <button class="cell ${isMyTurn ? '' : 'disabled'}"
                    data-pos="${i}"
                    data-hover-symbol="${mySymbolStr}"
                    aria-label="Cell ${i+1} empty"></button>
          </c:otherwise>
        </c:choose>
      </c:forEach>
    </div>

    <div class="board-overlay" id="board-overlay" style="${statusStr == 'waiting' ? '' : 'display:none'}">
      <div class="spinner"></div>
      <span>Waiting for opponent&#8230;</span>
      <small>Open this page in another browser window</small>
    </div>
  </div>

  <div class="game-actions" id="game-actions" style="${statusStr == 'finished' ? '' : 'display:none'}">
    <form method="post" action="${pageContext.request.contextPath}/app/play-again">
      <button type="submit" class="btn btn-primary">&#9654; Play Again</button>
    </form>
    <a href="${pageContext.request.contextPath}/logout" class="btn btn-ghost">Log out</a>
  </div>

  <c:if test="${statusStr != 'finished'}">
    <form id="form-forfeit" method="post" action="${pageContext.request.contextPath}/app/forfeit" style="margin-top:.5rem">
      <button type="button" id="btn-leave" class="btn btn-ghost" style="font-size:.8rem">
        Leave game
      </button>
    </form>
  </c:if>

</main>

<script>var contextPath = '${pageContext.request.contextPath}';</script>
<script src="${pageContext.request.contextPath}/js/game.js"></script>
</body>
</html>
