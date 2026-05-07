import {CatalogProduct} from "./catalog";

export interface SliderMain {
    id: number;
    text: string;
    image: string;
}
export interface SliderProducts extends CatalogProduct {
    sale: 1 | 0;
}