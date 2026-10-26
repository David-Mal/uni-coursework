import { Component, OnInit } from '@angular/core';
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
  articles: NewsArticle[] = [];
  categories = ['politics', 'society', 'health', 'sports', 'weather'];

  form: Partial<NewsArticle> = {
    id: undefined, title: '', text: '', producer: '', date: '', category: 'politics'
  };

  constructor(private newsService: NewsService, public auth: AuthService) {}

  ngOnInit() {
    this.load();
  }

  load() {
    this.newsService.getAllNews().subscribe(data => this.articles = data);
  }

  save() {
    const action = this.form.id
      ? this.newsService.updateArticle(this.form)
      : this.newsService.createArticle(this.form);
    action.subscribe(() => {
      this.load();
      this.reset();
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

  reset() {
    if (this.form.id && !confirm('Discard changes?')) return;
    this.form = { id: undefined, title: '', text: '', producer: '', date: '', category: 'politics' };
  }
}
