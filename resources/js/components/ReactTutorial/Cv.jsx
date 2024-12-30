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

    const addLineBreak = (str) =>
    str.split('\n').map((subStr) => {
      return (
        <>
          {subStr}
          <br />
        </>
      );
    });
    
    function CvAusbildung(props) {
        if (props.mykey.type=="AU"){
            return <div key={props.mykey.id} mykey={props.mykey.id}>
                      <div>{parse(props.mykey.value)}</div>
                   </div>;
        }    
    }

    function CvKnowledge(props) {
        if (props.mykey.type=="KN"){
            return <span className="tag w-fit" key={props.mykey.id} mykey={props.mykey.id}>
                      {parse(props.mykey.value)}
                   </span>;
        }    
    }

    function CvPrivateData(props) {
        if (props.mykey.type=="PD"){
            return <div key={props.mykey.id} mykey={props.mykey.id} className="col-span-6">
                      <div className="header">{parse(props.mykey.name)}</div>
                      <div className="bl_text mb-5 float-left w-[60%]">{parse(props.mykey.value)}</div>
                   </div>;
        }      
    }

    function CvLaufbahn(props) {
        if (props.mykey.type=="BL"){
            return <div key={props.mykey.id} mykey={props.mykey.id} className="col-span-6">
                      <div className="col-span-2 bl"><h3>{props.mykey.header}</h3></div>
                      <div className="">{parse(props.mykey.value)}</div>
                   </div>;
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
        <div className="container mx-auto flex-auto"> 
          <div className="flex justify-center">
            <img src="../img/noppal3.jpg" className="img-me" />
          </div>
          
          <div className="cv grid grid-cols-2 gap-0 ">
              <h1 className="col-span-2 text-center">Lebenslauf</h1>
              <hr className="col-span-2 mb-10" />
              <div className="grid grid-cols-6">
                  <h2 className="col-span-6  border-red-500">Persönliche Daten</h2>
                  { Object.entries(cvdata).map(([k, v]) =>
                    Object.entries(v).map(([i, j]) =>
                        <CvPrivateData mykey={j} />
                    )
                    )        
                  }
                  <div className="col-span-6">
                      <hr className="mb-5" />
                  </div>    

                  <div className="col-span-6">
                      <h2>Ausbildung</h2>
                      { Object.entries(cvdata).map(([k, v]) =>
                        Object.entries(v).map(([i, j]) => 
                                <div><CvAusbildung mykey={j} /></div>
                        )
                        )        
                      }
                  </div>
                  
                  <div className="col-span-6">
                      <hr className="mb-5" />
                  </div> 

                  <div className="col-span-6">
                      <h2>Kenntnisse & Fähigkeiten</h2>
                  </div>  

              
                  <div className="col-span-6">
                      <div className="my-10">
                      { Object.entries(cvdata).map(([k, v]) =>
                          Object.entries(v).map(([i, j]) => 
                              <CvKnowledge mykey={j} />
                          )
                      )        
                      }
                      </div>
                  </div>


              </div>
              <div className="grid grid-cols-1 row-span-12 ml-20">
                  <h2 className="col-span-2">Berufliche Laufbahn</h2>
                  { Object.entries(cvdata).map(([k, v]) =>
                    Object.entries(v).map(([i, j]) =>
                        <CvLaufbahn mykey={j} />
                    )
                  )        
                  }
              </div>    
          </div>
          </div>
          <button className="btn" onClick={() => shoot(cvdata)}>Log Data</button>
          <button onClick={() => newAU(au_length)}>Add</button>
        </div>
  );
}

if (document.getElementById('cv')) {
    const root = createRoot(document.getElementById('cv'));
    root.render(<Cv />);
}