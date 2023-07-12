import React from "react";

import { IProductReadModel } from "../../../models/product";
import arrowimg from '../../../assets/arrow.svg'
import ShortenString from "../../../pipes/shortenstring";
import { Link } from "react-router-dom";

type ProductCardArgs = {
    product: IProductReadModel,
};

const MAX_HEADER_LENGTH = 45,
    MAX_DESCRIPTION_LENGTH = 128;

export default function ProductCardComponent({ product }: ProductCardArgs) {
    const productLink = `/products/details/${product.id}`;

    return (
        <div className="card">
            <Link to={productLink}>
                <img className="primary-img" src={product.images[product.images.length - 1].lg} alt="" />
            </Link>
            <div className="p-5">
                <Link to={productLink}>
                    <h5>{ShortenString(product.name, MAX_HEADER_LENGTH)}</h5>
                </Link>
                <p>{ShortenString(product.description, MAX_DESCRIPTION_LENGTH)}</p>
                <Link to={productLink} className="tailwind-btn-dark">
                    Детальніше
                    <img src={arrowimg} alt="Arrow" className="w-3.5 h-3.5 ml-2 mt-0.5" />
                </Link>
            </div>
        </div>

    );
}