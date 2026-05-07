import {useContext} from "react";
import BasketContext from "@providers/BasketProvider";
import {ProviderBasket} from "@_types/providers";

export function useBasket() {
    const context = useContext<ProviderBasket | null>(BasketContext);

    if (!context) {
        throw new Error('Basket provider is missing');
    }

    const { basket, isPending, add, setCount, decrement, remove, clear, toggle } = context;

    return {
        basket,
        inBasket: (id: number, size: string) => basket.find(p => (p.id === id && p.size === size)),
        isBasketPending: isPending,
        add,
        setCount,
        decrement,
        remove,
        clear,
        toggle,
    };
}
