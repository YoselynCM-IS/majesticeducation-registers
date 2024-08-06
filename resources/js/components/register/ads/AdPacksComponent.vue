<template>
    <div>
      <b-carousel
        id="carousel-1"
        v-model="slide"
        :interval="7000"
        controls
        indicators
        background="#ababab"
        style="text-shadow: 1px 1px 2px #333;"
        @sliding-start="onSlideStart"
        @sliding-end="onSlideEnd">
  
        <!-- Slides with custom text -->
        <b-carousel-slide img-src="https://dl.dropboxusercontent.com/scl/fi/sssq56xtx2m8qbc6v0os5/adventure-pack-1.png?rlkey=z5tprwv4fr5i52hcwhu0nbci3&st=2qsq9ylq&dl=0">
            <h1>My adventure pack</h1>
            <h5>
                Explora nuestra colección ahora en <a href="https://myadventurepack.com/" target="_blank">My adventure pack</a> y encuentra la mochila que hará que cada salida sea una experiencia de estilo y comodidad.
            </h5>
          

<!-- Síguenos en nuestras redes sociales [enlace a redes sociales] para estar al tanto de nuestras últimas novedades, ofertas y mucho más. -->
        </b-carousel-slide>
        <b-carousel-slide img-src="https://dl.dropboxusercontent.com/scl/fi/kh8g9xxeniv19j95ohyef/adventure-pack-2.png?rlkey=qxn0a2vgyolyogrxilr7iauso&st=wo4ajwer&dl=0">
          <h5>
            Síguenos en nuestras redes sociales para estar al tanto de nuestras últimas novedades, ofertas y mucho más.
          </h5>
          <b-row>
            <b-col></b-col>
            <b-col sm="1">
                <b-button variant="dark" size="sm" href="https://www.facebook.com/people/My-Adventure-Pack/61562940206225/" target="_blank">
                    <i class="fa fa-facebook-square"></i>
                </b-button>
            </b-col>
            <b-col sm="1">
                <b-button variant="dark" size="sm" href="https://www.instagram.com/my_adventurepack/" target="_blank">
                    <i class="fa fa-instagram"></i>
                </b-button>
            </b-col>
            <b-col sm="1">
                <b-button variant="dark" size="sm" href="https://www.tiktok.com/@myadventurepack" target="_blank">
                    <i class="fa fa-heart"></i>
                </b-button>
            </b-col>
            <b-col></b-col>
          </b-row>
        </b-carousel-slide>
      </b-carousel>
    </div>
  </template>
  
  <script>
    export default {
      data() {
        return {
          slide: 0,
          sliding: null
        }
      },
      methods: {
        onSlideStart(slide) {
          this.sliding = true
        },
        onSlideEnd(slide) {
          this.sliding = false
        }
      }
    }
  </script>