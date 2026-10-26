import dotenv from 'dotenv';
dotenv.config();

import app from './app';

const PORT = process.env['PORT'] || 3001;

app.listen(PORT, () => {
  console.log(`News Node.js backend running on http://localhost:${PORT}`);
  console.log(`Connecting to MySQL at ${process.env['DB_HOST'] || 'localhost'}:${process.env['DB_PORT'] || 3306}`);
});
