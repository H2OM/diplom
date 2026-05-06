'use client';
import { useContext, useEffect, useState } from "react";
import { usePathname } from "next/navigation";
import ClientContext from "@/lib/ClientProvider.jsx";
import { useRouter } from "next/navigation";
import './autorization.scss';
import Link from "next/link";
import Spinner from "@/comps/ui/spinner/spiner";
import Loadscreen from "@/comps/ui/loadScreen/loadscreen";

export default function Autorization ({children}) {
    const { isAuth, loading } = useContext(ClientContext);
    const router = useRouter();
    const pathname = usePathname();
    const [isRoled, setRoled] = useState(!pathname.includes("login"));
    const [UIready, setUIStatus] = useState(false);

    useEffect(()=> {
        pathname.includes("login") ? setRoled(false) : setRoled(true);
    }, [pathname]);


    useEffect(()=> {
        setUIStatus(true);
    }, []);

    useEffect(()=> {
        if(isAuth) {
            router.push("/");
        }
    }, [isAuth]);

    if(isAuth === 'loading' || isAuth) return <Spinner/>

    if(!isAuth) {
        return (
            <section className="Autorization section">
                <div className="grid">
                    <h1 className="title title_black" style={{textAlign: "center"}}>Авторизация</h1>
                    <div className="Autorization__tabs">
                        <div className={"Autorization__tabs__roller " + (isRoled ? "Autorization__tabs__roller_roll" : "")}></div>
                        <Link href={"login"} className={"Autorization__tabs__btn " + (isRoled ? "" : "Autorization__tabs__btn_active") + (loading ? " Autorization__tabs__btn_block" : "")} onClick={(e)=>{
                            if(loading) e.preventDefault();
                        }}>Вход</Link>
                        <Link href={"signin"} className={"Autorization__tabs__btn " + (isRoled ? "Autorization__tabs__btn_active" : "") + (loading ? " Autorization__tabs__btn_block" : "")} onClick={(e)=>{
                            if(loading) e.preventDefault();
                        }}>Регистрация</Link>
                    </div>
                    <div className="Autorization__content">
                        <Loadscreen/>
                        {UIready ? children :<div className="Autorization__spinnerWrap"><div className="Autorization__spinnerWrap__spinner"></div></div> }
                    </div>
                </div>
            </section>
        )
    }
}