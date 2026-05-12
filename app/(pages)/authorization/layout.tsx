'use client';

import {ReactNode, useEffect} from "react";
import '@components/authorization/authorization.scss';
import useUser from "@hooks/useUser";
import Authorization from "@components/authorization/client/Authorization";
import {usePathname, useRouter} from "next/navigation";

export default function Layout({children}: { children: ReactNode }) {
    const {user, isPending} = useUser();
    const pathname = usePathname();
    const router = useRouter();

    useEffect(() => {
        if (!isPending && user) {
            router.push("/personal");
        }
    }, [user, isPending]);

    return (
        <Authorization isSignIn={pathname.includes("sign-in")}>
            {children}
        </Authorization>
    );
}