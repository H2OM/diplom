import dynamic from "next/dynamic";
import '@components/basket/basket.scss';

const BasketOrder = dynamic(
    () => import("@components/basket/client/BasketOrder"),
    {ssr: false}
);

export default function Order() {
    return <BasketOrder/>;
}