import {notFound} from "next/navigation";
import {catalogAPI} from "@api";
import Details from "@components/details/server/Details";

export default async function Page({params}: {params: Promise<{id: string}>}) {
    const {id} = await params;
    const data = await catalogAPI.getProductById(id);

    if(!data.success) {
        return notFound();
    }

    return <Details data={data.data}/>
}