var mMap = null;
var mLat = 42.6207129;
var mLng = 13.0277123;
var mMarker = new L.Marker([mLat, mLng]);
var mMarkersList = [];

function setMarker(lat, lng) {

    for(var i = 0; i < mMarkersList.length; i++){
        mMap.removeLayer(mMarkersList[i]);
    }

    mMarker = new L.marker([lat ?? mLat, lng ?? mLng]);
    mMarkersList.push(mMarker);

    mMarker.addTo(mMap);
    mMap.setView([lat ?? mLat, lng ?? mLng], 12);
}

$(document).ready(function($) {
    if ($('#map').length > 0) {
        mMap = L.map('map').setView([mLat, mLng], 5);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(mMap);
    }
});
