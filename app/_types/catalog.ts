export interface CatalogProduct {
    id: number;
    title: string;
    brand: string;
    type: string;
    article: string;
    price: number;
    price_old: number;
    image: string;
    slider_images: string;
    size: string;
    hit: 1 | 0;
    description: string;
    category_id: number;
    category: string
}

export interface CatalogProductBasket extends CatalogProduct {
    count: number;
}

export interface CatalogFilters {
    category?: string;
    price?: string;
    sale?: 'Yes' | 'More10' | 'More30' | 'More50';
    favorite?: boolean;
    brand?: string;
    size?: string;
    color?: string;
    type?: string;
    sort?: string;
}