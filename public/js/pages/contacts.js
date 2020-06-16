$(document).ready(function() {
    $('select[name^="contact_field_"]').on('change', function (e) {
       let $this = $(this);
       let $row = $this.parent().parent();

       if ($this.val() === "none") {
           $row.find('td > select[name^="custom_field_"]').attr('disabled', false);
       } else {
           $row.find('td > select[name^="custom_field_"]').attr('disabled', true).val('none');
           $row.find('td > input').attr('disabled', true).val('');
       }
    });

    $('select[name^="custom_field_"]').on('change', function (e) {
       let $this = $(this);
       let $row = $this.parent().parent();

       if ($this.val() === "new") {
           $row.find('td > input[name^="new_field_"]').attr('disabled', false);
           $row.find('td > select[name^="contact_field_"]').val('none');
       } else if ($this.val() !== "none") {
           $row.find('td > input[name^="new_field_"]').attr('disabled', true);
           $row.find('td > select[name^="contact_field_"]').val('none');
       }
    });

    $('.fieldColumn').hover(function () {
        let column = $(this).attr('data-column');
        $('td[data-column="' + column + '"]').addClass('contact_field_hover');
    }, function () {
        let column = $(this).attr('data-column');
        $('td[data-column="' + column + '"]').removeClass('contact_field_hover');
    });
});
