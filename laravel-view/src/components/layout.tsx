import React from 'react';
import { Outlet } from "react-router-dom";

import Navbar from './navbar';

export default function Layout() {

    return (
        <>
            <header>
                <Navbar />
            </header>
            <main>
                <Outlet />
            </main>
        </>
    );
}