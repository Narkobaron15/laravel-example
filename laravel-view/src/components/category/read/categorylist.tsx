import React, { useState, useEffect } from "react";

import ICategoryItem from "../../../models/category";
import Category from "./category";
import http_common from "../../../http_common";

const requestItems = (callback: Function) => {
  http_common.get(`/api/categories`)
    .then(r => callback(r.data));
};

export default function CategoryList() {
  const [items, setList] = useState<ICategoryItem[]>();
  useEffect(() => requestItems(setList), []);

  return (
    <table>
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Назва</th>
          <th scope="col">Фото</th>
          <th scope="col">Опис</th>
        </tr>
      </thead>
      <tbody>
        {items?.map((item, index) => <Category params={item} key={index} />)}
      </tbody>
    </table>
  )
}