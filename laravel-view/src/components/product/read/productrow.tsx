import React from "react";
import { Link } from "react-router-dom";
import http_common from "../../../http_common";
import { IProductReadModel } from "../../../models/product";

type ProductArgs = {
    params: IProductReadModel,
    removeCallback: () => void,
}

export default function ProductRowComponent({ params, removeCallback }: ProductArgs) {
    return (
        <tr>
            <th scope="row">{params.id}</th>
            <td>
                <img className="category-pic mx-4" src={params.primary_image?.sm} alt={params.name} />
            </td>
            <td>{params.name}</td>
            <td>{params.description}</td>
            <td>₴ {params.price}</td>
            <td>{params.category_name}</td>
            <td>
                {/* Edit button */}
                <Link to={`/products/edit/${params.id}`} className="tailwind-btn" >
                    <i className="fa-solid fa-pen-to-square"></i>
                </Link>

                {/* Delete button */}
                <button className="tailwind-btn ml-1" onClick={async () => {
                    await http_common.delete(`/api/products/${params.id}`);
                    removeCallback();
                }}>
                    <i className="fa-regular fa-trash-can"></i>
                </button>
            </td>
        </tr>
    );
}