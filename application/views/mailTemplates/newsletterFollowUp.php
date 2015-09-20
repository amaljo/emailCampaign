<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="content-type">
        <style>body{background:#e5e5e5;font-family: monospace;}table{background: #fff;width: 650px;margin: 40px auto; border: 1px solid #125A92;border-left: 4px solid #125A92; box-shadow: 0 0 16px #769C90;}td {    padding: 0 20px 5px 20px;} tr.footer td {    padding: 15px 20px;    border-top: 1px dashed #ccc;    margin:  0 0 0;}h3{color: #555555;margin: 10px 0 0 0;}p{color: #797979;margin: 2px 0 10px 0;}</style>
    </head>
    <body>
        <table>
            <tr><td><?= $message->message ?></td></tr>
            <tr><td><a href="<?= $this->config->base_url(); ?>actions/unsubscibe/1/<?= $subscriber->id ?>">Unsubscribe</a></td></tr>
        </table>
    </body>
</html>