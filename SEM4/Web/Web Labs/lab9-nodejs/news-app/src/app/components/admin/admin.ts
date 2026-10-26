import { Component, OnInit, signal, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { NewsService } from '../../services/news';
import { AuthService } from '../../services/auth';
import { NewsArticle } from '../../models/news.model';

@Component({
  selector: 'app-admin',
  imports: [CommonModule, FormsModule],
  templateUrl: './admin.html',
  styleUrl: './admin.scss'
})
export class AdminComponent implements OnInit {
  articles = signal<NewsArticle[]>([]);
  categories = ['politics', 'society', 'health', 'sports', 'weather'];

  form: Partial<NewsArticle> = {
    id: undefined, title: '', text: '', producer: '', date: '', category: 'politics'
  };

  constructor(
    private newsService: NewsService,
    public auth: AuthService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit() {
    this.load();
  }

  load() {
    this.newsService.getAllNews().subscribe(data => {
      this.articles.set(data);
      this.cdr.detectChanges();
    });
  }

  save() {
    const action = this.form.id
      ? this.newsService.updateArticle(this.form)
      : this.newsService.createArticle(this.form);
    action.subscribe(() => {
      this.load();
      this.reset(true);
    });
  }

  edit(article: NewsArticle) {
    this.form = { ...article };
  }

  delete(id: number) {
    if (confirm('Are you sure you want to delete this article?')) {
      this.newsService.deleteArticle(id).subscribe(() => this.load());
    }
  }

  reset(skipConfirm = false) {
    if (!skipConfirm && this.form.id && !confirm('Discard changes?')) return;
    this.form = { id: undefined, title: '', text: '', producer: '', date: '', category: 'politics' };
  }
}
