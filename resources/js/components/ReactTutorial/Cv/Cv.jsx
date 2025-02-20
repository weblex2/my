// resources/js/Counter.js

import axios from "axios";
import React, { useState, useEffect } from "react";
import { createRoot } from 'react-dom/client';
import parse from 'html-react-parser';
import PDItem from './Components/PDItem';
import AUItem from './Components/AUItem';
import KNItem from './Components/KNItem';
import BLItem from './Components/BLItem';
import SaveButton from './Components/SaveButton';
//import image from 'filename';

export default function Cv() {

    const [edit, setEdit] = useState(false);
    const showJson = (a) => {
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

    const handlePDDelete = (id) => {
        console.log("ID:"+id);
        const updatedPD = cvdata.PD.filter(item => item.id !== id);  // Filtere das Element mit der gegebenen id heraus
        console.log(updatedPD);
        setCvdata(prevState => ({
            ...prevState,
            PD: updatedPD  // Setze den neuen PD-Array-Status
        }));
    };

    const handleAUDelete = (id) => {
        const updatedAU = cvdata.AU.filter(item => item.id !== id);  // Filtere das Element mit der gegebenen id heraus
        setCvdata(prevState => ({
            ...prevState,
            AU: updatedAU  // Setze den neuen AU-Array-Status
        }));
    };

    const handleAddNewAU = () => {
    const newAU = {
      id: cvdata.AU.length + 1,  // Neue ID basierend auf der Länge der bestehenden AU-Array
      type: 'AU',
      name: 'Neuer Eintrag',
      value: 'Neuer Wert',
      header: 'Neue Institution',
      date_from: '2025-01-01',
      date_to: '0000-00-00',
      created_at: null,
      updated_at: null
    };

    // Ruf die addNewAU-Funktion auf, um den neuen Datensatz hinzuzufügen
    addNewAU(newAU);
    };

    const handleAddNewKN = () => {
    const newKN = {
      id: cvdata.KN.length + 1,  // Neue ID basierend auf der Länge der bestehenden AU-Array
      type: 'KN',
      name: 'Neuer Eintrag',
      value: 'Neuer Wert',
      header: 'Neue Institution',
      date_from: '2025-01-01',
      date_to: '0000-00-00',
      created_at: null,
      updated_at: null
    };

    // Ruf die addNewAU-Funktion auf, um den neuen Datensatz hinzuzufügen
    addNewKN(newKN);
    };

    // Funktion zum Hinzufügen eines neuen Datensatzes zu AU
    const addNewAU = (newAU) => {
      const updatedAU = [...cvdata.AU, newAU];  // Füge den neuen Datensatz zum bestehenden Array hinzu
      setCvdata(prevState => ({
        ...prevState,
        AU: updatedAU  // Aktualisiere den AU-Array im cvdata-State
      }));
    };

    // Funktion zum Hinzufügen eines neuen Datensatzes zu AU
    const addNewKN = (newKN) => {
      const updatedKN = [...cvdata.KN, newKN];  // Füge den neuen Datensatz zum bestehenden Array hinzu
      setCvdata(prevState => ({
        ...prevState,
        KN: updatedKN  // Aktualisiere den AU-Array im cvdata-State
      }));
    };

    // Funktion zum Hinzufügen eines neuen Datensatzes zu PD
    const addNewKPD = (newKN) => {
      const updatedPD = [...cvdata.PD, newPD];  // Füge den neuen Datensatz zum bestehenden Array hinzu
      setCvdata(prevState => ({
        ...prevState,
        PD: updatedPD  // Aktualisiere den AU-Array im cvdata-State
      }));
    };

    const addNewKD = (newKN) => {
      const updatedKN = [...cvdata.KN, newKN];  // Füge den neuen Datensatz zum bestehenden Array hinzu
      setCvdata(prevState => ({
        ...prevState,
        KN: updatedKN  // Aktualisiere den KD-Array im cvdata-State
      }));
    };

    const handleKNDelete = (id) => {
        const updatedKN = cvdata.KN.filter(item => item.id !== id);  // Filtere das Element mit der gegebenen id heraus
        setCvdata(prevState => ({
            ...prevState,
            KN: updatedKN  // Setze den neuen AU-Array-Status
        }));
    };

    const handleBLDelete = (id) => {
        const updatedBL = cvdata.KN.filter(item => item.id !== id);  // Filtere das Element mit der gegebenen id heraus
        setCvdata(prevState => ({
            ...prevState,
            BL: updatedBL  // Setze den neuen BL-Array-Status
        }));
    };

  
    function newPD(props) {
         setId(id => id + 1);
         const pd_name = document.getElementById('pd_name').value;
         const pd_value = document.getElementById('pd_value').value;
         if (pd_name=="" || pd_value==""){
             alert("alles ausfüllen!");
             return false;
         }
         let newpd = {
          "id": [id],
          "type": "PD",
          "name": pd_name,
          "value": pd_value,
          "header": "pd_name",
          "date_from": "1111-11-11",
          "date_to": "9999-12-31",
          "created_at": null,
          "updated_at": null
        };
        

        setPdLength(pd_length => pd_length + 1);

        const res = {
          ...cvdata,
          PD: {
            ...cvdata.PD,
            [pd_length] : newpd
          } 
        }

      setCvdata(res);  
      console.log(cvdata);
    }

    function uploadJson(){
      var formData = new FormData();
      var jsonfile = document.querySelector('#jsonfile');
      formData.append("jsonfile", jsonfile.files[0]);
        axios.post('/cv/uploadJson', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
        .then(res => {
          setCvdata(res.data);
          setAuLength(au_length => res.data.AU.length); 
          setPdLength(pd_length => res.data.PD.length); 
          setKnLength(kn_length => res.data.KN.length); 
          setEdit(edit => res.data.edit); 
          console.log(kn_length);
      })
      .catch(err => console.log(err))
    }

    /* function SaveButton(){
      return <button className="btn"  onClick={() => saveJson(cvdata)} >
            {edit==true ? "Save" : "Edit"}
            </button>
    } */

    const saveJson = (cvdata) => {
        
      if (edit==false){
        setEdit(edit => true);
      }
      else{
        setEdit(edit => false);
      }
      //console.log(cvdata);
      axios.post('/cv/saveJson',cvdata)
        .then(response => console.log(response)
        )
        .catch(err => console.log(err))
      , []
    } 

    function downloadJson(){
      location.href = '/cv/downloadJson';
    } 
    

    
    const img_alt ="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";

    // Set the initial count state to zero, 0
    //const img_alt = image;
    const initdata = {"AU": [],"BL": [],"KN": [],"LANG": [],"PD": [],"edit": false, "img": img_alt}
    const [cvdata, setCvdata] = useState(initdata);
    const MyImage = ({ data }) => <img className="img-me" src={`data:image/jpeg;base64,${cvdata.img}`} />    
    // load data from local storage
    useEffect(() => {
     axios.get('/cv/json/1')
    .then(res => {
        setCvdata(res.data);
        if (!res.data.img) {
          setCvdata(prevState => ({
              ...prevState,
              img: img_alt  // Setze den neuen BL-Array-Status
          }));
        }
        //setEdit(edit => res.data.edit); 
        setEdit(edit => true); 
    })
    .catch(err => console.log(err))
    }, [])
  
  return (
        <div>
        <div className="container mx-auto flex-auto"> 
          <div className="flex justify-center mt-0 mb-5">
            {/* <img src="../img/noppal3.jpg" className="img-me" /> */}
            <MyImage/>
            <div className="cv-scc">
              <div>
                <a  href="/ssc/1" target="_blank">[ <i class="icon-eye"></i>&nbsp; view source code]</a>
              </div>
              <div>
                Für alle, die mich einstellen wollen <i class="icon-smily"></i>
              </div>
            </div>  
          </div>


          { edit ? ( 
            <div>

            <form id="uploadForm" encType="multipart/form-data" v-on:change="uploadFile">
                <input type="file" id="jsonfile" name="jsonfile" />
            </form>
            <button onClick={() => uploadJson()} className="btn">Upload File</button>
            </div>)
            : ""
          }

          <div className="cv grid grid-cols-2 gap-0 ">
              <h1 className="col-span-2 text-center">Lebenslauf</h1>
              <hr className="col-span-2 mb-10" />
              <div className="grid grid-cols-6">
                  <h2 className="col-span-6  border-red-500">Persönliche Daten</h2>
                  {edit && 
                    <div className="col-span-6">
                    <input type="text" className="pd_name" id="pd_name" /> 
                    <input type="text" className="pd_value" id="pd_value" /> 
                    <button onClick={() => newPD(pd_length)} className="btn">Add PD</button>
                    </div>
                  }
                  
                  {cvdata.PD.map((item, index) => (
                    <PDItem key={item.id} index={index} edit={edit} item={item} onDelete={handlePDDelete}/>
                  ))}

                  <div className="col-span-6">
                      <hr className="mb-5" />
                  </div>    

                  <div className="col-span-6">
                      <h2>Ausbildung</h2>
                      {edit && 
                        <button onClick={handleAddNewAU} className="btn">new AU</button>
                      }
                      <div className="au-wrapper">
                        {cvdata.AU.map((item, index) => (
                            <AUItem key={item.id} index={index} edit={edit} item={item} onDelete={handleAUDelete}/>
                        ))}
                      </div>
                  </div>
                  
                  <div className="col-span-6">
                      <hr className="mb-5" />
                  </div> 

                  <div className="col-span-6">
                      <h2>Kenntnisse & Fähigkeiten</h2>
                      {edit && 
                        <div> 
                        <input type="text" className="kn_name" id="kn_name" /> 
                        <button onClick={handleAddNewKN} className="btn">new KN</button>
                        </div>
                      }
                  </div>  

                  <div className="col-span-6">
                      <div className="my-10">

                      <div>
                      <label htmlFor="website-admin" className="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                      <div className="flex">
                        <span className="inline-flex align-right items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 border-e-0 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                          <svg className="w-4 h-4 text-gray-500 dark:text-gray-400 float-right" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                          </svg>
                        </span>
                        <input type="text" id="website-admin" className="rounded-none rounded-e-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="elonmusk" />
                      </div>
                      </div> 


                      {cvdata.KN.map((item, index) => (
                        <KNItem key={item.id} index={index} edit={edit} item={item} onDelete={handleKNDelete}/>
                      ))}


                      </div>
                  </div>


              </div>


              {/* Berufliche Laufbahn  */}
              <div className="grid grid-cols-1 row-span-12 ml-20">
                  <h2 className="col-span-2">Berufliche Laufbahn</h2>
                  {cvdata.BL.map((item, index) => (
                        <BLItem key={item.id} index={index} edit={edit} item={item} onDelete={handleBLDelete}/>
                    ))
                  }
              </div>    


          </div>
          </div>
          <button className="btn" onClick={() => showJson(cvdata)}>Log Data</button>
           <SaveButton data={cvdata} edit={edit} onClick={setEdit} />
          <button className="btn" target="_blank" onClick={() => downloadJson(cvdata)}>Download</button>


           
          
        </div>

      
  );
}

if (document.getElementById('cv')) {
    const root = createRoot(document.getElementById('cv'));
    root.render(<Cv />);
}