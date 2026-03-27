(function () {
  function loadUi() {
    var s = document.createElement('script');
    s.src = '/assets/js/ui.js';
    s.defer = true;
    (document.body || document.documentElement).appendChild(s);
  }
  if (typeof jQuery === 'undefined') {
    var jq = document.createElement('script');
    jq.src = '/assets/js/jquery-3.4.1.min.js';
    jq.onload = loadUi;
    (document.head || document.documentElement).appendChild(jq);
  } else {
    loadUi();
  }
})();
