import dynamic from "next/dynamic";

const PersonalOrders = dynamic(
    () => import("@components/personal/client/PersonalOrders"),
    {ssr: false}
);

export default function Orders() {
    return <PersonalOrders/>;
}