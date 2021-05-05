import { Component, ComponentFactoryResolver, OnInit  } from '@angular/core';
import { TemplateRef, ViewChild } from '@angular/core';
import { ThrowStmt } from '@angular/compiler';
import { Router } from '@angular/router';
import { ServicesService } from '../services/services.service';
import { HttpClientModule, HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-products',
  templateUrl: './products.component.html',
  styleUrls: ['./products.component.css']
})
export class ProductsComponent implements OnInit {
  products: any;
  forms: any;

  constructor( public ds: ServicesService ) { }

  ngOnInit(): void {
    this.getProducts();
  }

  getProducts(){
    this.ds.getData("products", null).subscribe(data =>{
      this.products = data.payload;
      console.log(this.products)
    });
  }

  getForms(){
    this.ds.getData("forms", null).subscribe(data =>{
      this.forms = data.payload;
      console.log(this.forms)
    });
  }

}
