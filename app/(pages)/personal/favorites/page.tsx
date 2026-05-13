import dynamic from "next/dynamic";

const PersonalFavorites = dynamic(
    () => import("@components/personal/client/PersonalFavorites"),
    {ssr: false}
);

export default function Favorites() {
    return <PersonalFavorites/>;
}