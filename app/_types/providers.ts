import {ProductBasket} from "./product";

export interface ProviderFavorites {
    favorites: number[];
    toggle: (id?: number) => Promise<void>;
    isPending: boolean;
}

export interface ProviderBasket {
    basket: ProductBasket[];
    add: (productId: number, productSize: string, toastSuccess?: boolean, count?: number) => void;
    setCount: (productId: number, productSize: string, count: number) => void;
    decrement: (productId: number, productSize: string) => void;
    remove: (productId: number, productSize: string) => void;
    clear: () => void;
    toggle: (productId: number, productSize: string) => void;
    isPending: boolean;
}