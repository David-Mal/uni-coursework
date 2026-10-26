import { Injectable, signal } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';
import { API_BASE_URL } from '../config/api.config';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private baseUrl = API_BASE_URL;

  private _isLoggedIn = signal(localStorage.getItem('loggedIn') === 'true');
  private _username = signal(localStorage.getItem('username') || '');

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
        this._isLoggedIn.set(true);
        this._username.set(username);
      })
    );
  }

  logout(): Observable<any> {
    localStorage.removeItem('loggedIn');
    localStorage.removeItem('username');
    this._isLoggedIn.set(false);
    this._username.set('');
    return this.http.get(`${this.baseUrl}/logout.php`, {
      withCredentials: true
    });
  }

  isLoggedIn(): boolean {
    return this._isLoggedIn();
  }

  getUsername(): string {
    return this._username();
  }
}
