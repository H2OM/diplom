import Catalog from "@/comps/catalog/server/catalog";

export default function Page ({params, searchParams}) {
    return <Catalog params={params} searchParams={searchParams}/>
}