window.onload = function() {
  var webview = document.querySelector('#app-webview');
  webview.addEventListener('permissionrequest', function(e) {
    if (e.permission === 'download') {
      e.request.allow();
    }
  });
};