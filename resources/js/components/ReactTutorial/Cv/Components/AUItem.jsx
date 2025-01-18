import React from 'react';
import parse from 'html-react-parser';

// Die Component, die den Index empfängt
const AUItem = ({  index, item, edit, onDelete }) => {

     return (
              <div>
                <div key={"AU_"+index} mykey={"AU_"+index} className="w-full">
                    <div> Index {index} </div>    
                    <div className="w-full">{item.date_from} - {item.date_to=="0000-00-00" ? "jetzt" : item.date_to}</div>
                    <div className="w-full header"> 
                        {item.header}
                    </div>
                    <div className="w-full bl_text mb-5 float-left w-[60%]"> {parse(item.value)}</div>
                </div>
            </div>  
          );
};

export default AUItem;