import dynamic from "next/dynamic";
import '@components/favorites/favorites.scss';

const Favorites = dynamic(
    () => import("@components/favorites/client/Favorites"),
    {ssr: false}
);

export default function Page() {
    return <Favorites/>;
}
