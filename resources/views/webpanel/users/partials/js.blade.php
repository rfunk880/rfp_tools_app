<script>
    (function($, window, undefined) {
        $(function() {
            $(document).on('change', '[name=user_type_id]', function(e){
                if($(this).val() == $(this).attr('data-toggle-value')){
                    $($(this).attr('data-toggle-div')).removeClass('d-none');
                } else {
                    $($(this).attr('data-toggle-div')).addClass('d-none');
                }
            });
        });
    })(jQuery, window);
</script>
