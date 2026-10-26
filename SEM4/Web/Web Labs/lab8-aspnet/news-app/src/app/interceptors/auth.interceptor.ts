import { HttpInterceptorFn } from '@angular/common/http';
import { inject } from '@angular/core';
import { Router } from '@angular/router';
import { catchError, throwError } from 'rxjs';
import { AuthService } from '../services/auth';

export const authInterceptor: HttpInterceptorFn = (req, next) => {
  const auth   = inject(AuthService);
  const router = inject(Router);

  return next(req).pipe(
    catchError(err => {
      // Don't intercept 401s from the login endpoint itself (wrong credentials)
      if (err.status === 401 && !req.url.includes('/login.php')) {
        auth.clearLocalState();
        router.navigate(['/login']);
      }
      return throwError(() => err);
    })
  );
};
