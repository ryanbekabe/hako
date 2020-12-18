<?php
// javascript:var%20title=window.getSelection();location.href='http://127.0.0.1:8000/index.php?url='+encodeURIComponent(location.href)+'&title='+'&key=secret'
$KEY = "secret";
error_reporting(E_ERROR);
if (!file_exists('archive')) {
    mkdir('archive', 0777, true);
}
if (!empty($_GET['title']) and $_GET['key'] == $KEY) {
    $filename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $_GET['title']);
    $filename = mb_ereg_replace("([\.]{2,})", '', $filename);
    $filename = str_replace(" ", "_", $filename);
    shell_exec('monolith ' . $_GET['url'] . ' --isolate --output archive/' . $filename . '.html');
    $f = fopen("archive/" . $filename . ".txt", "a");
    fwrite($f, $_GET['title'] . "\n");
    fwrite($f, $_GET['url'] . "\n");
    fclose($f);
}
?>

<html lang="en">

<!-- Author: Dmitri Popov, dmpop@linux.com
         License: GPLv3 https://www.gnu.org/licenses/gpl-3.0.txt -->

<head>
    <meta charset="utf-8">
    <title>箱</title>
    <meta charset="utf-8">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🗃️</text></svg>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.5.7/dist/css/uikit.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.5.7/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.5.7/dist/js/uikit-icons.min.js"></script>
</head>

<body>
    <div class="uk-container uk-margin-small-top">
        <div class="uk-card uk-card-default uk-card-body">
            <h1 class="uk-heading-line uk-text-center"><span>箱 Hako</span></h1>
            <?php
            $fileList = glob('archive/*.html');
            foreach ($fileList as $filename) {
                //$url = file_get_contents('archive/' . basename($filename, ".html") . '.txt', true);
                $array = explode("\n", file_get_contents('archive/' . basename($filename, ".html") . '.txt', true));
                $title = $array[0];
                $url = $array[1];
                if (!empty($url)) {
                    echo "<a href='$filename'>" . $title . "</a> <strong><a href='$url'><img src='external-link.svg' /></a></strong><a href='read.php?url=$url'><img src='file-text.svg' /></a></strong><br>";
                } else {
                    echo "<a href='$filename'>" . basename(str_replace("_", " ", $filename), ".html") . "</a><br>";
                }
            }
            ?>
            <hr style="margin-bottom: 1em;">
            &copy; <?php echo date("Y"); ?>. This is <a href="https://github.com/dmpop/hako">Hako</a>
        </div>
    </div>
</body>

</html>