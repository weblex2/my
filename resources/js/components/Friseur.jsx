import React from 'react';
import axios from 'axios';
import { Component } from "react"
import { createRoot } from 'react-dom/client';
import { StrictMode } from "react";
import { useEffect, useState, setState } from "react";


export function Frise() {
    const [ inputs, setInputs] = useState({});
    const [ friesen, setFriesen ] = useState([]);
    const [ actual, setActual ] = useState([]);
    const [ plz , setPlz ] = useState('');
    
    const [form, setForm] = React.useState({
        plz: '',
    });
    
    const handleChange = (event) => {
        console.log(event.target.id);
        setForm({
          ...form,
          [event.target.id]: event.target.value,
        });
    };
    
    const handleSubmit = (event) => {
        event.preventDefault();
        let plz  = form.plz;
        axios.get('getFriesen/' + plz)
            .then(function (response) {
               let data  = response.data.results;
               console.log(data);
               //console.log(data.json());  
               setFriesen(data);
               setActual(data[0]);
            })
            .catch(function (error) {
                // handle error
                console.log(error);
            })
            .finally(function () {
                // always executed
            });
    };


    const search = async (event, data) => {
        event.preventDefault();
        const formData = new FormData();
        console.log(data);
        
        const res = await fetch("getFriesen/", {
            method: "GET",
            body: formData
            }).then((res) => res.json());
                alert(JSON.stringify(`${res.message}, status: ${res.status}`));
    };

    const showDetails = (friese) => {
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
                    <form onSubmit={handleSubmit}>
                        <div>Suche:</div> 
                        <div>PLZ:<input type="text" id="plz" name="plz" value={form.plz} onChange={handleChange}></input></div>
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
                    <table>
                        <tbody>
                        <tr>
                        <td className="p-3">Name</td>
                        <td className="p-3">{actual.name}</td>
                        </tr>
                        <tr>
                        <td className="p-3">Email</td>
                        <td className="p-3"><a href="{'mailto:'+actual.email}">{actual.email}</a></td>
                        </tr>
                        <tr>
                        <td className="p-3">Adresse</td>
                        <td className="p-3">{actual.street}</td>
                        </tr>
                        <tr>
                        <td className="p-3">PLZ / Ort</td>
                        <td className="p-3">{actual.plz} / {actual.city}</td>
                        </tr>
                        </tbody>
                    </table>
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
