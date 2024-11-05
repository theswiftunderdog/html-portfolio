//para mag rotate yung arrow

var previousDropdown = null;

function toggleDropdown(btn) {
  if (previousDropdown !== null && previousDropdown !== btn) {
    var previousIcon = previousDropdown.querySelector('i');
    previousIcon.classList.remove('rotate');
  }

  var icon = btn.querySelector('i');
  icon.classList.toggle('rotate');
  previousDropdown = btn;
}

