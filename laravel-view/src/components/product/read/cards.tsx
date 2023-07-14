import React from "react";

import api_common from "../../../api_common";
import { callErrorToast } from "../../errortoast";
import { IProductReadModel } from "../../../models/product";
import ProductCardComponent from "./productcard";

export default function CardsComponent() {
    const [products, setProducts] = React.useState<IProductReadModel[]>();
    React.useEffect(() => {
        api_common.get('/products')
        .then(r => setProducts(r.data))
        .catch(callErrorToast);
    }, []);

    return products?.length === 0
    ? (
      <div className="text-center">
        <h1>Список продуктів пустий</h1>
        <a href="/products/create" className="tailwind-btn text-lg">Додати новий продукт</a>
      </div>
    )
    : (
        <div className="flex flex-row flex-wrap justify-between mx-8">
            {products?.map((product, index) => <ProductCardComponent key={index} product={product} />)}
        </div>
    );
}