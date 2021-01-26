// Set your publishable key: remember to change this to your live publishable key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
const stripe = Stripe(
  "pk_test_51HrP9mFBU85ljtQmsGSfeR3Sb5udMysKiQnaPTes7cRRDQSEHybN6SfGiNS3FMs9gDSy5BxfQ4Qt7ll2LgVqH2bE00icgo07TW"
);
const elements = stripe.elements();

// Custom styling can be passed to options when creating an Element.
const style = {
  base: {
    // Add your base input styles here. For example:
    fontSize: '16px',
    color: '#32325d',
  },
};

// Create an instance of the card Element.
// const card = elements.create('card');
const cardNumberElement = elements.create('cardNumber', {style},showIcon="true", iconStyle="solid");
const cardExpiryElement = elements.create('cardExpiry', {style});
const cardCvcElement = elements.create('cardCvc', {style});

// Add an instance of the card Element into the `card-element` <div>.
// card.mount("#card-element");
cardNumberElement.mount("#card-number");
cardExpiryElement.mount("#card-expiry");
cardCvcElement.mount("#card-cvc");

// Create a token or display an error when the form is submitted.
const form = document.getElementById('payment-form');
form.addEventListener('submit', async (event) => {
  event.preventDefault();

  const {token, error} = await stripe.createToken(cardNumberElement);

  if (error) {
    // Inform the customer that there was an error.
    const errorElement = document.getElementById('card-errors');
    errorElement.textContent = error.message;
  } else {
    // Send the token to your server.
    stripeTokenHandler(token);
  }
});

const stripeTokenHandler = (token) => {
  // Insert the token ID into the form so it gets submitted to the server
  const form = document.getElementById("payment-form");
  const hiddenInput = document.createElement("input");
  hiddenInput.setAttribute("type", "hidden");
  hiddenInput.setAttribute("name", "stripeToken");
  hiddenInput.setAttribute("value", token.id);
  form.appendChild(hiddenInput);

  // Submit the form
  form.submit();
};

