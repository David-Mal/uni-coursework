import { UserRepository } from '../repositories/user.repository';

export class AuthService {
  private readonly userRepository: UserRepository;

  constructor() {
    this.userRepository = new UserRepository();
  }

  /**
   * Validates user credentials against the database.
   * Returns the username on success, or null on failure.
   *
   * NOTE: Passwords are stored as plain text in the shared DB to maintain
   * compatibility with the PHP backend. In a standalone production system,
   * replace the plain-text comparison with bcrypt.compare().
   */
  async validateCredentials(username: string, password: string): Promise<string | null> {
    const user = await this.userRepository.findByUsername(username);
    if (!user) return null;
    if (password === user.password) {
      return user.username;
    }
    return null;
  }
}
