import dynamic from "next/dynamic";

const PersonalProfile = dynamic(
    () => import("@components/personal/client/PersonalProfile"),
    {ssr: false}
);

export default function Page() {
    return <PersonalProfile/>;
}
