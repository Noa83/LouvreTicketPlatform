Stripe.setPublishableKey('pk_test_WjHJkRf8mEI5hYufVKkqlbWw');
var $form = $('#payment_form');
$form.submit(function(e){
    e.preventDefault();
    $form.find('button').attr('disabled', true);
    Stripe.card.createToken($form, function(status, response){
        if (response.error){
            $form.find('.message').remove();
            $form.prepend('<div>'+ response.error.message + '</div>')
            $form.find('button').attr('disabled', false);
        } else {
            var token = response.id;
            $form.append($('<input type="hidden" name="stripeToken">').val(token));
            $form.get(0).submit();
            return token;
        }
    })
})
