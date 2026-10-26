import { Component, OnInit, signal, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink } from '@angular/router';
import { NewsService } from '../../services/news';
import { NewsArticle, NewsFilter } from '../../models/news.model';

@Component({
  selector: 'app-news-list',
  imports: [CommonModule, FormsModule, RouterLink],
  templateUrl: './news-list.html',
  styleUrl: './news-list.scss'
})
export class NewsListComponent implements OnInit {
  articles = signal<NewsArticle[]>([]);
  loading = signal(false);
  error = signal('');
  prevFilterText = signal('');

  filter: NewsFilter = { category: '', start: '', end: '' };

  categories = ['politics', 'society', 'health', 'sports', 'weather'];

  constructor(private newsService: NewsService, private cdr: ChangeDetectorRef) {}

  ngOnInit() {
    this.prevFilterText.set(localStorage.getItem('prevFilter') || 'No previous filters');
    this.loadNews();
  }

  loadNews() {
    this.loading.set(true);
    this.error.set('');
    this.newsService.getNews(this.filter).subscribe({
      next: (data) => {
        this.articles.set(data);
        this.loading.set(false);
        this.cdr.detectChanges();
      },
      error: () => {
        this.error.set('Failed to load news.');
        this.loading.set(false);
        this.cdr.detectChanges();
      }
    });

    const text = `Showing: ${this.filter.category || 'All'} from ${this.filter.start || 'Any'} to ${this.filter.end || 'Any'}`;
    this.prevFilterText.set('Previous Filter: ' + text);
    localStorage.setItem('prevFilter', this.prevFilterText());
  }
}
