import { Component, OnInit, signal, ChangeDetectorRef } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { NewsService } from '../../services/news';
import { NewsArticle } from '../../models/news.model';

@Component({
  selector: 'app-article-detail',
  imports: [CommonModule, RouterLink],
  templateUrl: './article-detail.html',
  styleUrl: './article-detail.scss'
})
export class ArticleDetailComponent implements OnInit {
  article = signal<NewsArticle | null>(null);
  loading = signal(true);
  error = signal('');

  constructor(
    private route: ActivatedRoute,
    private newsService: NewsService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit() {
    const id = Number(this.route.snapshot.paramMap.get('id'));
    this.newsService.getNews().subscribe({
      next: (data) => {
        const found = data.find(a => Number(a.id) === id) || null;
        this.article.set(found);
        if (!found) this.error.set('Article not found.');
        this.loading.set(false);
        this.cdr.detectChanges();
      },
      error: () => {
        this.error.set('Failed to load article.');
        this.loading.set(false);
        this.cdr.detectChanges();
      }
    });
  }
}
