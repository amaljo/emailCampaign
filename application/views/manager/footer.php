</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="<?= $this->config->base_url(); ?>assets/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?= $this->config->base_url(); ?>assets/js/bootstrap.min.js"></script>

<script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>

<script type="text/javascript">
    tinymce.init({
        selector: "textarea.tinyMCE",
        plugins: [
            "advlist autolink lists link charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime nonbreaking save table contextmenu directionality",
            /*"emoticons template paste textcolor colorpicker textpattern moxiemanager",*/
            "emoticons paste textcolor colorpicker textpattern"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
        toolbar2: "print preview | forecolor backcolor",
        convert_urls: false,
        inline_styles: true,
        verify_html: false
    });
</script>

</body>

</html>
