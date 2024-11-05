$(document).ready(function() {
  // Define the price for each order type
  var prices = {
    '500ml Water Bottle': 10.00,
    'New Slim Gallon': 150.00,
    'New Round Gallon': 150.00,
    'Slim Gallon Refill': 25.00,
    'Round Gallon Refill': 25.00
  };

  // Function to calculate and display the price
  function calculatePrice() {
    var orderType = $('.orderType').val();
    var quantity = parseInt($('input[name="quantity"]').val());

    // Ensure quantity is a non-negative value
    quantity = Math.max(quantity, 0);

    var price = prices[orderType] * quantity;

    if (isNaN(price)) {
      $('#price').text('');
    } else {
      $('#price').text('Total Price: ₱' + price.toFixed(2));
    }
  }

  // Call the calculatePrice function when the order type or quantity changes
  $('.orderType, input[name="quantity"]').change(function() {
    calculatePrice();
  });

  // Prevent entering negative values for quantity
  $('input[name="quantity"]').on('input', function() {
    var value = $(this).val();
    if (value < 0) {
      $(this).val(0);
    }
  });

  // Initial price calculation
  calculatePrice();
});