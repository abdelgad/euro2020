$(document).ready(function()
{
    var add_input_button = $('.add_input_button');
    var field_wrapper = $('.field_wrapper');
    var new_field_html = '<div class="input-group mb-3"><input type="text" class="form-control" name="inputFieldTown[]" value="" placeholder="Nom de la ville"/><div class="input-group-append"><button title="Remove field"  class="btn btn-danger remove_input_button" onclick="javascript:void(0);" type="button">−</button></div></div>';

    // Add button dynamically
    $(add_input_button).click(function()
    {
        $(field_wrapper).append(new_field_html);
    });

    // Remove dynamically added button
    $(field_wrapper).on('click', '.remove_input_button', function(e)
    {
        e.preventDefault();
        $(this).parent('div').parent().remove();
    });
});