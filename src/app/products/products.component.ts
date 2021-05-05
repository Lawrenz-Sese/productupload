import { Component, ComponentFactoryResolver, OnInit  } from '@angular/core';


@Component({
  selector: 'app-products',
  templateUrl: './products.component.html',
  styleUrls: ['./products.component.css']
})
export class ProductsComponent implements OnInit {
  products: any;
  forms: any;

  constructor() { }

  ngOnInit(): void {
  }

}
