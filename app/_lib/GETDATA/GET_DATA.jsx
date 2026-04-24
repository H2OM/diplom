// import {headers} from "next/headers";

export default async function GET_DATA({controller, action, cache= "no-cache"}) {
    // const headersList = await headers();
    // const protocol = headersList.get("x-forwarded-proto") ?? "http";
    // const host = headersList.get("x-forwarded-host") ?? headersList.get("host");
    //
    // console.log(`${protocol}://${host}`);

    return await fetch(`${process.env.NEXT_API_URL}/${controller}/${action}`, {method: "GET", cache: cache})
        .then(data=> {
            if(!data.ok) {
                return false;
            }
            return data.json();
        }).catch((e)=> {
            console.error("GET_DATA error:", e);

            return false;
        });
}