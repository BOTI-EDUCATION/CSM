<template>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
      <div class="card overflow-hidden">
        <div class="card-body">
          <div id="map" style="width: 100%; height: 550px"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      map: null,
      markers: [],
      coordinates: [],
    };
  },
  methods: {
    initMap: function () {
      this.map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(30.963165, -8.143788),
        zoom: 6,
      });

      const iconBase =
        "https://developers.google.com/maps/documentation/javascript/examples/full/images/";

      // this.coordinates = [
      //   {
      //     position: new google.maps.LatLng(
      //       -33.91721,
      //       151.2263
      //     ),
      //     type: "info",
      //   },
      // ];
    },
    initMarkers: async function () {
      const token = localStorage.getItem("auth-token");
      if (token) {
        await axios
          .get("/api/getSchoolsCoordinates", {
            headers: {
              Authorization: "Bearer " + token,
            },
          })
          .then(async (result) => {
            this.coordinates = result.data;
            let infowindow = [];
            for (let i = 0; i < this.coordinates.length; i++) {
              // let markers = new google.maps.Marker({
              //   position: new google.maps.LatLng(this.coordinates[i].position[0],this.coordinates[i].position[1]),
              //   icon: this.icons[this.coordinates[i].type].icon,
              //   map: this.map,
              // });
              // let position = new google.maps.LatLng(parseFloat(this.coordinates[i].position[0]),parseFloat(this.coordinates[i].position[1]));
              let position = new google.maps.LatLng(
                parseFloat(this.coordinates[i].position[0]),
                parseFloat(this.coordinates[i].position[1])
              );

              infowindow[i] = new google.maps.InfoWindow({
                content: this.coordinates[i].info,
              });

              let marker = new google.maps.Marker({
                position: position,
                title: this.coordinates[i].title,
                // icon: this.coordinates[i].icon,
                map: this.map,
              });

              this.markers.push(marker);

              marker.addListener("click", () => {
                this.map.setZoom(15);
                this.map.setCenter(marker.getPosition());
                infowindow[i].open(this.map, marker);
              });
            }

            const clusterOptions = {
              imagePath: "https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m"
            };

            let markerCluster = new MarkerClusterer(
              this.map,
              this.markers,
              clusterOptions
            );

            const styles = markerCluster.getStyles();
              for (let i=0; i<styles.length; i++) {
                styles[i].textColor = "white";
              }
          })
          .catch(function (err) {});
      }
    },
  },
  mounted() {
    this.initMap();
    this.initMarkers();
  },
};
</script>