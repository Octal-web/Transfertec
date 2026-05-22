import React, { useEffect, useRef } from "react";

export const ContactMap = () => {
    const mapRef = useRef(null);
    const markerRef = useRef(null);
    const geocoderRef = useRef(null);

    useEffect(() => {
        const script = document.createElement("script");
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyD4896B_6vvadnzAjpCBFeB4tmsPIo3Abk&libraries=marker&callback=initMap&v=weekly`;
        script.async = true;
        script.defer = true;

        window.initMap = () => {
            if (mapRef.current) {
                const map = new window.google.maps.Map(mapRef.current, {
                    center: { lat: -29.2590, lng: -51.5305 },
                    zoom: 15,
                    streetViewControl: true,
                    mapId: 'bb9d80f35ea099bf',
                });

                geocoderRef.current = new window.google.maps.Geocoder();

                if (window.google.maps.marker && window.google.maps.marker.AdvancedMarkerElement) {
                    const placeMarker = (location, label) => {
                        if (markerRef.current) {
                            markerRef.current.map = null;
                        }

                        const markerContainer = document.createElement("div");
                        markerContainer.style.display = "flex";
                        markerContainer.style.flexDirection = "column";
                        markerContainer.style.alignItems = "center";
                        markerContainer.style.textAlign = "center";
                        markerContainer.style.width = "200px";
                        markerContainer.style.position = "relative";
                        markerContainer.style.top = "70px";

                        const pinImage = document.createElement("img");
                        pinImage.src = "/eno/site/img/location-pin.png";
                        pinImage.style.width = "50px";
                        pinImage.style.height = "auto";
                        pinImage.style.margin = "0 auto";
                        markerContainer.appendChild(pinImage);
                        
                        const labelElement = document.createElement("div");
                        labelElement.textContent = label;
                        labelElement.style.color = "#8A1102";
                        labelElement.style.fontSize = "24px";
                        labelElement.style.fontWeight = "400";
                        labelElement.style.fontFamily = '"Poppins", sans-serif';
                        labelElement.style.marginTop = "5px";
                        labelElement.style.width = "100%";
                        labelElement.style.textAlign = "center";
                        markerContainer.appendChild(labelElement);

                        markerRef.current = new window.google.maps.marker.AdvancedMarkerElement({
                            position: location,
                            map: map,
                            content: markerContainer,
                            title: label,
                        });

                        markerContainer.style.opacity = "0";
                        markerContainer.style.transition = "opacity 0.5s";
                        
                        setTimeout(() => {
                            markerContainer.style.opacity = "1";
                        }, 100);

                        map.panTo(location);
                    };

                    placeMarker(
                        { lat: -29.2590, lng: -51.5305 },
                        "Transfertec Enologia",
                    );
                } else {
                    const placeMarker = (location, label) => {
                        if (markerRef.current) {
                            markerRef.current.setMap(null);
                        }
                        markerRef.current = new window.google.maps.Marker({
                            position: location,
                            map: map,
                            draggable: false,
                            animation: window.google.maps.Animation.DROP,
                            visible: true,
                            icon: {
                                url: "/eno/site/img/location-pin.png",
                            },
                            label: {
                                text: label,
                                color: "#8A1102",
                                fontSize: "24px",
                                fontWeight: "400",
                                fontFamily: '"Poppins", sans-serif',
                            },
                        });
                        map.panTo(location);
                    };

                    placeMarker(
                        { lat: -29.2528, lng: -51.5441 },
                        "Transfertec Enologia",
                    );
                }
            }
        };

        document.head.appendChild(script);

        return () => {
            if (script && script.parentNode) {
                script.parentNode.removeChild(script);
            }
            delete window.initMap;
        };
    }, []);

    return <div ref={mapRef} className="max-md:h-80" />;
};