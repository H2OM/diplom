import Catalog from "@components/catalog/server/Catalog";

export default async function Page({params, searchParams}: {
    params: Promise<{}>;
    searchParams: Promise<Record<string, string>>;
}) {
    return <Catalog promiseParams={params} promiseSearchParams={searchParams}/>
}
