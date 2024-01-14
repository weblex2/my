// Inhaltskript für die Webseite

document.addEventListener('mouseup', function(event) {
  var selectedText = window.getSelection().toString().trim();
  chrome.runtime.sendMessage({text: selectedText});
});