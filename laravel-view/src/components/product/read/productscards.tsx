import React from "react";
import { Link } from "react-router-dom";

export default function ProductsCardsComponent() {
    return (
        <>
            <h1>Привіт козаки!</h1>
            <ul className="flex justify-center">
                <li>
                    <Link className="tailwind-btn" to="/products">Перейти до продуктів</Link>
                </li>
                <li>
                    <Link className="tailwind-btn ml-5" to="/categories">Перейти до категорій</Link>
                </li>
            </ul>
        </>
    );
}