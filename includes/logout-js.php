<script type="text/javascript">
        $('#logout').click(function() {
                $.ajax({
                        type: "DELETE",
                        url: "api/auth",
                        processData: false,
                        contentType: "application/json",
                        data: '{}',
                        success: function(r) {
                                console.log(r)
                        },
                        error: function(r) {
                                console.log(r)
                        }
                });
        });
</script>