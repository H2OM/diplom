import {useContext} from "react";
import BasketContext from "@providers/BasketProvider";

export function useBasket(productId?: number) {
    const context = useContext<FavoritesContext | null>(BasketContext);

    if (!context) {
        throw new Error('Favorites provider is missing');
    }

    const { favorites, toggleFavorite } = context;

    return {
        favorites,
        isFavorite: (productId && favorites) ? favorites.includes(productId) : false,
        toggleFavorite: () => productId && toggleFavorite(productId),
    };
}
