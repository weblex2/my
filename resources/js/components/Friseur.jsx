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
    const [ actual, setActual ] = useState([]);
    const fetchDetails = (id) => {
        //const key = e.target.getAttribute('key');
        console.log('We need to get the details for key', id);
    }

    const showDetails = (friese) => {
        // Here
        //console.log(`Name is: ${friese.name}, Age: ${friese.id}, Role: ${friese.id}`)
        console.log(friese);
        setActual(friese);
      }

    useEffect(() => {
        const fetchata = async () => {
            const response = await fetch(
              '/getFriesen');
               const data = await response.json();
               console.log(data.results);     
               setFriesen( data.results );
               setActual(data.results[0]);
           
        }
        // Call the function
        fetchata();
     }, []);

    return (
        <React.StrictMode>
        <div className='frise w-full'>
            <div className="grid grid-cols-12 gap-4 w-full">
                <div className="col-span-12 bg-slate-300">
                    <div id="search">
                        <form method="POST" action="/friese/search">
                        <div>Suche:</div> 
                        <div>PLZ:<input type="text" name="plz"></input></div>
                        <div><button className='btn'>Search</button></div>
                        </form>
                    </div>
                </div>    
                <div className="col-span-4">
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
                        <tr key={friese.id} data-item={friese.id} onClick={() => showDetails(friese)}>
                            <td>{friese.plz}</td>
                            <td>{friese.name}</td>
                            <td>{friese.city}</td>
                        </tr>)}
                    </tbody>
                    </table>
                    
                </div>
                <div className='col-span-8'>
                    <div>
                        <span className="p-3">Name</span>
                        <span className="p-3">{actual.name}</span>
                    </div>
                    <div>
                        <span className="p-3">Email</span>
                        <span className="p-3">{actual.email}</span>
                    </div>
                    <div className="frisenImages">
                        <img src={"img/friese24/"+ actual.id + "/" + actual.pic}></img> 
                    </div>
                </div>        
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
