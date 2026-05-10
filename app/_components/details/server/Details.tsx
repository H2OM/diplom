import Link from 'next/link';
import '../details.scss';
import Image from "next/image";
import Interaction from '../client/Interaction';
import TabsNavigation from '../client/TabsNavigation';
import DetailsSlider from '../client/DetailsSlider';
import MiniSlider from "@ui/miniSlider/MiniSlider";
import {Product, ProductDetails} from "@_types/product";
import {Fragment} from "react";

type DetailsProps = {
    data: {
        product: ProductDetails;
        related: Product[];
    }
}
// TODO НУЖЕН ТЕСТ
export default async function Details({data}: DetailsProps) {
    const {product, related} = data;

    return (
        <section className="Details section">
            <div className="grid">
                <h1 className="title title_black">{product.brand} {product.title} <span
                    className="title__type">{product.type}</span>
                </h1>
                <div className="Details__split">
                    <div className="Details__split__content">
                        <div>
                            <div className="Details__split__content__price">{product.price} ₽</div>
                            <div className="Details__split__content__subtitle _art">
                                Артикул - {product.article}
                            </div>
                            <div className="Details__split__content__subtitle _color">
                                Цвет - {product.color}
                            </div>
                        </div>
                        {product.colors.length !== 0 &&
                            <>
                                <div className="Details__split__content__title">Другие цвета:</div>
                                <div className="Details__split__content__colors">
                                    {
                                        product.colors.map(color => {
                                            const isCurrent = color.id === product.id;

                                            const content = (
                                                <Image
                                                    className={`Details__split__content__colors__type ${isCurrent ? "_active" : ""}`}
                                                    src={`/img/${color.image.trim()}`}
                                                    height={100}
                                                    width={110}
                                                    quality={100}
                                                    priority
                                                    alt="Цвет"
                                                    key={color.id}
                                                />
                                            );

                                            return color.id === product.id
                                                ? <Fragment key={color.id}>{content}</Fragment>
                                                : <Link key={color.id} href={`/product/${color.id}`}>
                                                    {content}
                                                </Link>;
                                        })
                                    }
                                </div>
                            </>
                        }
                        <Interaction sizes={product.size} productId={product.id}/>
                    </div>
                    <DetailsSlider slides={product.slider_images.split(',')} mainImage={product.image}/>
                </div>
                <div className="Details__tabs">
                    <TabsNavigation>
                        <div className="Details__tabs__nav__tab _active" data-link={"Описание"}>
                            Описание
                        </div>
                        <div className="Details__tabs__nav__tab" data-link={"Товар"}>
                            О товаре
                        </div>
                        <div className="Details__tabs__nav__tab" data-link={"Отзывы"}>
                            Отзывы
                            {/* <span className="Details__tabs__nav__tab__value"> {Array.isArray(feedback) ? feedback.length : ""}</span> */}
                        </div>
                    </TabsNavigation>
                    <div className="Details__tabs__content">
                        <div className="Details__tabs__content__block _active"
                             data-link={"Описание"}>
                            {product.description}
                        </div>
                        <div className="Details__tabs__content__block" data-link={"Товар"}>
                            //Характеристики
                        </div>
                        <div className="Details__tabs__content__block" data-link={"Отзывы"}>
                            //Отзывы
                        </div>
                    </div>
                </div>
                {related.length > 0 && <MiniSlider title={"Похожие товары"} products={related}/>}
            </div>
        </section>
    )
}