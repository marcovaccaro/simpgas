window.onload=function initialize_pfce() {
// Create the autocomplete object and associate it with the UI input control.
  
  autocomplete = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */
      (document.getElementById('autocomplete')),{
        types: ['(cities)'],
      });
  }