const ZONA_BOUNDARIES = [
    {
        "id": 1,
        "nama_zona": "Bandung Utara",
        "kode_zona": "BDG-U01",
        "geo_boundary": {
            "type": "Polygon",
            "coordinates": [
                [
                    [107.58, -6.86], [107.65, -6.86], [107.65, -6.9], [107.58, -6.9], [107.58, -6.86]
                ]
            ]
        }
    }
];

const ZONA_COLORS = [
    '#2563EB', '#16A34A', '#D97706', '#DC2626', '#7C3AED',
];

const DEFAULT_LAT = -6.9175;
const DEFAULT_LNG = 107.6191;
const DEFAULT_ZOOM = 12;

// mock L
const L = {
    map: function() { return this; },
    setView: function() { return this; },
    tileLayer: function() { return this; },
    addTo: function() { return this; },
    polygon: function() { return { addTo: function() { return { bindTooltip: function() {}, on: function() {} } } }; },
    marker: function() { return { addTo: function() { return { on: function() {} } } }; },
    divIcon: function() {}
};

const map = L.map('map').setView([DEFAULT_LAT, DEFAULT_LNG], DEFAULT_ZOOM);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap',
    maxZoom: 19,
}).addTo(map);

const zonaLayers = {};

ZONA_BOUNDARIES.forEach(function(zona, index) {
    if (!zona.geo_boundary || !zona.geo_boundary.coordinates) return;

    const color = ZONA_COLORS[index % ZONA_COLORS.length];
    const coords = zona.geo_boundary.coordinates[0].map(c => [c[1], c[0]]);

    const polygon = L.polygon(coords, {
        color: color,
        weight: 2,
        fillColor: color,
        fillOpacity: 0.08,
        dashArray: '5, 5',
    }).addTo(map);

    polygon.bindTooltip(zona.nama_zona, {
        permanent: true,
        direction: 'center',
        className: 'zona-label',
    });

    polygon.on('click', function(e) {
        selectZona(zona.id, zona.nama_zona, true);
        placeMarker(e.latlng.lat, e.latlng.lng);
    });

    zonaLayers[zona.id] = { polygon, color, nama: zona.nama_zona };
});

console.log("SUCCESS!");
