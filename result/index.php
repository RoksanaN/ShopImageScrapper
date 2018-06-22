<?php if (!isset($_GET['res'])) { ?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    <h1>LIST</h1>
<?php } ?>
    <?php
        $base = isset($_GET['res'])? './result': '.';
        $handle = opendir($base);
        if ($handle) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && $entry !== 'index.php') {
                    if (is_dir("$base/$entry")) {
                        if ($handleInnerDirectory = opendir("$base/$entry")) {
                            echo <<<HTML
                                <h2 class="blockName">$entry</h2>
                                <a href="?zip=$entry">$entry</a>
                                <ul class="blockUL">
HTML;

                            while (false !== ($entry1 = readdir($handleInnerDirectory))) {
                                if ($entry1 != "." && $entry1 != "..") {
                                    if (!is_dir($entry1)) {
                                        echo "<li><a href='$base/$entry/$entry1'>$entry1</a></li>\n";
                                    }
                                }
                            }

                            closedir($handleInnerDirectory);

                            echo "</ul>";
                        }
                    }
                }
            }

            closedir($handle);
        }
    ?>
    <a href="?delete_all_zip">DELETE ALL</a>
<?php if (!isset($_GET['res'])) { ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/tmpl" id="tmplDownload">
        <a href="{{link}}" download="{{name}}">&#x1f4be;</a>
    </script>
    <script>
        $(function() {
            'use strict';
            let site = {
                el: {
                    a: $('.blockUL a'),
                },
                tmpl: {
                    a: $('#tmplDownload').html(),
                },
                set: {
                    click: () => {

                        site.el.a.each(function() {
                            let $this = $(this),
                                link = $this.prop('href'),
                                name = $this.text(),
                                $download = $(
                                    site.tmpl.a
                                        .replace('{{link}}', link)
                                        .replace('{{name}}', name)
                                );

                            $this.after($download).after(' &bullet; ');

                        });
                        // site.el.a.click(function(e) {

                        //     e.preventDefault();

                        //     let $this = $(this);

                        //     site.download($this.prop('href'), $this.text());
                        // });

                        return site.set;
                    },
                },
                // download: (link, name) => {
                //     let a = document.createElement('a');
                //     a.href = link;
                //     a.download = name;
                //     a.click();
                //     return site;
                // },
                init: () => {

                    site.set
                        .click();

                    return site;
                },
            };
            window.site = site.init();
        });
    </script>
</body>
</html>


<?php } ?>