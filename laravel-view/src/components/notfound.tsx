import React from "react";
import { Link } from "react-router-dom";

export default function NotFound() {
    return (
        <div className="text-center">
            <h1>404: Такої сторінки не існує</h1>
            <Link className="text-xl underline" to="/">Повернутись на головну</Link>
        </div>
    );
}