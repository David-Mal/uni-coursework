import pool from '../config/database';
import { NewsArticle, NewsFilter } from '../models/news.model';

type Row = Record<string, unknown>;

async function run(sql: string, params: (string | number)[] = []): Promise<Row[]> {
  const [rows] = await (pool as unknown as { execute: (s: string, p: (string | number)[]) => Promise<[Row[], unknown]> }).execute(sql, params);
  return rows;
}

export class NewsRepository {
  async findAll(filter: NewsFilter): Promise<NewsArticle[]> {
    const startDate = filter.start && filter.start.trim() ? filter.start : '1900-01-01';
    const endDate = filter.end && filter.end.trim() ? filter.end : '2099-12-31';

    let sql = 'SELECT * FROM news WHERE date BETWEEN ? AND ?';
    const params: (string | number)[] = [startDate, endDate];

    if (filter.category && filter.category.trim()) {
      sql += ' AND category = ?';
      params.push(filter.category);
    }

    return run(sql, params) as unknown as Promise<NewsArticle[]>;
  }

  async create(article: Omit<NewsArticle, 'id'>): Promise<number> {
    const result = await run(
      'INSERT INTO news (title, text, producer, date, category) VALUES (?, ?, ?, ?, ?)',
      [article.title, article.text, article.producer ?? '', article.date, article.category]
    ) as unknown as { insertId: number };
    return result.insertId;
  }

  async update(id: number, article: Partial<NewsArticle>): Promise<void> {
    await run(
      'UPDATE news SET title=?, text=?, producer=?, date=?, category=? WHERE id=?',
      [article.title ?? '', article.text ?? '', article.producer ?? '', article.date ?? '', article.category ?? '', id]
    );
  }

  async remove(id: number): Promise<void> {
    await run('DELETE FROM news WHERE id = ?', [id]);
  }
}
