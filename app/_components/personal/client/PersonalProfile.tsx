'use client';

import useUser from "@hooks/useUser";
import Spinner from "@ui/spinner/Spinner";
import Link from "next/link";
import LoadScreen from "@ui/loadScreen/LoadScreen";

export default function PersonalProfile() {
    const {user, isPending} = useUser();

    if (!user) return <Spinner/>;

    const parsedPhone = `+7 (${user.phone.slice(1, 4)}) ${user.phone.slice(4, 7)}-${user.phone.slice(7, 9)}-${user.phone.slice(9, 11)}`;

    return (
        <>
            {isPending && <LoadScreen><Spinner/></LoadScreen>}
            <h2 className="title title_black title_small">Профиль</h2>
            <div className="Personal__split__content__split">
                <div className="Personal__split__content__split__block">
                    <h3 className="Personal__split__content__split__block__title">Личная информация</h3>
                    <div className="Personal__split__content__split__block__field">
                        Имя: <span data-info>{user.first_name}</span>
                    </div>
                    <div className="Personal__split__content__split__block__field">
                        Фамилия: <span data-info>{user.second_name}</span>
                    </div>
                    <div className="Personal__split__content__split__block__field">
                        Возраст: <span data-info>{user.age}</span>
                    </div>
                    <div className="Personal__split__content__split__block__field">
                        Пол: <span data-info>{user.gender === "female" ? "Женский" : "Мужской"}</span>
                    </div>
                </div>
                <div className="Personal__split__content__split__block">
                    <h3 className="Personal__split__content__split__block__title">Контактная информация</h3>
                    <div className="Personal__split__content__split__block__field">
                        Почта: <span data-info>{user.email}</span>
                    </div>
                    <div className="Personal__split__content__split__block__field">
                        Номер телефона: <span data-info>{parsedPhone}</span>
                    </div>
                </div>
            </div>
            <Link href={"/personal/edit"} className="btn">Изменить информацию</Link>
        </>
    )
}