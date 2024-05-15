import $ from "./src/jquery.js";
import App from "./src/App.js";

(function () {
    $.noConflict();

    $(document).ready(() => App.init());
})();