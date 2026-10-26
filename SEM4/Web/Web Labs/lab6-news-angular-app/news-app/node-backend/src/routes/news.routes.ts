import { Router } from 'express';
import { NewsController } from '../controllers/news.controller';

const router = Router();
const newsController = new NewsController();

router.get('/fetch_news.php', newsController.getNews);

export default router;
