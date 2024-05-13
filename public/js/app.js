import $ from "./src/jquery";
import App from "./src/App";

(function () {
    $.noConflict();

    $(document).ready(() => App.init());
})();