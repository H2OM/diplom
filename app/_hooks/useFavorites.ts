import {useContext} from "react";
import FavoritesContext from "@providers/FavoritesProvider";
import {Favorites} from "@_types/providers";

export function useFavorites(productId?: number) {
    const context = useContext<Favorites | null>(FavoritesContext);

    if (!context) {
        throw new Error('Favorites provider is missing');
    }

    const { favorites, isPending, toggleFavorite } = context;

    return {
        favorites,
        isFavorite: (productId && favorites) ? favorites.includes(productId) : false,
        isFavoritesPending: isPending,
        toggleFavorite: () => productId && toggleFavorite(productId),
    };
}
