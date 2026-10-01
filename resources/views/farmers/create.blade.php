@extends('layouts.app')

@section('content')

<div class="m-portlet m-portlet--head-solid-bg m-portlet--rounded">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    Añadir Agricultor
                </h3>
            </div>          
        </div>
    </div>
    <div class="m-portlet__body">
        {!! Form::open(array('route' => 'farmers.store','method'=>'POST','id' => 'FormCreateFarmer')) !!}
            @include('farmers.form')
            <button type="submit" class="btn btn-primary">Añadir Agricultor</button>
        {!! Form::close() !!}
    </div>
</div>

<script>
    



    function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -41.21843, lng: -73.0178829},
            zoom: 10,
            mapTypeId: 'satellite'
        });

        var markers = [];

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();
            if (places.length == 0) {
                return;
            }
            

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }

                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng(); 
                $("input[name=latitude]").val(latitude)
                $("input[name=longitude]").val(longitude)

                var marker = new google.maps.Marker({
                    map: map,
                    title: place.name,
                    position: place.geometry.location,
                    draggable: true
                });

                google.maps.event.addListener(marker, 'dragend', function (event) {
                    $("input[name=latitude]").val(this.getPosition().lat())
                    $("input[name=longitude]").val(this.getPosition().lng())
                }); 


                // Create a marker for each place.
                markers.push(marker);

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);

            
        });


    }


</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd3yh0ivl1dLQraSDf5r-_9N484zc7bTQ&libraries=places&callback=initAutocomplete">


@endsection
