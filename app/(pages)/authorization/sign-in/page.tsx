import dynamic from "next/dynamic";

const SignIn = dynamic(
    () => import("@components/authorization/client/SignIn"),
    {ssr: false}
);

export default function Page() {
    return <SignIn/>;
}