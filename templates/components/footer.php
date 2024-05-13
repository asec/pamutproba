<?php
/**
 * @var HtmlView $this
 */

use PamutProba\App\View\HtmlView;
use PamutProba\Utility\Url;

?>
    <script type="text/javascript" src="<?php echo Url::base("/js/bootstrap.bundle.js") ?>"></script>
    <script type="text/javascript" src="<?php echo Url::base("/js/jquery-3.7.1.min.js") ?>"></script>
    <script type="text/javascript">
        window.app = {
            apiEntryPoint: "<?php echo Url::base() ?>"
        };
    </script>
    <script type="module" src="<?php echo Url::base("/js/App.js") ?>"></script>
</body>
</html>