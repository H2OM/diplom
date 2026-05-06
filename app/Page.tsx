import './page.scss';
import {mediaAPI} from "@api";
import MainSlider from "@components/mainSlider/client/MainSlider";
import MiniSlider from "@components/ui/miniSlider/MiniSlider";
import {SliderMain, SliderProducts} from "@/_types/sliders";

export default async function Page() {
    const data = await mediaAPI.getMainInfo();

    if(!data.success || !data.data) {
        // TODO сделать блок ошибки или загрузки если не success
    }

    const {slider, popular, sales}: {
        slider: SliderMain[],
        popular: SliderProducts[],
        sales: SliderProducts[]
    } = data.data ?? [];

    return (
        <section className="FirstPage section">
            <div className="Slider">
                <div className="grid">
                    {slider && <MainSlider slides={slider}/>}
                </div>
            </div>
            <div className="grid">
                <div className="block">
                    {Array.isArray(popular) && popular.length > 0 && <MiniSlider title={"Популярные товары"} products={popular}/>}
                </div>
                <div className="block">
                    {Array.isArray(sales) && sales.length > 0 && <MiniSlider title={"Главные скидки"} products={sales}/>}
                </div>
            </div>
        </section>
    )
}