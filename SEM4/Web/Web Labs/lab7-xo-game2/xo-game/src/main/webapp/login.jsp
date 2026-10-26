<%@ page contentType="text/html;charset=UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>X-O Game – Sign In</title>
  <link rel="stylesheet" href="${pageContext.request.contextPath}/css/style.css">
  <style>
    .login-logo { text-align: center; margin-bottom: 1.5rem; }
    .login-logo h1 { font-size: 2.5rem; font-weight: 900; letter-spacing: .05em; }
    .login-logo h1 .x { color: #ef4444; }
    .login-logo h1 .o { color: #3b82f6; }
    .login-logo p { color: var(--text-muted); font-size: .875rem; margin-top: .25rem; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    .demo-hint {
      margin-top: 1.25rem;
      padding: .65rem .9rem;
      background: rgba(99,102,241,.08);
      border: 1px solid rgba(99,102,241,.2);
      border-radius: 8px;
      font-size: .8rem;
      color: var(--text-muted);
      text-align: center;
    }
  </style>
</head>
<body>
<div class="page-center">
  <div class="login-container">

    <div class="login-logo">
      <h1><span class="x">X</span> - <span class="o">O</span></h1>
      <p>Two-player Tic-Tac-Toe</p>
    </div>

    <div class="card">

      <!-- Server-side error -->
      <c:if test="${not empty errorMsg}">
        <div class="alert alert-error" id="server-error">${errorMsg}</div>
      </c:if>

      <!-- Tabs -->
      <div class="tabs" role="tablist">
        <button class="tab-btn ${empty activeTab || activeTab == 'login' ? 'active' : ''}"
                role="tab" data-tab="login" type="button">Sign In</button>
        <button class="tab-btn ${activeTab == 'register' ? 'active' : ''}"
                role="tab" data-tab="register" type="button">Register</button>
      </div>

      <!-- ===== Login Form ===== -->
      <div class="tab-content ${empty activeTab || activeTab == 'login' ? 'active' : ''}" id="tab-login">
        <form id="form-login" method="post" action="${pageContext.request.contextPath}/login" novalidate>
          <input type="hidden" name="action" value="login">

          <div class="form-group">
            <label for="login-username">Username</label>
            <input type="text" id="login-username" name="username" class="form-control"
                   placeholder="Enter your username" maxlength="30"
                   value="${not empty param.username && empty activeTab ? param.username : ''}"
                   autocomplete="username" required>
            <span class="field-error" id="login-username-err">Please enter your username.</span>
          </div>

          <div class="form-group">
            <label for="login-password">Password</label>
            <input type="password" id="login-password" name="password" class="form-control"
                   placeholder="Enter your password"
                   autocomplete="current-password" required>
            <span class="field-error" id="login-password-err">Please enter your password.</span>
          </div>

          <button type="submit" class="btn btn-primary btn-full">Sign In</button>
        </form>

        <div class="demo-hint">
          🎮 Demo accounts: <strong>player1</strong> / <strong>player2</strong> — password: <strong>123</strong>
        </div>
      </div>

      <!-- ===== Register Form ===== -->
      <div class="tab-content ${activeTab == 'register' ? 'active' : ''}" id="tab-register">
        <form id="form-register" method="post" action="${pageContext.request.contextPath}/login" novalidate>
          <input type="hidden" name="action" value="register">

          <div class="form-group">
            <label for="reg-username">Username</label>
            <input type="text" id="reg-username" name="username" class="form-control"
                   placeholder="3–30 letters, digits or _" maxlength="30"
                   value="${activeTab == 'register' ? param.username : ''}"
                   autocomplete="username" required>
            <span class="field-error" id="reg-username-err">
              Username must be 3–30 characters (letters, digits, underscore).
            </span>
          </div>

          <div class="form-group">
            <label for="reg-password">Password</label>
            <input type="password" id="reg-password" name="password" class="form-control"
                   placeholder="At least 6 characters"
                   autocomplete="new-password" required>
            <span class="field-error" id="reg-password-err">
              Password must be at least 6 characters.
            </span>
          </div>

          <div class="form-group">
            <label for="reg-confirm">Confirm Password</label>
            <input type="password" id="reg-confirm" name="confirmPassword" class="form-control"
                   placeholder="Repeat your password"
                   autocomplete="new-password" required>
            <span class="field-error" id="reg-confirm-err">Passwords do not match.</span>
          </div>

          <button type="submit" class="btn btn-primary btn-full">Create Account</button>
        </form>
      </div>

    </div><!-- /card -->
  </div><!-- /login-container -->
</div><!-- /page-center -->

<script>
(function () {
  /* ---- Tab switching ---- */
  document.querySelectorAll('.tab-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var tab = btn.dataset.tab;
      document.querySelectorAll('.tab-btn').forEach(function (b) {
        b.classList.toggle('active', b.dataset.tab === tab);
      });
      document.querySelectorAll('.tab-content').forEach(function (c) {
        c.classList.toggle('active', c.id === 'tab-' + tab);
      });
      // Hide server error when switching tabs
      var err = document.getElementById('server-error');
      if (err) err.style.display = 'none';
    });
  });

  /* ---- Login validation ---- */
  document.getElementById('form-login').addEventListener('submit', function (e) {
    var ok = true;
    var u  = document.getElementById('login-username');
    var p  = document.getElementById('login-password');

    function showErr(input, errId) {
      input.classList.add('error');
      document.getElementById(errId).classList.add('visible');
      ok = false;
    }
    function clearErr(input, errId) {
      input.classList.remove('error');
      document.getElementById(errId).classList.remove('visible');
    }

    clearErr(u, 'login-username-err');
    clearErr(p, 'login-password-err');

    if (!u.value.trim()) showErr(u, 'login-username-err');
    if (!p.value)        showErr(p, 'login-password-err');

    if (!ok) e.preventDefault();
  });

  /* ---- Register validation ---- */
  document.getElementById('form-register').addEventListener('submit', function (e) {
    var ok = true;
    var u  = document.getElementById('reg-username');
    var p  = document.getElementById('reg-password');
    var c  = document.getElementById('reg-confirm');

    function showErr(input, errId) {
      input.classList.add('error');
      document.getElementById(errId).classList.add('visible');
      ok = false;
    }
    function clearErr(input, errId) {
      input.classList.remove('error');
      document.getElementById(errId).classList.remove('visible');
    }

    [['reg-username','reg-username-err'],['reg-password','reg-password-err'],
     ['reg-confirm','reg-confirm-err']].forEach(function (pair) {
      clearErr(document.getElementById(pair[0]), pair[1]);
    });

    var uVal = u.value.trim();
    if (!uVal || uVal.length < 3 || uVal.length > 30 || !/^[A-Za-z0-9_]+$/.test(uVal)) {
      showErr(u, 'reg-username-err');
    }
    if (!p.value || p.value.length < 6) {
      showErr(p, 'reg-password-err');
    }
    if (!c.value || c.value !== p.value) {
      showErr(c, 'reg-confirm-err');
    }

    if (!ok) e.preventDefault();
  });

  /* ---- Live match check for confirm password ---- */
  var regP = document.getElementById('reg-password');
  var regC = document.getElementById('reg-confirm');
  if (regC) {
    regC.addEventListener('input', function () {
      if (regC.value && regC.value !== regP.value) {
        regC.classList.add('error');
        document.getElementById('reg-confirm-err').classList.add('visible');
      } else {
        regC.classList.remove('error');
        document.getElementById('reg-confirm-err').classList.remove('visible');
      }
    });
  }
})();
</script>
</body>
</html>
