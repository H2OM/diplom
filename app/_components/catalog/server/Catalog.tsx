import {notFound} from "next/navigation";
import '../catalog.scss';
import Filters from "@ui/filters/filters";
import Cart from "@ui/cart/Cart";
import {catalogAPI} from "@api";
import {CatalogFilters} from "@_types/catalog";
export default async function Catalog({promiseParams, promiseSearchParams}: {
    promiseParams: Promise<{ type?: string; }>
    promiseSearchParams: Promise<CatalogFilters>;
}) {
    const searchParams: CatalogFilters = await promiseSearchParams;
    const params = await promiseParams;

    if(params.type) {
        searchParams.category = params.type.toLowerCase();
    }

    const catalog = await catalogAPI.get(searchParams);

    let title = searchParams.category;

    switch (title) {
        case undefined:
        case "":
            title = "";
            break;
        case "woman":
            title = "Женская обувь";
            break;
        case "man":
            title = "Мужская обувь";
            break;
        case "kids":
            title = "Детская обувь";
            break;
        case "all":
            title = "Унисекс";
            break;
        default:
            notFound();
    }
// TODO !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    return (
        <section className="Catalog section">
            <div className="grid">
                {catalog !== undefined ?
                    <>
                        <div className="Catalog__title">
                            <h1 className="title title_black">{(title !== "" && title !== undefined) ? title : "Каталог"}</h1>
                            <span className="title__count"> - {catalog.count}</span>
                        </div>
                        {(Object.keys(sp).length !== 0 || (Array.isArray(catalog.goods) && catalog.goods.length > 0)) ?
                            <Filters main={catalog.filters} category={title === ""}/> : null}
                        {catalog.count > 0 ?
                            <>
                                <div className="Catalog__content">
                                    {catalog.goods.map(goods => {
                                        return (
                                            <Cart key={goods.id} info={goods}/>
                                        )
                                    })}
                                </div>
                                {/* <div className="Catalog__navigation">
                                    <div className="btn btn_big Catalog__navigation__btn">Показать еще</div>
                                    <div className="pagination">
                                        <button className="pagination__btns">
                                        </button>
                                        <div className="pagination__titles" onClick={({target})=>{
                                            if(target.classList.contains("pagination__titles__tab")) {
                                                target.parentElement.querySelectorAll(".pagination__titles__tab_active").forEach(elem=>{
                                                    elem.classList.remove("pagination__titles__tab_active");
                                                });
                                                target.classList.add("pagination__titles__tab_active");
                                            }
                                        }}>
                                            <div className="pagination__titles__tab pagination__titles__tab_active">1</div>
                                            <div className="pagination__titles__tab">2</div>
                                            <div className="pagination__titles__tab">3</div>
                                            <div className="pagination__titles__dot">...</div>
                                            <div className="pagination__titles__tab">10</div>
                                        </div>
                                        <button className="pagination__btns">
                                        </button>
                                    </div>
                                </div> */}
                            </> :
                            <div className="title title_black" style={{marginTop: "20px", fontSize: "22px"}}>Товаров не
                                найдено</div>}
                    </> : <div className="title title_black">Не удалось загрузить каталог</div>}
            </div>
        </section>
    )
}