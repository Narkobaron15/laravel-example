import React from "react";
import { Link } from "react-router-dom";
import api_common from "../../../api_common";
import { IProductReadModel } from "../../../models/product";
import ShortenString from "../../../utilities/shortenstring";
import PriceToString from "../../../utilities/pricetostr";

type ProductArgs = {
    params: IProductReadModel,
    removeCallback: () => void,
}

const MAX_TITLE_LENGTH = 25,
    MAX_DESCRIPTION_LENGTH = 35;


export default function ProductRowComponent({ params, removeCallback }: ProductArgs) {
    return (
        <tr>
            <th scope="row">{params.id}</th>
            <td>
                <img className="category-pic mx-4" src={params.primary_image?.sm} alt={params.name} />
            </td>
            <td>
                <Link to={`/products/details/${params.id}`} className="underline" >
                    {ShortenString(params.name, MAX_TITLE_LENGTH)}
                </Link>
            </td>
            <td>{ShortenString(params.description, MAX_DESCRIPTION_LENGTH)}</td>
            <td>{PriceToString(params.price)}</td>
            <td>{params.category_name}</td>
            <td>
                {/* Edit button */}
                <Link to={`/products/edit/${params.id}`} className="tailwind-btn ml-1" >
                    <i className="fa-solid fa-pen-to-square"></i>
                </Link>

                {/* Delete button */}
                <button className="tailwind-btn ml-1" onClick={async () => {
                    await api_common.delete(`/products/${params.id}`);
                    removeCallback();
                }}>
                    <i className="fa-regular fa-trash-can"></i>
                </button>
            </td>
        </tr>
    );
}