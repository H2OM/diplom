import dynamic from "next/dynamic";
import {ReactNode} from "react";
import '@components/authorization/authorization.scss';

const Authorization = dynamic(
    () => import("@components/authorization/client/Authorization"),
    {ssr: false}
);

export default function Layout({children}: { children: ReactNode }) {
    return (
        <Authorization>
            {children}
        </Authorization>
    );
}