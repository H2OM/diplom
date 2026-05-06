import {Product} from "@_types/products";

export interface SliderMain {
    id: number;
    text: string;
    image: string;
}
export interface SliderProducts extends Product {
    sale: 1 | 0;
}