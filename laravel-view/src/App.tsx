import React from 'react';
import { useEffect } from 'react';
import axios from 'axios';

const requestItems = () => {
  axios.get('http://laravel.spu123.com/api/categories')
       .then(r => console.log(r.data));
};

function App() {
  useEffect(requestItems, []);
  
  return (
    <>
      <header>

      </header>
      <main>
        <h1>Привіт козаки</h1>
        
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Назва</th>
              <th>Фото</th>
              <th>Опис</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>фіваф</td>
              <td>фівафіа</td>
              <td>фіва</td>
            </tr>
          </tbody>
        </table>
      </main>
    </>
  );
}

export default App;
