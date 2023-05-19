import React from 'react';
import axios from 'axios';
import { Component } from "react"
import { createRoot } from 'react-dom/client';
import { StrictMode } from "react";
import { useEffect, useState } from "react";

//
/*
const [friesen, setFriesen] = useState("");
const getFriesen = () =>{
    axios.get('/getFriesen')
    .then(response => response.data)
    .then(
        (data) => {
            
            setFriesen({ data });   
            console.log(friesen.data);         
        },
        (error) => {
            alert(error);
        }
    )
}

*/

export function Frise() {
    const [ friesen, setFriesen ] = useState([]);
    const fetchDetails = () => {
        //const key = e.target.getAttribute('key');
        console.log('We need to get the details for key');
    }

    const handler = (_selectedRow) => {
        //console.log("selectedRow:", selectedRow)
    }

    useEffect(() => {
        const fetchata = async () => {
        
            const response = await fetch(
              '/getFriesen');
               const data = await response.json();
               console.log(data.results);     
               //use only 3 sample data
               setFriesen( data.results );
           
        }
      
        // Call the function
        fetchata();
     }, []);

    return (
        <React.StrictMode>
        <div className='frise w-full'>
            <div className="grid w-full grid-cols-12 gap-1">
                <div className="col-span-6">
                    <table className="friesenTable w-full">
                    <thead>
                        <tr>
                        <th>PLZ</th>
                        <th>NAME</th>
                        <th>Ort</th>
                        </tr>   
                    </thead>   
                    <tbody>
                    {friesen.map(friese => 
                        <tr key={friese.id} onClick={() => this.handler('hiHo')}>
                            <td>{friese.plz}</td>
                            <td>{friese.name}</td>
                            <td>{friese.city}</td>
                        </tr>)}
                
                    </tbody>
                    </table>
                </div>
                <div className='col-span-7'>Details</div>        
            </div>
        </div>    
        </React.StrictMode>
    );
}


if (document.getElementById('reactFrise')) {
    const root = createRoot(document.getElementById('reactFrise'));
    root.render(
        <Frise tab="home" />
    );
}
