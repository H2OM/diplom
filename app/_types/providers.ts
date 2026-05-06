interface FavoritesContext {
    favorites: number[] | null;
    toggleFavorite: (id: number) => Promise<void>;
}