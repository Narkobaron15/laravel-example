import React, { useState, useEffect } from "react";

import { ICategoryReadItem } from "../../../models/category";
import Category from "./category";
import http_common from "../../../http_common";
import { callErrorToast } from "../errortoast";

export default function CategoryList() {
  const [items, setList] = useState<ICategoryReadItem[]>([]);
  useEffect(() => {
    http_common.get(`/api/categories`)
      .then(r => setList(r.data))
      .catch(callErrorToast);
  }, []);


  return (
    <table>
      <colgroup>
        <col className="w-1/12" />
        <col className="w-1/12" />
        <col className="w-3/12" />
        <col className="w-6/12" />
        <col className="w-1/12" />
      </colgroup>
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">{/* Image */}</th>
          <th scope="col">Назва</th>
          <th scope="col">Опис</th>
          <th scope="col">Дії</th>
        </tr>
      </thead>
      <tbody>
        {items?.map((item, index) =>
          <Category params={item} key={index}
            removeCallback={() => setList(items?.filter(val => val !== item))} />)}
      </tbody>
    </table>
  )
}