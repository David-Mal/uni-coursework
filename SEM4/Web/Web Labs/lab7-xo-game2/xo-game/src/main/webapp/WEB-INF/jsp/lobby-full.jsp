<%@ page contentType="text/html;charset=UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <%-- Auto-refresh every 5 s to re-check whether a slot opened up --%>
  <meta http-equiv="refresh" content="5;url=${pageContext.request.contextPath}/app/lobby">
  <title>X-O Game – Game Full</title>
  <link rel="stylesheet" href="${pageContext.request.contextPath}/css/style.css">
</head>
<body>

<nav class="navbar">
  <div class="navbar-brand">
    <span style="color:#ef4444">X</span>-<span style="color:#3b82f6">O</span>&nbsp;Game
  </div>
  <div class="navbar-user">
    <span>Logged in as <strong>${username}</strong></span>
    <a href="${pageContext.request.contextPath}/logout" class="btn btn-ghost">Log out</a>
  </div>
</nav>

<div class="lobby-page">
  <div class="card lobby-card">
    <div style="font-size:3rem;margin-bottom:.5rem">🎮</div>
    <h2>Game in Progress</h2>

    <c:if test="${not empty errorMsg}">
      <p>${errorMsg}</p>
    </c:if>
    <c:if test="${empty errorMsg}">
      <p>Two players are already competing. You will be redirected automatically when the game ends.</p>
    </c:if>

    <div class="alert alert-info" style="text-align:left">
      ⏳ This page refreshes every 5 seconds…
    </div>

    <div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap">
      <a href="${pageContext.request.contextPath}/app/lobby" class="btn btn-primary">
        🔄 Check Now
      </a>
      <a href="${pageContext.request.contextPath}/logout" class="btn btn-ghost">Log out</a>
    </div>
  </div>
</div>

</body>
</html>
