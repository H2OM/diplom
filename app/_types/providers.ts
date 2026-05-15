import {Product, ProductBasket} from "./product";
import {User, UserEditData, UserSignInData, UserSignUpData} from "@_types/user";

export interface ProviderFavorites {
    favorites: Product[];
    get: () => Promise<void>;
    toggle: (id?: number) => Promise<void>;
    isPending: boolean;
}

export interface ProviderBasket {
    basket: ProductBasket[];
    add: (productId: number, count?: number, toastSuccess?: boolean) => void;
    setCount: (productId: number, count: number) => void;
    decrement: (productId: number) => void;
    remove: (productId: number) => void;
    clear: () => void;
    toggle: (productId: number) => void;
    isPending: boolean;
}

export interface ProviderUser {
    user: User | null;
    isPending: boolean;
    get: () => Promise<void>;
    signIn: (data: UserSignInData) => Promise<void>;
    signUp: (data: UserSignUpData) => Promise<void>;
    edit: (data: UserEditData) => Promise<boolean>;
    logOut: () => Promise<void>;
}