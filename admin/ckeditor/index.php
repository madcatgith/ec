<?php

$no_html = 1;
include getenv('DOCUMENT_ROOT') .'/admin/admin_top.php';

$name = filter_input(INPUT_GET, 'name');

?>
<!DOCTYPE html>
<html>
    <head>
        <title>CKEditor 4</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="ckeditor.js"></script>
        <script type="text/javascript" src="/admin/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" title="text style" href="/admin/css/bootstrap.min.css" />
        <script>
            $(document).ready(function() {
                element = opener.document.getElementById('<?=$name?>');        
                
                CKEDITOR.replace('cketextarea');
                CKEDITOR.instances.cketextarea.setData(element.value);
                
                save = function() {
                    element.value = CKEDITOR.instances.cketextarea.getData();  
                    window.close();
                };
            });
        </script>
    </head>
    <body>
        <textarea id="cketextarea" name="cketextarea"></textarea>
        <div class="navbar-left margin-20">
            <button class="btn btn-success" type="button" onclick="save()">Передать</button>
        </div>
        <div class="navbar-right margin-20">
            <button data-toggle="modal" data-target="#specialModal" class="btn btn-success" type="button">Специальные вставки</button>
            <button class="btn btn-success" type="button" onclick="javascript:void(window.open('/admin/files.php?show=jqGrid','FileManager','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=720,height=520,left=30,top=30'));">Файл-менеджер</button>
        </div>
        <div class="modal fade" id="specialModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog iframeDialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Специальные вставки</h4>
                    </div>
                    <div class="modal-body">
                        <iframe width="940" height="400" frameborder="0" src="/admin/ckeditor/specials/"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>