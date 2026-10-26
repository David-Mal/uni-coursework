import { Router } from 'express';
import { AdminController } from '../controllers/admin.controller';
import { requireAuth } from '../middleware/auth.middleware';

const router = Router();
const adminController = new AdminController();

router.all('/admin.php', requireAuth, adminController.handleAdmin);

export default router;
