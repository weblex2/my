import React from "react";
import axios from "axios";

const SaveButton = ({ data, edit, setEdit }) => {
  const handleSave = async () => {
    // Daten um den "edit"-Parameter ergänzen
    setEdit(!edit);
    
    try {
      const response = await axios.post("/cv/saveJson", updatedData, {
        headers: {
          "Content-Type": "application/json",
        },
      });

      console.log("Erfolgreich gesendet:", response.data);
      //alert("Daten erfolgreich gesendet!");
    } catch (error) {
      console.error("Fehler beim Senden der Daten:", error);
      //alert("Es gab einen Fehler beim Senden der Daten.");
    }
  };

  return (
    <button onClick={handleSave}>
      Speichern
    </button>
  );
};

export default SaveButton;