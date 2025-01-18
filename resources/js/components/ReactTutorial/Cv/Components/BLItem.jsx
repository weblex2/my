import React from 'react';
import parse from 'html-react-parser';

// Die Component, die den Index empfängt
const BLItem = ({  index, item, edit, onDelete }) => {

     return (
              <div key={item.id} mykey={item.id} className="col-span-6 mb-5">
                <span>
                    { edit ? ( 
                        <i className="fa-solid fa-xmark text-red-500 cursor-pointer" onClick={() => onDelete(item.id)}></i> 
                    ) : ""
                    }
                </span>
                <div className="col-span-2 bl"><h3>{item.header}</h3></div>
                <div className="">{parse(item.value)}</div>
              </div>
          );
};

export default BLItem;