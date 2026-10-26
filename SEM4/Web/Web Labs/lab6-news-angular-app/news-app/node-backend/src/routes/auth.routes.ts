import { Router } from 'express';
import { AuthController } from '../controllers/auth.controller';

const router = Router();
const authController = new AuthController();

router.all('/login.php', authController.login);
router.get('/logout.php', authController.logout);

export default router;
