import "@/page.scss";
import {mainAPI} from "@api";
import MainSlider from "@components/mainSlider/client/MainSlider";
import MiniSlider from "@components/ui/miniSlider/MiniSlider";
import {SliderMain, SliderProducts} from "@/_types/sliders";
import Fallback from "@ui/fallback/Fallback";

export default async function Page() {
    const data = await mainAPI.getInfo();

    console.log(data);

    const {slider, popular, sales}: {
        slider?: SliderMain[],
        popular?: SliderProducts[],
        sales?: SliderProducts[]
    } = data.data ?? {};

    return (
        <section className="FirstPage section">
            <div className="Slider">
                <div className="grid">
                    {slider && <MainSlider slides={slider}/>}
                    {!data.success && <Fallback message={data.message}/>}
                </div>
            </div>
            <div className="grid">
                <div className="block">
                    {Array.isArray(popular) && popular.length > 0 && <MiniSlider title={"Популярные товары"} products={popular}/>}
                    {!data.success && <Fallback message={data.message}/>}
                </div>
                <div className="block">
                    {Array.isArray(sales) && sales.length > 0 && <MiniSlider title={"Главные скидки"} products={sales}/>}
                    {!data.success && <Fallback message={data.message}/>}
                </div>
            </div>
        </section>
    )
}