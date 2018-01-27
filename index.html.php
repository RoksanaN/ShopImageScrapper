<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scrapper</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
    <style>
        #scrollBlock {
            overflow-y: auto;
            /*max-height: 400px;*/
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="row hide0">
            <div class="col-md-6">
                <h4>New url</h4>
                <form id="newForm">

                    <div class="input-group">
                        <span class="input-group-addon" id="siteUrl1">http://trade-city.ua/catalog/</span>

                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span id="siteUrl2"></span>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" id="siteList">
                                <li><a href="#"></a></li>
                                <li><a href="#"></a></li>
                            </ul>
                        </div>

                        <input
                            class="form-control"
                            id="customeUrl"
                            name="customeUrl"
                            placeholder="MI5149"
                            value="<?php echo $customeUrl; ?>"
                        >

                        <input type="hidden" id="realUrl" name="url">

                        <span class="input-group-addon" id="siteUrl3">.html</span>
                        <span class="input-group-btn">
                            <button class="btn btn-default">Go!</button>
                        </span>
                    </div>

                </form>
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($success) { ?>
                            <br>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Success!</strong> Success scrap!
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div id="newBlock"></div>
            </div>
            <div class="col-md-6">
                <h4 id="resTitle">Result</h4>
                <div id="scrollBlock">
                    <?php
                        $_GET['res'] = true;
                        include 'result/index.php';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"></h4>
          </div>
          <div class="modal-body">
              <img alt="" class="img-responsive">
          </div>
        </div>
      </div>
    </div>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/tmpl" id="tmplDownload">
        <a href="{{link}}" download="{{name}}">&#x1f4be;</a>
    </script>
    <script>
        $(() => {
            'use strict';
            let site = {
                el: {
                    siteList: $('#siteList'),
                    siteListA: $('#siteList a'),
                    siteUrl3: $('#siteUrl3'),
                    siteUrl2: $('#siteUrl2'),
                    siteUrl1: $('#siteUrl1'),
                    customeUrl: $('#customeUrl'),
                    form: $('#newForm'),
                    newBlock: $('#newBlock'),
                    realUrl: $('#realUrl'),
                    scrollBlock: $('#scrollBlock'),

                    a: $('.blockUL a'),
                },
                data: {
                    urls: ['belts/', 'bag/', 'glasses/'],
                },
                tmpl: {
                    a: $('#tmplDownload').html(),
                },
                set: {
                    a: newUrl => {

                        site.el.siteListA.each(function(i) {
                            let $this = $(this),
                                url = newUrl[i];
                            site.set._a($this, url);
                        });

                        return site.set;
                    },
                    _a: ($this, url) => {
                        $this
                            .prop('href', `#${url}`)
                            .html(url);
                    },
                    form: () => {

                        site.el.form.submit(() => {

                            let siteUrl1 = site.el.siteUrl1.text(),
                                siteUrl2 = site.el.siteUrl2.text(),
                                siteUrl3 = site.el.siteUrl3.text(),
                                customeUrl = site.el.customeUrl.val();

                            let realUrl = `${siteUrl1}${siteUrl2}${customeUrl}${siteUrl3}`;

                            console.log('realUrl::', realUrl);

                            site.el.realUrl.val(realUrl);

                            // return false;
                        });

                        return site.set;
                    },
                    urlPath: () => {

                        if (location.search) {
                            let url = decodeURIComponent(location.search),
                                customeUrl = url.split('=')[1];
                                // customeUrl = url.substring(url.lastIndexOf('/')+1, url.lastIndexOf('.'));
                            site.el.customeUrl.val(customeUrl);
                            $('.blockName').each(function(i) {
                                let $this = $(this);
                                if (customeUrl === $.trim($this.text())) {
                                    site.el.newBlock.html(
                                        $this.add($('.blockUL').eq(i)).addClass('bg-success')
                                    );
                                }
                            });
                        }

                        return site.set;
                    },
                    clickA: () => {

                        site.el.a
                            .click(function(e) {
                                let $this = $(this),
                                    src = $this.prop('href'),
                                    name = $this.text();

                                $('.modal-title').html(name);
                                $('.modal-body img').prop('src', src);

                                $('#exampleModal').modal('show');

                                e.preventDefault();
                            })
                            .each(function() {
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

                        return site.set;
                    },
                },
                show: () => {
                    $('.row.hide').removeClass('hide');
                },
                init: () => {

                    if (!location.hash) {
                        location.hash = site.data.urls[0];
                    }

                    site.data.curUrl = location.hash && location.hash.substring(1);
                    let indexUrl = site.data.urls.indexOf(site.data.curUrl);

                    if (indexUrl === -1) {
                        location.hash = site.data.urls[0];
                    }

                    let newUrl = [].concat(site.data.urls);
                    newUrl.splice(indexUrl, 1);

                    site.el.siteUrl2.html(site.data.curUrl);

                    site.set
                        .urlPath()
                        .a(newUrl)
                        .form()
                        .clickA();

                    site.el.siteListA.click(function() {
                        
                        let $this = $(this),
                            newCurUrl = $this.text();
                        
                        site.el.siteUrl2.html(newCurUrl);

                        setTimeout(() => {
                            site.set._a($this, site.data.curUrl);
                            site.data.curUrl = newCurUrl;
                        }, 100);

                    });

                    site.el.scrollBlock.css({'max-height': $(window).height() - 200});

                    return site;
                },
            };

            window.site = site.init();

            if (localStorage._debug) {
                site.show();
            }

        });
    </script>
</body>
</html>