# X-O Game — JSP/Servlet Web Application

A two-player Tic-Tac-Toe game built with **Java Servlets + JSP**, an SQLite database, and AJAX polling for real-time updates.

---

## Features

| Feature | Detail |
|---|---|
| Authentication | Username + password (SHA-256 + per-user salt), session-based |
| Session protection | `AuthFilter` guards all `/app/*` routes; logout destroys session |
| Two-player limit | 3rd visitor is shown a "Game Full" page with auto-refresh |
| Real-time board | Client polls `/app/status` every 2 s via XMLHttpRequest (AJAX) |
| Input validation | Both client-side (JS) and server-side (Servlet) |
| No manual IDs | Player never enters a game ID; lobby auto-assigns roles |
| Confirmations | "Leave Game" and "Log out" both require confirm dialogs |
| Clean UX | Inline field errors, disabled cells, turn indicator, win animation |
| Demo accounts | `player1` / `player2`, password `123` — pre-seeded on first run |

---

## Project Structure

```
xo-game/
├── pom.xml
└── src/main/
    ├── java/com/xogame/
    │   ├── util/
    │   │   ├── DBUtil.java          ← SQLite init + connection pool
    │   │   └── PasswordUtil.java    ← SHA-256 + salt hashing
    │   ├── model/
    │   │   ├── User.java
    │   │   └── Game.java            ← board logic + win check
    │   ├── dao/
    │   │   ├── UserDAO.java
    │   │   └── GameDAO.java
    │   ├── filter/
    │   │   └── AuthFilter.java      ← protects /app/*
    │   └── servlet/
    │       ├── LoginServlet.java    ← GET/POST /login, POST /register
    │       ├── LogoutServlet.java   ← GET /logout
    │       ├── LobbyServlet.java    ← GET /app/lobby (join/create)
    │       ├── GameServlet.java     ← GET /app/game (show board)
    │       ├── MoveServlet.java     ← POST /app/move (AJAX)
    │       ├── StatusServlet.java   ← GET /app/status (AJAX polling)
    │       ├── ForfeitServlet.java  ← POST /app/forfeit
    │       └── PlayAgainServlet.java← POST /app/play-again
    └── webapp/
        ├── WEB-INF/
        │   ├── web.xml
        │   └── jsp/
        │       ├── game.jsp         ← main game board
        │       ├── lobby-full.jsp   ← shown to 3rd player
        │       └── error.jsp
        ├── login.jsp                ← login + register tabs
        ├── index.jsp                ← redirects to /login
        ├── css/style.css
        └── js/game.js               ← AJAX polling + board rendering
```

---

## Prerequisites

- Java 11+
- Maven 3.6+
- Tomcat 9 (or use the embedded Maven plugin)

---

## Build & Run

### Option A — Embedded Tomcat via Maven

```bash
cd xo-game
mvn clean package tomcat7:run
```

Then open **http://localhost:8080/xo**

> The `tomcat7-maven-plugin` uses Tomcat 7 runtime but runs fine for Servlet 3.1 / 4.0 apps.  
> For Tomcat 9 use the `cargo-maven-plugin` (see below).

### Option B — Deploy WAR to Tomcat 9

```bash
mvn clean package
cp target/xo-game.war $CATALINA_HOME/webapps/
$CATALINA_HOME/bin/startup.sh
```

Then open **http://localhost:8080/xo-game**

### Option C — Cargo Plugin (Tomcat 9 embedded)

Replace the plugin block in `pom.xml`:

```xml
<plugin>
  <groupId>org.codehaus.cargo</groupId>
  <artifactId>cargo-maven3-plugin</artifactId>
  <version>1.10.9</version>
  <configuration>
    <container>
      <containerId>tomcat9x</containerId>
      <artifactInstaller>
        <groupId>org.apache.tomcat</groupId>
        <artifactId>tomcat</artifactId>
        <version>9.0.82</version>
      </artifactInstaller>
    </container>
    <configuration>
      <properties>
        <cargo.servlet.port>8080</cargo.servlet.port>
      </properties>
    </configuration>
    <deployables>
      <deployable>
        <properties>
          <context>/xo</context>
        </properties>
      </deployable>
    </deployables>
  </configuration>
</plugin>
```

Then: `mvn clean package cargo:run`

---

## Database

SQLite file is created automatically at `~/xogame.db` on first run.

```sql
-- users: id, username, password_hash, salt, created_at
-- games: id, player_x_id, player_o_id, board(9 chars), current_turn, status, winner, timestamps
```

---

## How to Play

1. Open the app in **two different browsers** (or incognito windows).
2. Log in as `player1` in browser A, `player2` in browser B.
3. Player 1 visits `/xo/app/lobby` → becomes Player X, waits.
4. Player 2 visits `/xo/app/lobby` → joins as Player O, game begins.
5. Players take turns clicking cells. Board updates via AJAX polling.
6. After game ends click **Play Again** to start a new match.

---

## Security Notes

- Passwords hashed with SHA-256 + cryptographically random per-user salt.
- Session fixation prevented by invalidating old session on login.
- `HttpOnly` cookie flag set to prevent JS session hijacking.
- No-cache headers added by `AuthFilter` to prevent back-button exposure.
- JSP pages in `WEB-INF/jsp/` are inaccessible directly.
- All user input validated both client-side and server-side.
