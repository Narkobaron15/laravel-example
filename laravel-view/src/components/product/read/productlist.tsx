import React, { useEffect, useState } from "react";
import http_common from "../../../http_common";
import { callErrorToast } from "../../errortoast";
import ProductComponent from "./product";
import { IProductReadModel } from "../../../models/product";

export default function ProductListComponent() {
    const [products, setProducts] = useState<IProductReadModel[]>();
    useEffect(() => {
        http_common.get('/api/products')
        .then(r => setProducts(r.data))
        .catch(callErrorToast);
    }, []);

    // retouch the table
    return products?.length === 0
    ? (
      <div className="text-center">
        <h1>Список продуктів пустий</h1>
        <a href="/products/create" className="tailwind-btn text-lg">Додати новий продукт</a>
      </div>
    )
    : (
      <table>
        <colgroup>
          <col className="w-1/12" />
          <col className="w-1/12" />
          <col className="w-2/12" />
          <col className="w-4/12" />
          <col className="w-1/12" />
          <col className="w-2/12" />
          <col className="w-1/12" />
        </colgroup>
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">{/* Image */}</th>
            <th scope="col">Назва</th>
            <th scope="col">Опис</th>
            <th scope="col">Ціна</th>
            <th scope="col">Категорія</th>
            <th scope="col">Дії</th>
          </tr>
        </thead>
        <tbody>
          {products?.map((item, index) =>
            <ProductComponent params={item} key={index}
              removeCallback={() => setProducts(products?.filter(val => val !== item))} />)}
        </tbody>
      </table>
    );
}