import React from 'react';
import parse from 'html-react-parser';

// Die Component, die den Index empfängt
const KNItem = ({  index, item, edit, onDelete }) => {

     return (
            <span>
                 
                <span className="tag w-fit" key={"PD_"+index} mykey={"PD_"+index}>
                    <span>
                    { edit ? ( 
                        <i className="fa-solid fa-xmark text-red-500 cursor-pointer" onClick={() => onDelete(item.id)}></i>  
                    ) : ""
                    }
                    </span>     
                    {parse(item.value)}
                </span>
            </span>
          );
};

export default KNItem;