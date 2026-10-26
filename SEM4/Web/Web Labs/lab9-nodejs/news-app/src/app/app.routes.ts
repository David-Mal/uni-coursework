import { Routes } from '@angular/router';
import { NewsListComponent } from './components/news-list/news-list';
import { ArticleDetailComponent } from './components/article-detail/article-detail';
import { AboutComponent } from './components/about/about';
import { LoginComponent } from './components/login/login';
import { AdminComponent } from './components/admin/admin';
import { authGuard } from './guards/auth.guard';

export const routes: Routes = [
  { path: '', component: NewsListComponent, canActivate: [authGuard] },
  { path: 'article/:id', component: ArticleDetailComponent, canActivate: [authGuard] },
  { path: 'about', component: AboutComponent, canActivate: [authGuard] },
  { path: 'login', component: LoginComponent },
  { path: 'admin', component: AdminComponent, canActivate: [authGuard] },
  { path: '**', redirectTo: '' }
];
