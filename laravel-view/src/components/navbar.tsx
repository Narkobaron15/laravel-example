import React from "react";
import { Link } from "react-router-dom";

import openimg from '../assets/open.svg';

export default function Navbar() {
    return (
        <nav>
            <div className="container">
                <Link to="/">
                    <span className="title-logo">CRUD Project</span>
                </Link>
                <button data-collapse-toggle="navbar-default" type="button" aria-controls="navbar-default" aria-expanded="false">
                    <span className="sr-only">Open main menu</span>
                    <img src={openimg} aria-hidden="true" alt="Open menu" />
                </button>
                <div className="hidden w-full md:block md:w-auto" id="navbar-default">
                    <ul>
                        <li>
                            <Link to="/products">Керувати продуктами</Link>
                        </li>
                        <li>
                            <Link to="/products/create">Створити продукт</Link>
                        </li>
                        <li>
                            <Link to="/categories">Усі категорії</Link>
                        </li>
                        <li>
                            <Link to="/categories/create">Створити категорію</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    );
}