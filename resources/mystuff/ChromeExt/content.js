chrome.runtime.onMessage.addListener(function (request, sender, sendResponse) {
  if (request.action === 'extractText') {
    const selectedText = window.getSelection().toString().trim();
    chrome.runtime.sendMessage({ action: 'displayText', selectedText: selectedText });
  }
});