        </div>
    </main>

    <footer>
        <div class="container">
            <p>Web Programming Assignment 2 - Anna Thomas (s4927945)</p>
        </div>
    </footer>
<script>
function success(result) {
    result = JSON.parse(result);
    if(result) {
        if(result.hasOwnProperty('error')) {
            $("#error").text(result.error);

        } else if(result.hasOwnProperty('redirect')) {
            // Redirect to result.redirect
            location.href = result.redirect;

        } else {
            failure(result);
        }

    } else {
        failure(result);
    }
}

function failure(result) {
    try {
        result = JSON.parse(result.responseText);
        if(result) {
            if (result.hasOwnProperty('errorObject')) {
                $("#error").text(result.errorObject.message);
                return;
            } else if (result.hasOwnProperty('error')) {
                $("#error").text(result.error);
                return;
            }
        }
    } catch(e) {}

    $("#error").text('An unspecified error occurred' + result);

}

$('.ajax').on('submit', function(e){
    e.preventDefault();

    var form = $(this);
    var formdata = false;

    if (window.FormData) {
        formdata = new FormData(form[0]);
    }

    data2 = formdata ? formdata : form.serialize();

    $.ajax({
        method: "POST",
        url         : form.attr('action'),
        data        : formdata ? formdata : form.serialize(),
        cache       : false,
        contentType : false,
        processData : false
    }).done(success).fail(failure);
});
</script>

</body>
</html>