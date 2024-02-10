// content.js
chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {
  if (request.action === 'getSelectedText') {
    var selectedText = window.getSelection().toString();
    console.log("selected Text"+ selectedText);
    sendResponse({ selectedText: selectedText });
  }
});