'use client';

import './cart.scss';
import Image from "next/image";
import { useState } from "react";
import { useDispatch } from "react-redux";
import { useRouter } from "next/navigation";
import {Product} from "@/_types/products";
import {Icons} from "@components/ui/icons/Icons";
import {useFavorites} from "@hooks/useFavorites";

type Modal = {
    show: boolean;
    sizes: boolean;
}

export default function Cart ({ product, isSlide = false }: { product: Product; isSlide: boolean; }) {
    const { favorites, isFavorite, toggleFavorite } = useFavorites(product.id);
    const [modal, setModal] = useState<Modal>({show: false, sizes: false});
    const [sizesNav, setSizesNav] = useState<number>(0);
    const dispatch = useDispatch();
    const navigate = useRouter();

    const sizes: string[] = product.size.split(".");
    const title: string = (product.title.length > 19 ? product.title.slice(0,19) + "... " : product.title);

    return (
        <div className={"cart-wrap" + (isSlide ? " cart-wrap_slide" : "")}>
            <div className="cart"
                onClick={({currentTarget})=> {
                    switch(currentTarget.classList.value) {
                        case "cart__btn btn btn_small":
                        case "cart__sizes__nav":
                        case "cart__sizes":
                        case "bi bi-suit-heart":
                            break;
                        default:
                            if(modal.sizes) {
                                setModal({...modal, sizes: false});

                                break;
                            }

                            navigate.push(`/catalog/${product.category}/${product.id}`);

                            break;
                    }
                }}
                onMouseEnter={({currentTarget})=> {
                    let target = currentTarget;

                    if(!target.classList.contains("cart")) target = target.closest(".cart")!;

                    target.classList.remove("cart_unactive");
                    target.classList.add("cart_active");

                    setModal({...modal, show: true});
                }}
                onMouseLeave={({currentTarget})=> {
                    let target = currentTarget;

                    if(!target.classList.contains("cart")) target = target.closest(".cart")!;

                    target.classList.remove("cart_active");
                    target.classList.add("cart_unactive");

                    setModal({sizes: false, show: false});
                }}>
                {favorites &&
                    <div className="cart__heart" onClick={()=> toggleFavorite()}>
                        <Icons type={isFavorite ? 'filedHeart' : 'unfiledHeart'}/>
                    </div>
                }
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
                <div className="cart__desc">
                    <div className="cart__desc__price cart__desc__price_new">
                        {product.price} ₽
                        {product.price_old &&
                            <span className="cart__desc__price cart__desc__price_old">{product.price_old} ₽</span>
                        }
                    </div>
                    <h3 className="cart__desc__title">{product.type} {title}</h3>
                    <h3 className="cart__desc__title">{product.brand}</h3>
                    <div className="cart__desc__size">{sizes.join(" ")}</div>
                </div>
                {(modal.show && !modal.sizes) &&
                    <button className="cart__btn btn btn_small" onClick={()=> setModal({...modal, sizes: true})}>
                        В корзину
                    </button>
                }
                <div className="cart__sizes" style={(modal.sizes && modal.show) ? {} : {display: "none"}}>
                    <div className="cart__sizes__wrapper" style={{transform: `translateX(-${sizesNav * 20}%)`}}>
                        { sizes.length !== 0 ?
                            sizes.map((size, i)=> {
                                return(
                                    <button key={i} className="cart__sizes__wrapper__btn" onClick={async (e)=>{
                                        e.stopPropagation();
                                        statusModals({...modals, isSizes: false});
                                        await dispatch(basketFetch({url: '/api/basket/add-basket', m:"POST", b: {article: Article, size: value}}));
                                    }}>{size}</button>
                                )
                            }) : <div>Товар закончился</div>
                        }
                    </div>
                    <div className="cart__sizes__nav">
                        { sizes.length !== 0 ?
                            sizes.map((_, i)=> {
                                if(!(i % 5 === 0)) return null;

                                return (
                                    <div className="cart__sizes__nav__tab" key={i}
                                        onClick={(e)=> {
                                            e.stopPropagation();
                                            setSizesNav(i);
                                        }}
                                    ></div>
                                );
                           }) : <div>Товар закончился</div>
                        }
                    </div>
                </div>
            </div>
        </div>
    )
}