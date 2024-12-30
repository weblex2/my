// resources/js/Counter.js

import axios from "axios";
import React, { useState } from "react";
import { createRoot } from 'react-dom/client';

export default function Counter() {
  // Set the initial count state to zero, 0
  const [count, setCount] = useState(0);

  // Create handleIncrement event handler
  const handleIncrement = () => {
    setCount(prevCount => prevCount + 1);
    notifyServer();
  };

  // Create handleDecrement event handler
  const handleDecrement = () => {
    setCount(prevCount => prevCount - 1);
    notifyServer();
  };

  // Notifies the server about the change
  const notifyServer = () => {
      axios.post('/react/getMessage', {
          message: 'Counter Updated!',
      })
      .then(function (response) {
        console.log(response.data);
    })
  }

  return (
    <div>
        <button onClick={handleDecrement}>-</button>
        <span> {count} </span>
        <button onClick={handleIncrement}>+</button>
        <button onClick={() => alert('Du hast geklickt.')}>Hier klicken</button>
    </div>
    
  );
}

if (document.getElementById('counter')) {
    const root = createRoot(document.getElementById('counter'));
    root.render(<Counter />);
}