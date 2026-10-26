import { Component, OnInit, signal, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { NewsService } from '../../services/news';

@Component({
  selector: 'app-about',
  imports: [CommonModule, RouterLink],
  templateUrl: './about.html',
  styleUrl: './about.scss'
})
export class AboutComponent implements OnInit {
  articleCount = signal(0);
  categories = ['Politics', 'Society', 'Health', 'Sports', 'Weather'];

  constructor(private newsService: NewsService, private cdr: ChangeDetectorRef) {}

  ngOnInit() {
    this.newsService.getNews().subscribe(data => {
      this.articleCount.set(data.length);
      this.cdr.detectChanges();
    });
  }
}
