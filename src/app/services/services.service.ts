import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class ServicesService {

  constructor(private http: HttpClient) { }
  data: any;
  baseURL="http://localhost/SIA-FeedbackSystem/api/";

  getData(endpoint, data) {
    return <any>(
      this.http.post(this.baseURL + endpoint, btoa(JSON.stringify(data)))
    );
  }
  
}
