var stripe = Stripe(
  "pk_test_51HrP9mFBU85ljtQmsGSfeR3Sb5udMysKiQnaPTes7cRRDQSEHybN6SfGiNS3FMs9gDSy5BxfQ4Qt7ll2LgVqH2bE00icgo07TW"
);
// var cle= document.querySelector('#submit')
// var clientsecret= cle.dataset.secret

// Disable the button until we have Stripe set up on the page
document.querySelector("button").disabled = true;

var elements = stripe.elements();
var style = {
  base: {
    color: "#32325d",
    fontFamily: 'Arial, sans-serif',
    fontSmoothing: "antialiased",
    fontSize: "16px",
    "::placeholder": {
      color: "#32325d"
    }
  },
  invalid: {
    fontFamily: 'Arial, sans-serif',
    color: "#fa755a",
    iconColor: "#fa755a"
  }
};

var card = elements.create("card", { style: style });
// Stripe injects an iframe into the DOM
card.mount("#card-element");

card.on("change", function (event) {
  // Disable the Pay button if there are no card details in the Element
  document.querySelector("button").disabled = event.empty;
  document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
});
var form = document.getElementById("payment-form");
form.addEventListener("submit", function (event) {
  event.preventDefault();
  var clientsecret= form.dataset.secret;
  payWithCard(stripe, card, clientsecret);
});

// Calls stripe.confirmCardPayment
// If the card requires authentication Stripe shows a pop-up modal to
// prompt the user to enter authentication details without leaving your page.
var payWithCard = function (stripe, card, clientSecret) {
  loading(true);
  stripe
    .confirmCardPayment(clientSecret, {
      payment_method: {
        card: card
      }
    })
    .then(function (result) {
      if (result.error) {
        // Show error to your customer
        showError(result.error.message);
      } else {
        // The payment succeeded!
        var idtrans = result.paymentIntent.id
        var url = document.getElementById("payment-form").dataset.url+"/"+idtrans;
        window.location = url
      }
    });
};

// Shows a success message when the payment is complete

// Show the customer the error from Stripe if their card fails to charge
var showError = function (errorMsgText) {
  loading(false);
  var errorMsg = document.querySelector("#card-error");
  errorMsg.textContent = errorMsgText;
  setTimeout(function () {
    errorMsg.textContent = "";
  }, 4000);
};

// Show a spinner on payment submission
var loading = function (isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner
    document.querySelector("button").disabled = true;
    document.querySelector("#spinner").classList.remove("hidden");
    document.querySelector("#button-text").classList.add("hidden");
  } else {
    document.querySelector("button").disabled = false;
    document.querySelector("#spinner").classList.add("hidden");
    document.querySelector("#button-text").classList.remove("hidden");
  }
};
