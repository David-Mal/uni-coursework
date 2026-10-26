import { Component, OnInit } from '@angular/core';
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
  article: NewsArticle | null = null;
  loading = true;
  error = '';

  constructor(private route: ActivatedRoute, private newsService: NewsService) {}

  ngOnInit() {
    const id = Number(this.route.snapshot.paramMap.get('id'));
    this.newsService.getNews().subscribe({
      next: (data) => {
        this.article = data.find(a => a.id === id) || null;
        if (!this.article) this.error = 'Article not found.';
        this.loading = false;
      },
      error: () => { this.error = 'Failed to load article.'; this.loading = false; }
    });
  }
}
