import React from 'react';
import { Route, Routes } from 'react-router-dom';

import CategoryList from './components/category/read/categorylist';
import Layout from './components/layout';
import CreateCategory from './components/category/create/createcategory';
import NotFound from './components/notfound';
import UpdateCategory from './components/category/update/updatecategory';
import ProductListComponent from './components/product/read/productlist';
import CardsComponent from './components/product/read/cards';
import CreateProduct from './components/product/create/createproduct';
import UpdateProduct from './components/product/update/updateproduct';

export default function App() {
  return (
    <Routes>
      <Route path='/' element={<Layout />}>
        <Route index element={<CardsComponent />} />
        <Route path='/products'>
          <Route index element={<ProductListComponent />} />
          <Route path='create' element={<CreateProduct />} />
          <Route path='edit/:id' element={<UpdateProduct />} />
        </Route>
        <Route path='/categories'>
          <Route index element={<CategoryList />} />
          <Route path='create' element={<CreateCategory />} />
          <Route path='edit/:id' element={<UpdateCategory />} />
        </Route>
        <Route path='*' element={<NotFound />} />
      </Route>
    </Routes>
  );
}
