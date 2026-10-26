import { Request, Response } from 'express';
import { NewsService } from '../services/news.service';
import { NewsFilter } from '../models/news.model';

export class NewsController {
  private readonly newsService: NewsService;

  constructor() {
    this.newsService = new NewsService();
  }

  getNews = async (req: Request, res: Response): Promise<void> => {
    try {
      const filter: NewsFilter = {
        category: req.query['category'] as string | undefined,
        start: req.query['start'] as string | undefined,
        end: req.query['end'] as string | undefined,
      };
      const news = await this.newsService.getFilteredNews(filter);
      res.json(news);
    } catch {
      res.status(500).json({ error: 'Internal server error' });
    }
  };
}
