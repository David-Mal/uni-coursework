import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { NewsArticle, NewsFilter } from '../models/news.model';

@Injectable({ providedIn: 'root' })
export class NewsService {
  // Change this base URL to match your PHP server
  private baseUrl = 'http://localhost:8080';

  constructor(private http: HttpClient) {}

  getNews(filter: NewsFilter = {}): Observable<NewsArticle[]> {
    let params = new HttpParams();
    if (filter.category) params = params.set('category', filter.category);
    if (filter.start) params = params.set('start', filter.start);
    if (filter.end) params = params.set('end', filter.end);
    return this.http.get<NewsArticle[]>(`${this.baseUrl}/fetch_news.php`, { params });
  }

  createArticle(data: Partial<NewsArticle>): Observable<any> {
    const body = new URLSearchParams();
    body.set('id', '');
    body.set('title', data.title || '');
    body.set('text', data.text || '');
    body.set('producer', data.producer || '');
    body.set('date', data.date || '');
    body.set('category', data.category || '');
    return this.http.post(`${this.baseUrl}/admin.php`, body.toString(), {
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      withCredentials: true,
      responseType: 'text'
    });
  }

  updateArticle(data: Partial<NewsArticle>): Observable<any> {
    const body = new URLSearchParams();
    body.set('id', String(data.id));
    body.set('title', data.title || '');
    body.set('text', data.text || '');
    body.set('producer', data.producer || '');
    body.set('date', data.date || '');
    body.set('category', data.category || '');
    return this.http.post(`${this.baseUrl}/admin.php`, body.toString(), {
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      withCredentials: true,
      responseType: 'text'
    });
  }

  deleteArticle(id: number): Observable<any> {
    return this.http.get(`${this.baseUrl}/admin.php`, {
      params: { delete: id },
      withCredentials: true,
      responseType: 'text'
    });
  }

  getAllNews(): Observable<NewsArticle[]> {
    return this.http.get<NewsArticle[]>(`${this.baseUrl}/fetch_news.php`, { withCredentials: true });
  }
}
