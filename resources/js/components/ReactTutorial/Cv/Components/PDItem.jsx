import React from 'react';
import parse from 'html-react-parser';

// Die Component, die den Index empfängt
const PDItem = ({  index, item, edit, onDelete }) => {

     return (
              <div key={"PD_"+index} mykey={"PD_"+index} className="col-span-6">
                            <div className="header"> 
                              { edit ? ( 
                                  <span>
                                  <i className="fa-solid fa-xmark text-red-500 cursor-pointer" onClick={() => onDelete(item.id)}></i> 
                                  <i className="fa-solid fa-edit text-blue-500 cursor-pointer ml-2" onClick={() => alert(item.id)}></i>&nbsp; 
                                </span>) : ""
                              }
                              {item.name}
                            </div>
                            <div className="bl_text mb-5 float-left w-[60%]">{parse(item.value)}</div>
                        </div>
          );
};

export default PDItem;



