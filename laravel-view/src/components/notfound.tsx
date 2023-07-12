import React from "react";
import { Link, useNavigate } from "react-router-dom";

export default function NotFound() {
    const navigate = useNavigate();

    return (
        <div className="text-center">
            <h1>404: Такої сторінки не існує</h1>
            <div className="flex justify-center">
                <Link className="text-xl underline mr-6" to="/">Повернутись на головну</Link>
                <button className="text-xl underline" onClick={() => navigate(-1)}>Повернутись назад</button>
            </div>
        </div>
    );
}