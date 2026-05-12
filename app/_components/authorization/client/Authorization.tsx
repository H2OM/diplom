'use client';

import '../authorization.scss';
import useUser from "@hooks/useUser";
import {ReactNode} from "react";
import Link from "next/link";
import LoadScreen from "@ui/loadScreen/LoadScreen";
import Spinner from "@ui/spinner/Spinner";

export default function Authorization({children, isSignIn}: { children: ReactNode; isSignIn: boolean }) {
    const {user, isPending} = useUser();

    return (
        <section className="Authorization section">
            <div className="grid">
                <h1 className="title title_black" style={{textAlign: "center"}}>Авторизация</h1>
                <div className="Authorization__tabs">
                    <div className={"Authorization__tabs__roller "
                        + (!isSignIn ? "Authorization__tabs__roller_roll" : "")}></div>
                    <Link href={"sign-in"}
                          className={"Authorization__tabs__btn"
                              + (isSignIn ? " _active" : "")
                              + (isPending ? " _block" : "")
                          }>
                        Вход
                    </Link>
                    <Link href={"sign-up"}
                          className={"Authorization__tabs__btn"
                              + (!isSignIn ? " _active" : "")
                              + (isPending ? " _block" : "")
                          }>
                        Регистрация
                    </Link>
                </div>
                <div className="Authorization__content">
                    {(isPending || user) && <LoadScreen><Spinner/></LoadScreen>}
                    {children}
                </div>
            </div>
        </section>
    )
}