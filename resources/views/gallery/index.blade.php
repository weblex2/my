@php
    foreach ($galleries as $country){
        echo '<script src="https://cdn.amcharts.com/lib/5/geodata/'.$country['country_map_name'].'High.js"></script>';
    }

@endphp
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/lang/DE.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/lang/ES.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<x-gallery-layout>
    @php
      $cg_invisible = "invisible"; 
      if (($message = Session::get('error'))){
        $cg_invisible = "";
      }
      else{
        $cg_invisible = "invisible";
      }  
    @endphp      
    <div id="create-gallery-modal" class="{{$cg_invisible}} absolute z-10 bg-zinc-900 bg-opacity-80 top-0 left-0 w-screen h-screen flex items-center justify-center">
      <div class="bg-zinc-800 rounded px-10 py-10 relative w-[500px]">
        <div class="absolute top-0 right-0 cursor-pointer m-3 shadow-xl" onclick="$('#create-gallery-modal').css('visibility', 'hidden')"><i class="fa-solid fa-xmark text-orange-500"></i></div>
        <h1>Create gallery</h1>
        
        <form id="frmCreateBlog" action="{{route('gallery.store')}}" method="post">
          @csrf
          <div>
              <label for="name">Blog Name</label>
              <div><input type="text" id="gallery_name" name="name" value="" class="w-full"></div>
              <label for="country_map_name">AM Charts country map name</label>
              <div><input type="text" id="country_map_name" name="country_map_name" value="" class="w-full"></div>
              <input type="hidden" id="country_code" name="code" value="">
          </div>
          <button type="submit" class="my-4 rounded-xl px-5 py-3 bg-zinc-700 border border-zinc-900">Create</button>

          @if (($message = Session::get('error')))
            <div class="p-5 border-red-900 bg-red-300 text-black rounded-xl mb-5">
            <strong>{{ $message }}</strong>
            </div>
          @endif

        </form>
      </div>  
    </div>

    <!-- create Map Point Modal -->
    <div id="create-mappoint-modal" class="{{$cg_invisible}} modal">
      <div class="bg-zinc-800 rounded px-10 py-10 relative w-[500px]">
        <div class="absolute top-0 right-0 cursor-pointer m-3 shadow-xl" onclick="$('#create-mappoint-modal').css('visibility', 'hidden')"><i class="fa-solid fa-xmark text-orange-500"></i></div>
        <h1>Create Map Point</h1>
        
        <form id="frmCreateMapPoint" action="{{route('gallery.storeMappoint')}}" method="post">
          @csrf
          <div>
              <label for="name">Map Point</label>
              <div><input type="text" id="mappoint_name" name="mappoint_name" value="" class="w-full"></div>
              <label for="lat">Latitude</label>
              <div><input type="text" name="lat" class="w-full"></div>
              <label for="lon">Longitude</label>
              <div><input type="text" name="lon" class="w-full"></div>
              <label for="mp_country_code">Country</label>
              <div><input type="text" id="mp_country_code" name="country_id" value="" class="w-full"></div>
          </div>
          <button type="submit" class="mt-3 btn-submit">Create</button>

          @if (($message = Session::get('error')))
            <div class="p-5 border-red-900 bg-red-300 text-black rounded-xl mb-5">
            <strong>{{ $message }}</strong>
            </div>
          @endif

        </form>
      </div>  
    </div>

    <x-slot name="header">
        <h2 class="font-semibold leading-tight text-orange-500">
            {{ __('Travel Blog') }}
        </h2>
    </x-slot>

    <div id="chartdiv" class="hidden md:block w-full h-[850px] pt-2 "></div> 
    <div class="block sm:hidden w-full">
      @foreach ($galleries as $gallery )
        <a href="{{route('gallery.showGallery', ['id' => $gallery->code] ) }}"><div>{{ $gallery->name }}</div></a>
      @endforeach 
    </div>
    

    <script>
        <?php 
            echo "var lang ='" .session('lang')."';"; 
            echo "var geo_lang = 'am5geodata_lang_".session('lang')."';";
        ?>
        am5.ready(function() {
        
        // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element
        var root = am5.Root.new("chartdiv");
        
        var myTheme = am5.Theme.new(root);
        
        myTheme.rule("am5map").setAll({
          fontSize: "1.0em"
        });
        
        /* am5.utils.addEventListener(root.dom, "contextmenu", function(ev) {
          ev.preventDefault();
        }); */
        
        
        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
          am5themes_Animated.new(root),
        ]);
        
        // Create the map chart
        // https://www.amcharts.com/docs/v5/charts/map-chart/
        var chart = root.container.children.push(am5map.MapChart.new(root, {
          panX: "translateX",
          panY: "translateY",
          //projection: am5map.geoMercator(),
          projection: am5map.geoNaturalEarth1(),
        }));
        
        
        // Create main polygon series for countries
        // https://www.amcharts.com/docs/v5/charts/map-chart/map-polygon-series/
        var polygonSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {
          geoJSON: am5geodata_worldLow,
          exclude: ["AQ"],
          //geodataNames: am5geodata_lang_ES, //am5geodata_lang_DE,
          <?php echo "geodataNames: am5geodata_lang_".session('lang').","; ?>
          //fill: am5.color(0x999999)
          templateField: "polygonSettings"
        }));
        
        var countries = polygonSeries.mapPolygons;
        var countryTemplate = polygonSeries.mapPolygons.template;
        
        countryTemplate.events.on("click", function(event){
            var countryCode = event.target.dataItem.dataContext.id;
            console.log(countryCode);
            console.log(event);
        });  
        
        
        
        
        polygonSeries.mapPolygons.template.setAll({
          tooltipText: "{name}",
          toggleKey: "active",
          templateField: "polygonSettings",
          fill: am5.color(0x3f3f46),
          interactive: true,
          text: "hallo"
        });
        
        polygonSeries.mapPolygons.template.states.create("hover", {
          fill: am5.color(0x6f6f86) // ?
        });

        polygonSeries.mapPolygons.template.events.on("rightclick", function(ev) {
          
          console.log(ev);
          $("#create-gallery-modal").css("visibility", "visible");
          $('#country_code').val(ev.target.dataItem.dataContext.id);
          $('#gallery_name').val(ev.target.dataItem.dataContext.name);
          console.log("Right click event:" + ev.target.dataItem.dataContext);
        });
        
        polygonSeries.mapPolygons.template.states.create("active", {
          fill: am5.color(0xed8936)
        });
        
        var previousPolygon;
        
        polygonSeries.mapPolygons.template.on("active", function (active, target) {
          if (previousPolygon && previousPolygon != target) {
            previousPolygon.set("active", false);
          }
          if (target.get("active")) {
            polygonSeries.zoomToDataItem(target.dataItem );
          }
          else {
            chart.goHome();
          }
          previousPolygon = target;
        });
        
        
        
        <?php
        
         foreach ($galleries as $country){
         if ($country['color']==""){
            $country['color'] = "cccccc";
         }
         echo '
          var states'.$country['code'].' = chart.series.push(am5map.MapPolygonSeries.new(root, {
            geoJSON: am5geodata_'.$country['country_map_name'].'High
          }));
        
          states'.$country['code'].'.mapPolygons.template.setAll({
            tooltipText: "{name} - '.$country['name'].'",
            toggleKey: "active",
            interactive: true,
            geodataNames: am5geodata_lang_DE,
            fill: am5.color(0x'.$country['color'].'),
          });

          states'.$country['code'].'.mapPolygons.template.events.on("rightclick", function(ev) {
              
              var id= ev.target.dataItem.dataContext.id.substr(0,2);
              $("#mp_country_code").val(id);
              $("#create-mappoint-modal").css("visibility","visible");
          });

          states'.$country['code'].'.mapPolygons.template.events.on("click", function(ev) {
              console.log("Right click event 2:" + ev.target.dataItem.dataContext.id);
          });

          

          states'.$country["code"].'.mapPolygons.template.on("active", function (active, target) {
            if (previousPolygon && previousPolygon != target) {
              previousPolygon.set("active", false);
            };

         
            if (target.get("active")) {
              polygonSeries.zoomToDataItem(target.dataItem );
            }
            else {
              chart.goHome();
            }
            previousPolygon = target;
            });';
         }
        ?>
        
        
        
        polygonSeries.mapPolygons.template.on("click", function(event){
            chart.zoomToMapObject(event.target);
            var countryCode = event.target.dataItem.dataContext.id;
            console.log(countryCode);
        });
        
        // Add zoom control
        // https://www.amcharts.com/docs/v5/charts/map-chart/map-pan-zoom/#Zoom_control
        chart.set("zoomControl", am5map.ZoomControl.new(root, {}));
        
        
        // Set clicking on "water" to zoom out
        chart.chartContainer.get("background").events.on("click", function () {
          chart.goHome();
        })
        
        
        // Disable built-in context menu
        root.addDisposer(
          am5.utils.addEventListener(root.dom, "contextmenu", function(ev) {
            ev.preventDefault();
          })
        );
        
        
        
        
        // Make stuff animate on load
        chart.appear(1000, 100);
        
        
        var pointSeries = chart.series.push(am5map.ClusteredPointSeries.new(root, {}));
        
        // Create regular bullets
        pointSeries.bullets.push(function() {
            var container = am5.Container.new(root, {
            cursorOverStyle:"pointer",
            tooltipText : "{title}",
            href: "{country}"
          });
        
          var circle1 = container.children.push(am5.Circle.new(root, {
            radius: 6,
            tooltipY: 0,
            fill: am5.color(0x00ff00)
          }));
        
          var circle2 = container.children.push(am5.Circle.new(root, {
            radius: 10,
            fillOpacity: 0.3,
            tooltipY: 0,
            fill: am5.color(0x00ff00)
          }));
        
          var circle3 = container.children.push(am5.Circle.new(root, {
            radius: 14,
            fillOpacity: 0.3,
            tooltipY: 0,
            fill: am5.color(0x00ff00)
          }));
        
          var label = container.children.push(am5.Label.new(root, {
            centerX: am5.p50,
            centerY: am5.p50,
            //fill: am5.color(0xff0000),
            populateText: true,
            fontSize: "8",
         
          }));
        
          circle2.events.on("inited", function(event){
            animateBullet(event.target);
          });
        
          container.events.on("click", function(e) {
            console.log(chart._settings.zoomLevel);
            let zoom_level = chart._settings.zoomLevel;
            var href="/travel-blog/show/"+ e.target.dataItem.dataContext.href + "/" +e.target.dataItem.dataContext.id;
            window.location.href = href;
            //alert(e.target.dataItem.dataContext.href+"/"+e.target.dataItem.dataContext.id);
            if (zoom_level>1){
              
            }
            else{  
              pointSeries.zoomToCluster(e.target.dataItem);
            }  
          });
        
          return am5.Bullet.new(root, {
            sprite: container
          });
        });
        
        
        // Set data
        /* var cities = [
          /* { title: 'Munich', latitude: 48.1371, longitude: 11.5761, country: "DE" }, 
          { title: "Vienna", latitude: 48.2092, longitude: 16.3728, country: "AT" },
          { title: "Bogota",latitude: 4.6473, longitude: -74.0962, country: "CO" },
          { title: "Medellin",latitude: 6.2308, longitude: -75.5905, country: "CO" },
          { title: "Hanoi", latitude: 21.0341, longitude: 105.8372, country: "VN" },
          { title: 'Hoi An', latitude: 15.87944, longitude: 108.335, country: "VN" },
          { title: 'Lima', latitude: -12.0463, longitude: -77.0427, country: "PE"},      
          { title: 'Panama City', latitude: 8.983333, longitude: -79.5166, country: "PE"}  
        ]; */
        
      
        @foreach ($mappoints as $city)
            addCity({{$city->lon}}, {{$city->lat}}, '{{$city->mappoint_name}}', '{{$city->country_id}}', '{{$city->id}}', {{$city->gallery_pics_count}});        
        @endforeach 
        
        function addCity(longitude, latitude, title, country, id, pic_count) {
          pointSeries.data.push({
            geometry: { type: "Point", coordinates: [longitude, latitude] },
            title: title +" - ( " + pic_count + " Pics )" ,
            tooltipText : title ,
            href: country,
            id: id
          });
        }
        
        function animateBullet(circle) {
            var animation = circle.animate([{ property: "scale", from: 1 / chart.zoomLevel, to: 5 / chart.zoomLevel }, { property: "opacity", from: 1, to: 0 }], 1000, am4core.ease.circleOut);
            animation.events.on("animationended", function(event){
              animateBullet(event.target.object);
            })
        }
        
        
        }); // end am5.ready()
        $('body').on("contextmenu", function(evt) {evt.preventDefault();});
  </script>
        
</x-gallery-layout>
