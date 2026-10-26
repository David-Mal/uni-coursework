import pool from '../config/database';
import { User } from '../models/user.model';

export class UserRepository {
  async findByUsername(username: string): Promise<User | null> {
    const [rows] = await pool.execute('SELECT * FROM users WHERE username = ?', [username]);
    const results = rows as User[];
    return results[0] ?? null;
  }
}
