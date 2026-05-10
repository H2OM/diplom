import {notFound} from "next/navigation";
import {catalogAPI} from "app/_api";
import Details from "app/_components/details/server/Details";

export default async function Page({params}: {
    params: Promise<{ id: string; }>
}) {
    const {id} = await params;
    const data = await catalogAPI.getProductById(id);

    if(!data.success) {
        return notFound();
    }

    console.log(data)

    return <Details data={data.data}/>;
}