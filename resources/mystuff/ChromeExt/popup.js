// popup.js

document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('extractButton').addEventListener('click', function() {
    chrome.tabs.query({active: true, currentWindow: true}, function(tabs) {
      chrome.tabs.sendMessage(tabs[0].id, {action: 'extractText'}, function(response) {
        console.log(response);

        if (response && response.text) {
          var apiUrl = 'http://api.noppal.de';

          var postData = {
            text: response.text
          };

          fetch(apiUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(postData)
          })
          .then(response => response.json())
          .then(data => {
            console.log('API Response:', data);
            // Hier können Sie mit der API-Antwort weiterarbeiten
          })
          .catch(error => {
            console.error('Error:', error);
          });
        } else {
          console.warn('No text to extract.');
        }
      });
    });
  });
});
