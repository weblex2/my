import React from 'react';
import { createRoot } from 'react-dom/client';
import { StrictMode } from "react";

import axios from 'axios';
import { useState } from 'react';
class CustomerTable extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            customers: []
        }
    }

    
    componentDidMount() {
        let data = "";
        axios.get('/blog/reactTest')
        .then(response => response.data)
        .then(
            (customers) => {
                console.log(customers);this.
                setState({ customers: customers });            },
            (error) => {
                alert(error);
            }
        )
    }         
    
    

    render() {
        return (
            <>
            <div className="react">
            <table cellPadding="0" cellSpacing="0" className="tblTest">
            <thead>
                <tr>
                    <th>CustomerId</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
 
            <tbody>
                {this.state.customers.map(customer =>
                    <tr key={customer.id}>
                        <td>{customer.id}</td>
                        <td>{customer.name}</td>
                        <td>{customer.email}</td>
                    </tr>
                )}
            </tbody>
        </table>
        </div>
        
        </>
        );
    }
}

if (document.getElementById("reactTable")) {
const rootElement = document.getElementById("reactTable");
const root = createRoot(rootElement);
root.render(
    <StrictMode>
      <CustomerTable />
    </StrictMode>
  );
}  