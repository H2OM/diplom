'use client'

import Link from "next/link";
import {Icons} from "@ui/icons/Icons";
import {useBasket} from "@hooks/useBasket";
import ClientContext from "@lib/ClientProvider";
import {useContext} from "react";
import {useFavorites} from "@hooks/useFavorites";

export default function HeaderButtons () {
    const { isAuth } = useContext(ClientContext);
    const { basket } = useBasket();
    const { favorites } = useFavorites();

    return (
        <div className="Header__btns">
            <Link href={"/basket"} className="Header__btns__btn btn">
                <Icons type={'basketWhite'}/>
                <div className="Header__btns__btn__count">{basket.length}</div>
            </Link>
            <Link href={"/favorites"} className="Header__btns__btn btn">
                <Icons type={'filedHeart'}/>
                <div className="Header__btns__btn__count">{favorites.length}</div>
            </Link>
            {isAuth == 'loading'
                ?   <button className="Header__btns__btn btn btn_disabled">loading...</button>
                :   <Link className="Header__btns__btn btn" href={isAuth == true ? "/personal" : "/autorization/login"}>
                        <Icons type={'userWhite'}/>
                    </Link>
            }
        </div>
    )
}