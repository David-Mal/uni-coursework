import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private baseUrl = 'http://localhost:8080';

  constructor(private http: HttpClient) {}

  login(username: string, password: string): Observable<any> {
    const body = new URLSearchParams();
    body.set('username', username);
    body.set('password', password);
    return this.http.post(`${this.baseUrl}/login.php`, body.toString(), {
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      withCredentials: true
    }).pipe(
      tap(() => {
        localStorage.setItem('loggedIn', 'true');
        localStorage.setItem('username', username);
      })
    );
  }

  logout(): Observable<any> {
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('username');
    return this.http.get(`${this.baseUrl}/logout.php`, {
      withCredentials: true
    });
  }

  isLoggedIn(): boolean {
    return localStorage.getItem('loggedIn') === 'true';
  }

  getUsername(): string {
    return localStorage.getItem('username') || '';
  }
}
