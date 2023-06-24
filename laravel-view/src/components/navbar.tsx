import React from "react";
import { Link } from "react-router-dom";

import openimg from '../img/open.svg';

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
                <div className="navbar-default" id="navbar-default">
                    <ul>
                        <li>
                            <Link to="/">Головна</Link>
                        </li>
                        <li>
                            <Link to="/all">Усі категорії</Link>
                        </li>
                        <li>
                            <Link to="/create">Створити</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    );
}