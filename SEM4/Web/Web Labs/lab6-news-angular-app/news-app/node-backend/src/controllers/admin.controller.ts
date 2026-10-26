import { Request, Response } from 'express';
import { NewsService } from '../services/news.service';

export class AdminController {
  private readonly newsService: NewsService;

  constructor() {
    this.newsService = new NewsService();
  }

  handleAdmin = async (req: Request, res: Response): Promise<void> => {
    try {
      // DELETE: GET /admin.php?delete=<id>
      if (req.method === 'GET' && req.query['delete'] !== undefined) {
        await this.newsService.deleteArticle(Number(req.query['delete']));
        res.json({ success: true, action: 'deleted' });
        return;
      }

      // CREATE or UPDATE: POST /admin.php
      if (req.method === 'POST') {
        const { id, title, text, producer, date, category } = req.body as Record<string, string>;

        if (id && id !== '') {
          await this.newsService.updateArticle(Number(id), { title, text, producer, date, category });
          res.json({ success: true, action: 'updated' });
        } else {
          const result = await this.newsService.createArticle({ title, text, producer, date, category });
          res.json({ success: true, action: 'created', id: result.id });
        }
        return;
      }

      // GET /admin.php (no query) — list all news
      const news = await this.newsService.getFilteredNews({});
      res.json(news);
    } catch {
      res.status(500).json({ error: 'Internal server error' });
    }
  };
}
