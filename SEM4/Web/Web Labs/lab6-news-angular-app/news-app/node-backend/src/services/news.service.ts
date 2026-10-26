import { NewsRepository } from '../repositories/news.repository';
import { NewsArticle, NewsFilter } from '../models/news.model';

export class NewsService {
  private readonly newsRepository: NewsRepository;

  constructor() {
    this.newsRepository = new NewsRepository();
  }

  async getFilteredNews(filter: NewsFilter): Promise<NewsArticle[]> {
    return this.newsRepository.findAll(filter);
  }

  async createArticle(article: Omit<NewsArticle, 'id'>): Promise<{ id: number }> {
    const id = await this.newsRepository.create(article);
    return { id };
  }

  async updateArticle(id: number, article: Partial<NewsArticle>): Promise<void> {
    await this.newsRepository.update(id, article);
  }

  async deleteArticle(id: number): Promise<void> {
    await this.newsRepository.remove(id);
  }
}
