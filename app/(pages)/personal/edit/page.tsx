import dynamic from "next/dynamic";

const PersonalProfileEdit = dynamic(
    () => import("@components/personal/client/PersonalProfileEdit"),
    {ssr: false}
);

export default function Page() {
    return <PersonalProfileEdit/>;
}