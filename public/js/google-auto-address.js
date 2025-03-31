 function initialize() {
    var input = document.getElementById('searchTextField');
    var autocomplete = new google.maps.places.Autocomplete(input);    
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();
        getcountry(place);
        document.getElementById('city2').value = place.name;
        document.getElementById('CstLat').value = place.geometry.location.lat();
        document.getElementById('CstLong').value = place.geometry.location.lng();
    });
}
google.maps.event.addDomListener(window, 'load', initialize);
function getcountry(data) {
            let state = "";
            let country = "";
            let city = "";
            let zipCode = "";
            data = data.address_components;

            console.log(data);

            const dataCount = data.length;

            for (var i = 0; i < dataCount; i++) {

                if ($.inArray("country", data[i].types) != -1) {
                    country = data[i].long_name;
                }

                if ($.inArray("administrative_area_level_1", data[i].types) != -1) {
                    state = data[i].long_name;
                }

                if ($.inArray("administrative_area_level_2", data[i].types) != -1) {
                    city = data[i].long_name;
                }

                if ($.inArray("postal_code", data[i].types) != -1) {
                    zipCode = data[i].long_name;
                }

                document.getElementById('Country').value = country
                document.getElementById('State').value = state
                document.getElementById('PinCode').value = zipCode
                document.getElementById('City').value = city
            }
        }