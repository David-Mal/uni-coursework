import { Component, OnInit } from '@angular/core';
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
  articles: NewsArticle[] = [];
  loading = false;
  error = '';

  filter: NewsFilter = { category: '', start: '', end: '' };
  prevFilterText = '';

  categories = ['politics', 'society', 'health', 'sports', 'weather'];

  constructor(private newsService: NewsService) {}

  ngOnInit() {
    this.prevFilterText = localStorage.getItem('prevFilter') || 'No previous filters';
    this.loadNews();
  }

  loadNews() {
    this.loading = true;
    this.error = '';
    this.newsService.getNews(this.filter).subscribe({
      next: (data) => { this.articles = data; this.loading = false; },
      error: () => { this.error = 'Failed to load news.'; this.loading = false; }
    });

    const text = `Showing: ${this.filter.category || 'All'} from ${this.filter.start || 'Any'} to ${this.filter.end || 'Any'}`;
    this.prevFilterText = 'Previous Filter: ' + text;
    localStorage.setItem('prevFilter', this.prevFilterText);
  }
}
