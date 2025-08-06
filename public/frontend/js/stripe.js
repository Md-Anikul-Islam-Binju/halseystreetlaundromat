document.addEventListener("DOMContentLoaded", function () {
    const stripe = Stripe(document.querySelector('meta[name="stripe-key"]').content);
    const elements = stripe.elements();
    const card = elements.create("card");
    card.mount("#card-element");

    card.on("change", function (event) {
        const displayError = document.getElementById("card-errors");
        displayError.textContent = event.error ? event.error.message : "";
    });

    const form = document.getElementById("dry-order-form");
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        stripe.createToken(card).then(function (result) {
            if (result.error) {
                document.getElementById("card-errors").textContent = result.error.message;
            } else {
                document.getElementById("stripe_token").value = result.token.id;
                form.submit();
            }
        });
    });
});
