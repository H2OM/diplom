import Catalog from "@components/catalog/server/Catalog";

export default function Page({params, searchParams}: {
    params: Promise<{type: string}>;
    searchParams: Promise<Record<string, string>>;
}) {
    return <Catalog promiseParams={params} promiseSearchParams={searchParams}/>
}