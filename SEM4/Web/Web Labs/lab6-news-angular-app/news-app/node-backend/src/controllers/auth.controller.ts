import { Request, Response } from 'express';
import { AuthService } from '../services/auth.service';

export class AuthController {
  private readonly authService: AuthService;

  constructor() {
    this.authService = new AuthService();
  }

  login = async (req: Request, res: Response): Promise<void> => {
    if (req.method !== 'POST') {
      res.json({ message: 'Use POST to login' });
      return;
    }

    try {
      const { username, password } = req.body as { username: string; password: string };
      const validUsername = await this.authService.validateCredentials(username, password);

      if (validUsername) {
        req.session.user = validUsername;
        res.json({ success: true, username: validUsername });
      } else {
        res.status(401).json({ success: false, error: 'Invalid credentials' });
      }
    } catch {
      res.status(500).json({ error: 'Internal server error' });
    }
  };

  logout = (req: Request, res: Response): void => {
    req.session.destroy(() => {
      res.json({ success: true });
    });
  };
}
