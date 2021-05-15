const stripe = document.querySelector('#headingStripe')
if (stripe) { stripe.addEventListener('click', paiement) }


function paiement() {
  var stripe = Stripe(
    "pk_test_51HrP9mFBU85ljtQmsGSfeR3Sb5udMysKiQnaPTes7cRRDQSEHybN6SfGiNS3FMs9gDSy5BxfQ4Qt7ll2LgVqH2bE00icgo07TW"
  );
  // Disable the button until we have Stripe set up on the page
  document.querySelector(".submitstripe").disabled = true;
  axios
    .post("../intention")
    .then(function (response) {
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
      card.mount("#card-element");

      card.on("change", function (event) {
        document.querySelector(".submitstripe").disabled = event.empty;
        document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
      });
      var form = document.getElementById("payment-stripe");
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        payWithCard(stripe, card, response.data);
      });

      // Calls stripe.confirmCardPayment
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
              var url = "/cart/success/" + idtrans;
              window.location = url
            }
          });
      }
    })
    .catch(function (error) {
      if (error.response.status === 400) {
        alert(error.response.data)
      }
    })
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
}