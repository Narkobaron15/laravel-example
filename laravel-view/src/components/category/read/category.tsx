import React from "react";

import http_common from "../../../http_common";
import { ICategoryReadModel } from "../../../models/category"
import { Link } from "react-router-dom";
import ShortenString from "../../../utilities/shortenstring";

type CategoryArgs = {
    params: ICategoryReadModel,
    removeCallback: () => void,
}

const   MAX_TITLE_LENGTH = 40,
        MAX_DESCRIPTION_LENGTH = 65;

export default function Category({ params, removeCallback }: CategoryArgs) {
    return (
        <tr>
            <th scope="row">{params.id}</th>
            <td>
                <img className="category-pic mx-4" src={params.picture.sm} alt={`${params.name} category`} />
            </td>
            <td>{ShortenString(params.name, MAX_TITLE_LENGTH)}</td>
            <td>{ShortenString(params.description, MAX_DESCRIPTION_LENGTH)}</td>
            <td>
                {/* Edit button */}
                <Link to={`/categories/edit/${params.id}`} className="tailwind-btn" >
                    <i className="fa-solid fa-pen-to-square"></i>
                </Link>

                {/* Delete button */}
                <button className="tailwind-btn ml-1" onClick={async () => {
                    await http_common.delete(`/api/categories/${params.id}`);
                    removeCallback();
                }}>
                    <i className="fa-regular fa-trash-can"></i>
                </button>
            </td>
        </tr>
    );
}