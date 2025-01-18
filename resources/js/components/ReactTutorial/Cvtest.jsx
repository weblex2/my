import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { createRoot } from 'react-dom/client';
import PDItem from './Components/PDItem'; // Die AUItem-Komponente importieren

// Die Component, die den Index empfängt
const AUItem = ({ index, item }) => {
    return (
        <div>
            <h3>Index: {index}</h3>
            <p>Name: {item.name ? item.name : "Kein Name"}</p>
            <p>Wert: {item.value}</p>
        </div>
    );
};

const App = () => {
    const [cvdata, setCvdata] = useState({});
    const [auLength, setAuLength] = useState(0); // Länge von AU, falls benötigt
    const [pdLength, setPdLength] = useState(0);
    const [knLength, setKnLength] = useState(0);
    const [edit, setEdit] = useState(false);

    // Lade Daten von der API
    useEffect(() => {
        axios.get('/cv/json/1')
            .then(res => {
                setCvdata(res.data);
                setAuLength(res.data.AU.length);
                setPdLength(res.data.PD.length);
                setKnLength(res.data.KN.length);
                setEdit(res.data.edit);
            })
            .catch(err => console.log(err));
    }, []);

    // Bedingtes Rendering: Nur rendern, wenn cvdata.AU existiert und ein Array ist
    if (!Array.isArray(cvdata.AU)) {
        return <div>Loading...</div>;
    }

    return (
        <div>
            <h1>Index von AU-Daten</h1>
            {cvdata.AU.map((item, index) => (
                <AUItem key={item.id} index={index} item={item} />
            ))}
        </div>
    );
};

if (document.getElementById('App')) {
    const root = createRoot(document.getElementById('App'));
    root.render(<App />);
}