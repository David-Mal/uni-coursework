<%@ page contentType="text/html;charset=UTF-8" isErrorPage="true" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>X-O Game – Error</title>
  <link rel="stylesheet" href="${pageContext.request.contextPath}/css/style.css">
</head>
<body>
<div class="page-center">
  <div class="card" style="max-width:420px;text-align:center">
    <div style="font-size:3rem;margin-bottom:.5rem">💥</div>
    <h2 style="margin-bottom:.5rem">Something went wrong</h2>
    <p style="color:var(--text-muted);margin-bottom:1.5rem">
      <c:choose>
        <c:when test="${not empty requestScope['javax.servlet.error.message']}">
          ${requestScope['javax.servlet.error.message']}
        </c:when>
        <c:otherwise>An unexpected error occurred. Please try again.</c:otherwise>
      </c:choose>
    </p>
    <a href="${pageContext.request.contextPath}/app/lobby" class="btn btn-primary">
      Go to Lobby
    </a>
  </div>
</div>
</body>
</html>
