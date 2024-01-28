document.addEventListener('DOMContentLoaded', function () {
  chrome.tabs.query({ active: true, currentWindow: true }, function(tabs) {
    chrome.tabs.sendMessage(tabs[0].id, { action: 'extractText' }, function(response) {
      console.log('Received Response:', response);
      if (response && response.selectedText) {
        document.getElementById('selectedText').innerText = response.selectedText;
      }
    });
  });
});