import {useContext} from "react";
import FavoritesContext from "@providers/FavoritesProvider";

export function useFavorites(productId?: number) {
    const context = useContext<FavoritesContext | null>(FavoritesContext);

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
