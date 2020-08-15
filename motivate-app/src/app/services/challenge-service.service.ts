import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ChallengeServiceService {
    apiURL: string = 'http://localhost:8000/api/';

  constructor(public http: HttpClient) {}

  getListChallenges(): Observable<any>{
      return this.http.get(this.apiURL + 'listChallenges');
  }
}
