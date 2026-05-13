import dynamic from "next/dynamic";
import '@components/basket/basket.scss';

const Basket = dynamic(
    () => import("@components/basket/client/Basket"),
    {ssr: false}
);

export default function Page() {
    return <Basket/>;
}