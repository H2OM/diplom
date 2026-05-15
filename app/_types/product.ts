export interface Product {
    id: number;
    title: string;
    article: string;
    brand_id: string;
    brand: string; // TODO
    category_type_id: number;
    category: string;
    description: string;
    hit: "1" | "0";
    image: string;
    slider_images: string;
    price: number;
    price_old: number;
    unit: string;
    stock: number;
    variations: {id: number; image: string}[];
}

export interface ProductBasket extends Omit<Product, 'variations'>  {
    count: number;
}

export interface ProductDetails extends Product {
    local_filters: ProductLocalFilters[]; // TODO
}

export interface ProductLocalFilters {
    // TODO
}

