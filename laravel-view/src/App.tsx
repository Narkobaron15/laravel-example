import React from 'react';
import { Route, Routes } from 'react-router-dom';


import CategoryList from './components/category/read/categorylist';
import Layout from './components/layout';
import CreateCategory from './components/category/create/createcategory';
import NotFound from './components/notfound';
import UpdateCategory from './components/category/update/updatecategory';

export default function App() {

  return (
    <Routes>
      <Route path='/' element={<Layout/>}>
        <Route index element={<CategoryList />}/>
        <Route path='create' element={<CreateCategory />}/>
        <Route path='edit/:id' element={<UpdateCategory />}/>
        <Route path='*' element={<NotFound />}/>
      </Route>
    </Routes>
  );
}
