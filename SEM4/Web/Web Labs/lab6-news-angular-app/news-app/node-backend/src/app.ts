import express from 'express';
import session from 'express-session';
import { corsMiddleware } from './middleware/cors.middleware';
import newsRoutes from './routes/news.routes';
import adminRoutes from './routes/admin.routes';
import authRoutes from './routes/auth.routes';

const app = express();

app.use(corsMiddleware);

app.use(express.urlencoded({ extended: false }));
app.use(express.json());

app.use(
  session({
    secret: process.env['SESSION_SECRET'] || 'news-app-secret',
    resave: false,
    saveUninitialized: false,
    cookie: {
      httpOnly: true,
      sameSite: 'lax',
      maxAge: 24 * 60 * 60 * 1000,
    },
  })
);

app.use(newsRoutes);
app.use(adminRoutes);
app.use(authRoutes);

app.get('/', (_req, res) => {
  res.json({
    status: 'ok',
    message: 'News Node.js backend is running',
    endpoints: [
      'GET  /fetch_news.php?category=&start=&end=',
      'POST /login.php',
      'GET  /logout.php',
      'POST /admin.php  (auth required – create/update)',
      'GET  /admin.php?delete=<id>  (auth required)',
    ],
  });
});

export default app;
