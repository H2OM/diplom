'use client';

import './cart.scss';
import Image from "next/image";
import {Product} from "@_types/product";
import {Icons} from "@components/ui/icons/Icons";
import {useFavorites} from "@hooks/useFavorites";
import {useBasket} from "@hooks/useBasket";
import Link from "next/link";

export default function Cart({product, isSlider = false}: { product: Product; isSlider?: boolean; }) {
    const {isFavorite, isPending, toggle} = useFavorites(product.id);
    const {add} = useBasket();

    const title: string = (product.title.length > 19 ? product.title.slice(0, 19) + "... " : product.title);

    return (
        <div className={"cart-wrap" + (isSlider ? " cart-wrap_slide" : "")}>
            <div className="cart"
                 onMouseEnter={({currentTarget}) => {
                     let target = currentTarget;

                     if (!target.classList.contains("cart")) target = target.closest(".cart")!;

                     target.classList.remove("cart_unactive");
                     target.classList.add("cart_active");
                 }}
                 onMouseLeave={({currentTarget}) => {
                     let target = currentTarget;

                     if (!target.classList.contains("cart")) target = target.closest(".cart")!;

                     target.classList.remove("cart_active");
                     target.classList.add("cart_unactive");
                 }}>
                <div className={"cart__heart" + (isPending ? ' _disabled' : '')} onClick={() => toggle()}>
                    <Icons type={isFavorite() ? 'filedHeart' : 'unfiledHeart'}/>
                </div>
                <Link href={`/product/${product.id}`}>
                    <Image
                        src={`/img/${product.image.trim()}`}
                        alt={"ОШИБКА ЗАГРУЗКИ ФОТОГРАФИИ"}
                        className={"cart__img"}
                        width={0}
                        height={0}
                        sizes="100vw"
                        quality={100}
                        priority={true}
                    />
                </Link>
                <div className="cart__desc">
                    <div className="cart__desc__price cart__desc__price_new">
                        {product.price.toLocaleString('ru-RU', {
                            style: 'currency',
                            currency: 'RUB',
                        })}&nbsp;
                        {product.price_old ?
                            <span className="cart__desc__price cart__desc__price_old">
                                {product.price_old.toLocaleString('ru-RU', {
                                    style: 'currency',
                                    currency: 'RUB',
                                })}
                            </span>
                            : null
                        }
                    </div>
                    <Link href={`/product/${product.id}`} className="cart__desc__title">
                        {product.category} {title}
                    </Link>
                    <h3 className="cart__desc__sub-title">{product.brand}</h3>
                    {/*TODO вариации*/}
                    {/*TODO в наличии stock unit*/}
                </div>
                {product.stock > 0 ?
                    <button className="cart__btn btn btn_small" onClick={() => add(product.id)}>
                        В корзину
                    </button>
                    : <div>Товар закончился</div>
                }
            </div>
        </div>
    );
}