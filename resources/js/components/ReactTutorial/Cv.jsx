// resources/js/Counter.js

import axios from "axios";
import React, { useState, useEffect } from "react";
import { createRoot } from 'react-dom/client';
import parse from 'html-react-parser'

export default function Cv() {

    const [au_length, setAuLength] = useState(0);
    const shoot = (a) => {
        console.log(a);
    }
    
    function CvAusbildung(props) {
        if (props.mykey.type=="AU"){
            return <tr key={props.mykey.id} mykey={props.mykey.id}>
                      <td>{props.mykey.value}</td>
                   </tr>;
        }    
    }

    function CvKnowledge(props) {
        if (props.mykey.type=="KN"){
            return <div className="tag w-fit" key={props.mykey.id} mykey={props.mykey.id}>
                      {props.mykey.value}
                   </div>;
        }    
    }

    function CvPrivateData(props) {
        if (props.mykey.type=="PD"){
            return <tr key={props.mykey.id} mykey={props.mykey.id}>
                      <td>{parse(props.mykey.name)}</td>
                      <td>{parse(props.mykey.value)}</td>
                   </tr>;
        }      
    }

    function CvLaufbahn(props) {
        if (props.mykey.type=="BL"){
            return <tr key={props.mykey.id} mykey={props.mykey.id}>
                      <td>
                      <div className="whitespace-pre h1">{props.mykey.header}</div>
                      <div className="whitespace-pre">{props.mykey.value}</div>
                      </td>
                   </tr>;
        }  
    }

    function newAU(props) {
         let newau = {
          "id": 17,
          "type": "AU",
          "name": "",
          "value": "HomeOffice Worker",
          "header": "HomeOffice GmbH",
          "date_from": "1111-11-11",
          "date_to": "9999-12-31",
          "created_at": null,
          "updated_at": null
        };

        setAuLength(au_length => au_length + 1);

        const res = {
          ...cvdata,
          AU: {
            ...cvdata.AU,
            [au_length] : newau
          } 
        }

      setCvdata(res);  
      console.log(cvdata);

    }


  // Set the initial count state to zero, 0
    const [cvdata, setCvdata] = useState([]);
    //const [cvdataAU, setCvdataAU] = useState([]);
    useEffect(() => {
     //axios.get('https://jsonplaceholder.typicode.com/users')
     axios.get('/cv/json')
    .then(res => {
        setCvdata(res.data);
        setAuLength(au_length => res.data.AU.length); 
        console.log("au_length: "+au_length);
    })
    .catch(err => console.log(err))
    }, [])
  
  return (
    <div>
        <table className="t1">
         <thead>
          <tr>
            <th>Ausbildung</th>
          </tr>  
         </thead> 
         <tbody>
        { Object.entries(cvdata).map(([k, v]) =>
            Object.entries(v).map(([i, j]) => 
                    <CvAusbildung mykey={j} />
            )
        )        
        }
        </tbody>
        </table>


        <table className="t2">
          <thead>
          <tr>
          <th>Laufbahn</th>
          </tr>
         </thead>
         <tbody>
        { Object.entries(cvdata).map(([k, v]) =>
            Object.entries(v).map(([i, j]) =>
                <CvLaufbahn mykey={j} /> 
            )
        )        
        }
        </tbody>
        </table>


        <table className="t2">
          <thead>
          <tr>
            <th>Private Data</th>
          </tr>
         </thead>
         <tbody>
        { Object.entries(cvdata).map(([k, v]) =>
            Object.entries(v).map(([i, j]) =>
                <CvPrivateData mykey={j} /> 
            )
        )        
        }
        </tbody>
        </table>


        <table className="t1">
         <thead>
          <tr>
            <th>Knowledge</th>
          </tr>  
         </thead> 
         <tbody>
         <tr><td>
        { Object.entries(cvdata).map(([k, v]) =>
            Object.entries(v).map(([i, j]) => 
                    <CvKnowledge mykey={j} />
            )
        )        
        }
        </td></tr>
        </tbody>
        </table>

        <button className="btn" onClick={() => shoot(cvdata)}></button>
        <button onClick={() => newAU(au_length)}>Add</button>
        
      
    </div>
  )
}

if (document.getElementById('cv')) {
    const root = createRoot(document.getElementById('cv'));
    root.render(<Cv />);
}