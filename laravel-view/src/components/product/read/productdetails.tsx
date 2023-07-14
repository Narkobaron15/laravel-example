import React from "react";
import { useNavigate, useParams } from "react-router-dom";

import "react-responsive-carousel/lib/styles/carousel.min.css";
import { Carousel } from "react-responsive-carousel";

import { IProductReadModel } from "../../../models/product";
import api_common from "../../../api_common";
import arrowimg from '../../../assets/arrow.svg'
import { callErrorToast } from "../../errortoast";
import PriceToString from "../../../utilities/pricetostr";

export default function ProductDetails() {
    const { id } = useParams();
    const navigate = useNavigate();
    const [product, setProduct] = React.useState<IProductReadModel>();

    React.useEffect(() => {
        api_common.get(`/products/${id}`)
            .then(r => setProduct(r.data))
            .catch(e => {
                callErrorToast(e);
                navigate(`/`);
            });
    }, [id, navigate]);

    return (
        <article>
            <div className="mb-5">
                <button type="button" className="tailwind-btn" onClick={() => navigate(-1)}>
                    <img src={arrowimg} alt="Arrow" className="w-3.5 h-3.5 mr-2 mb-0.5 rotate-180 inline-block" />
                    Назад
                </button>
                {/* Breadcrumbs placeholder */}
            </div>
            <Carousel className="mb-14" showStatus={false} dynamicHeight={true} showArrows={true} showThumbs={false} stopOnHover={true} >
                {product?.images.map((img, i) => (
                    <div className="carousel-container" key={i}>
                        <img className="slider-img" src={img.xl} alt={product.name} />
                    </div>
                ))}
            </Carousel>
            <h1 className="text-left font-semibold mb-1">{product?.name ?? "Loading..."}</h1>
            <h3 className="text-6xl font-bold mb-10">{PriceToString(product?.price)}</h3>
            {/* Attributes, location placeholder */}
            <p className="text-lg">{product?.description}</p>
        </article>
    )
}