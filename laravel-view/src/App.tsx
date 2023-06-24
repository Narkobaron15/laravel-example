import React from 'react';
import { Route, Routes } from 'react-router-dom';


import CategoryList from './components/category/read/categorylist';
import Layout from './components/layout';
import HomeComponent from './components/home';
import CreateCategory from './components/category/create/createcategory';

export default function App() {

  return (
    <Routes>
      <Route path='/' element={<Layout/>}>
        <Route index element={<HomeComponent />}/>
        <Route path='all' element={<CategoryList />}/>
        <Route path='create' element={<CreateCategory />}/>
      </Route>
    </Routes>
  );
}
