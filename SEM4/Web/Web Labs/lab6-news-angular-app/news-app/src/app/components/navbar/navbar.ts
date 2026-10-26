import { Component } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { CommonModule } from '@angular/common';
import { AuthService } from '../../services/auth';
import { Router } from '@angular/router';
import { API_BASE_URL } from '../../config/api.config';

@Component({
  selector: 'app-navbar',
  imports: [RouterLink, RouterLinkActive, CommonModule],
  templateUrl: './navbar.html',
  styleUrl: './navbar.scss'
})
export class NavbarComponent {
  readonly backendLabel: string;
  readonly backendType: 'php' | 'node';

  constructor(public auth: AuthService, private router: Router) {
    this.backendType = API_BASE_URL.includes('8080') ? 'php' : 'node';
    this.backendLabel = this.backendType === 'php'
      ? 'PHP :8080'
      : 'Node.js :3001';
  }

  logout() {
    this.auth.logout().subscribe(() => this.router.navigate(['/']));
  }
}
