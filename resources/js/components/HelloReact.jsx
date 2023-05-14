// resources/js/components/HelloReact.js

import React from 'react';
import { createRoot } from 'react-dom/client';
import axios from 'axios';
import { useState } from 'react';
//import { Table } from './HelloTable';


const data = "";

export default function HelloReact() {
    return (
        <div className='react'>
        <React.StrictMode>
        <h1>Hello React!</h1>
        <div className='content'>Hello React!</div>
        </React.StrictMode>
        </div>
    );
}

export function Table({theadData, tbodyData}) {
    

     return (
       <table>
           <thead>
              <tr>
               {theadData.map(heading => {
                 return <th key={heading}>{heading}</th>
               })}
             </tr>
           </thead>
           <tbody>
               {tbodyData.map((row, index) => {
                   return <tr key={index}>
                       {theadData.map((key, index) => {
                            return <td key={row[key]}>{row[key]}</td>
                       })}
                 </tr>;
               })}
           </tbody>
       </table>
    );
    }

if (document.getElementById('reactRoot')) {
    const root = createRoot(document.getElementById('reactRoot'));
    root.render(
        <HelloReact tab="home" />
    );
}