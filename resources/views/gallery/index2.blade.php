<!-- Styles --> 
<style> 
#chartdiv { 
  width: 100%; 
  height: 100%; 
} 
</style> 

<!-- Resources --> 
<script src="https://cdn.amcharts.com/lib/5/index.js"></script> 
<script src="https://cdn.amcharts.com/lib/5/map.js"></script> 
<script src="https://cdn.amcharts.com/lib/5/geodata/continentsHigh.js"></script> 
<script src="https://cdn.amcharts.com/lib/5/geodata/worldHigh.js"></script> 
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script> 
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script> 
<script src="https://cdn.tailwindcss.com"></script> 

<!-- Chart code --> 
<script> 
am5.ready(function() { 

    var country_color = am5.color(0x71717A); 
    //var hover_color   = am5.color(255, 24, 27); 
    var hover_color   = am5.color(0xed8936); 

// Create root element 
// https://www.amcharts.com/docs/v5/getting-started/#Root_element 
var root = am5.Root.new("chartdiv"); 

// Set themes 
// https://www.amcharts.com/docs/v5/concepts/themes/ 
root.setThemes([ 
  am5themes_Animated.new(root) 
]); 

// Create the map chart 
// https://www.amcharts.com/docs/v5/charts/map-chart/ 
var chart = root.container.children.push(am5map.MapChart.new(root, { 
  panX: "rotateX", 
  projection: am5map.geoNaturalEarth1() 
})); 

// Create polygon series for continents 
// https://www.amcharts.com/docs/v5/charts/map-chart/map-polygon-series/ 
var continentSeries = chart.series.push(am5map.MapPolygonSeries.new(root, { 
  geoJSON: am5geodata_continentsHigh, 
  exclude: ["antarctica"] 
})); 

continentSeries.mapPolygons.template.setAll({ 
  tooltipText: "{name}", 
  interactive: true, 
  fill: am5.color(0x27272A), 
  cursorOverStyle: "pointer", 
}); 

continentSeries.mapPolygons.template.states.create("hover", { 
  //fill: root.interfaceColors.get("primaryButtonActive") 
  fill: hover_color 
}); 

// Set up zooming in on clicked continent 
continentSeries.mapPolygons.template.events.on("click", function (ev) { 
  continentSeries.zoomToDataItem(ev.target.dataItem); 
  continentSeries.hide(); 
  countrySeries.show(); 
  homeButton.show(); 
}); 

// Create polygon series for countries 
// https://www.amcharts.com/docs/v5/charts/map-chart/map-polygon-series/ 
var countrySeries = chart.series.push(am5map.MapPolygonSeries.new(root, { 
  geoJSON: am5geodata_worldHigh, 
  exclude: ["AQ"], 
  visible: false 
})); 

countrySeries.mapPolygons.template.setAll({ 
  tooltipText: "{name}", 
  interactive: true, 
  fill: country_color, 
  cursorOverStyle: "pointer", 
}); 

countrySeries.mapPolygons.template.states.create("hover", { 
  fill: hover_color 
}); 



// Set up zooming in on clicked continent 
continentSeries.mapPolygons.template.events.on("click", function (ev) { 
  continentSeries.zoomToDataItem(ev.target.dataItem); 
  continentSeries.hide(); 
  //countrySeries.show(); 
  homeButton.show(); 
}); 

countrySeries.mapPolygons.template.events.on("click", function (ev) { 

    var isStates = false; 
    //remove states if shown 
    if (chart.series._values[6]){ 
      chart.series.removeIndex( 
        chart.series.indexOf(states) 
      ); 
    } 

  console.log(isStates); 

  
  console.log(ev.target.dataItem.dataContext.id); 
  var name = ev.target.dataItem.dataContext.name.toLowerCase(); 
  switch(name) { 
    case "united states": 
      map = "usa"; 
      break; 
    case "guinea-bissau": 
      map = "guineaBissau"; 
      break; 
    case "sierra leone": 
      map = "sierraLeone"; 
      break;   
    case "côte d'ivoire": 
      map = "cotedIvoire"; 
      break;     
    case "el salvador": 
      map = "elSalvador"; 
      break;   
    case "costa rica": 
      map = "costaRica"; 
      break;   
    case "french guiana": 
      map = "frenchGuiana"; 
      break; 
    case "falkland islands": 
      map = "falklandIslands"; 
      break;   
    default: 
      map = name; 
      break; 
  } 
  var url = "https://cdn.amcharts.com/lib/5/geodata/"+map+"High.js"; 
  $.getScript(url, function( data, textStatus, jqxhr ) { 
  // this is your callback. 
        statedata = window['am5geodata_' + map + 'High']; 
        //console.log(statedata); 
        //console.log(am5geodata_canadaLow); 
        //console.log(am5geodata_canadaLow==statedata); 
        if ( chart.series.length > 2) { 
            //chart.series.removeIndex(3).dispose(); 
        }     
        states = chart.series.push(am5map.MapPolygonSeries.new(root, { 
            geoJSON: statedata, 
            //geoJSON: am5geodata_canadaLow 
        }));   

        states.mapPolygons.template.setAll({ 
            tooltipText: "{name}", 
            toggleKey: "active", 
            interactive: true, 
            //id : "states", 
            //geodataNames: am5geodata_lang_DE, 
            //fill: root.interfaceColors.get("primaryButtonActive"), 
            fill: country_color, 
            cursorOverStyle: "pointer", 
        }); 

        states.mapPolygons.template.events.on("click", function (ev) { 
            console.log(ev.target.dataItem); 
            alert( ev.target.dataItem.dataContext.id + " parent" +  ev.target.dataItem.dataContext.parentCountry); 
        }); 

        states.mapPolygons.template.states.create("hover", { 
            //fill: am5.color(0xed8936) 
            fill : hover_color 
        }); 

        ev.target.hide(); 
   }); 

   continentSeries.zoomToDataItem(ev.target.dataItem); 
   continentSeries.hide(); 
   countrySeries.show(); 
   homeButton.show(); 

});   

// Cities 
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
    if (zoom_level>1){ 
      var href="/gallery/"+ e.target.dataItem.dataContext.href; 
      alert(href); 
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
var cities = [ 
  { title: 'Munich', latitude: 48.1371, longitude: 11.5761, country: "DE" }, 
  { title: "Vienna", latitude: 48.2092, longitude: 16.3728, country: "AT" }, 
  { title: "Bogota",latitude: 4.6473, longitude: -74.0962, country: "CO" }, 
  { title: "Medellin",latitude: 6.2308, longitude: -75.5905, country: "CO" }, 
  { title: "Hanoi", latitude: 21.0341, longitude: 105.8372, country: "VN" }, 
  { title: 'Hoi An', latitude: 15.87944, longitude: 108.335, country: "VN" }, 
  { title: 'Lima', latitude: -12.0463, longitude: -77.0427, country: "PE"},       
  { title: 'Panama City', latitude: 8.983333, longitude: -79.5166, country: "PE"}   
]; 

/* for (var i = 0; i < cities.length; i++) { 
  var city = cities[i]; 
  addCity(city.longitude, city.latitude, city.title, city.country); 
} */ 

var paris = addCity(  { latitude: 48.8567, longitude: 2.351 },  "Paris"); 
var la    = addCity(  { latitude: 34.3, longitude: -118.15 },   "Los Angeles"); 
var med   = addCity(  { latitude: 6.2308, longitude: -75.5905}, "Medallin" );
console.log(paris); 
console.log(la); 
//var munich = addCity(  48.1371,  11.5761 , "Los Angeles"); 
//var hanoi = addCity(  21.0341,  105.837 , "Hanoi"); 


var pointSeries = chart.series.push( 
  am5map.MapPointSeries.new(root, { 
    geoJSON: cities 
  }) 
); 

function addCity(coords, title) { 
  return pointSeries.pushDataItem({ 
    latitude: coords.latitude, 
    longitude: coords.longitude, 
    title: title 
  }); 
} 

/* function addCity(coords,  title, country="nix") { 
    longitude = coords.longitude; 
    latitude = coords.latitude; 
  return pointSeries.data.push({ 
    geometry: { type: "Point", coordinates: [longitude, latitude] }, 
    title: title, 
    tooltipText : title, 
    href: country 
  }); 
  //return coords; 
} 
*/ 

// Add lines 

// https://jsfiddle.net/codeblazor/9nqpugf0/27/ 

// Create line series for trajectory lines 
// https://www.amcharts.com/docs/v5/charts/map-chart/map-line-series/ 

var lineSeries = chart.series.push(am5map.MapLineSeries.new(root, {})); 
lineSeries.mapLines.template.setAll({ 
  //stroke: root.interfaceColors.get("alternativeBackground"), 
  strokeOpacity: 0.9, 
  stroke: am5.color(0xffffff) 
}); 

// Create line series 

var lineDataItem = lineSeries.pushDataItem({ 
  pointsToConnect: [paris, la, med] 
}); 




var planeSeries = chart.series.push(am5map.MapPointSeries.new(root, {})); 

var plane = am5.Graphics.new(root, { 
  svgPath: 
    "m2,106h28l24,30h72l-44,-133h35l80,132h98c21,0 21,34 0,34l-98,0 -80,134h-35l43,-133h-71l-24,30h-28l15,-47", 
  scale: 0.06, 
  centerY: am5.p50, 
  centerX: am5.p50, 
  fill: am5.color(0xffffff) 
}); 

planeSeries.bullets.push(function() { 
  var container = am5.Container.new(root, {}); 
  container.children.push(plane); 
  return am5.Bullet.new(root, { sprite: container }); 
}); 

var planeDataItem = planeSeries.pushDataItem({ 
  lineDataItem: lineDataItem, 
  positionOnLine: 0, 
  autoRotate: true 
}); 

planeDataItem.animate({ 
  key: "positionOnLine", 
  to: 1, 
  duration: 10000, 
  loops: Infinity, 
  easing: am5.ease.yoyo(am5.ease.linear) 
}); 

planeDataItem.on("positionOnLine", function(value) { 
  if (value >= 0.99) { 
    plane.set("rotation", 180); 
  } else if (value <= 0.01) { 
    plane.set("rotation", 0); 
  } 
}); 

  
// End Lines 


// Add a button to go back to continents view 
var homeButton = chart.children.push(am5.Button.new(root, { 
  paddingTop: 10, 
  paddingBottom: 10, 
  x: am5.percent(100), 
  centerX: am5.percent(100), 
  opacity: 0, 
  interactiveChildren: false, 
  icon: am5.Graphics.new(root, { 
    svgPath: "M16,8 L14,8 L14,16 L10,16 L10,10 L6,10 L6,16 L2,16 L2,8 L0,8 L8,0 L16,8 Z M16,8", 
    fill: am5.color(0xffffff) 
  }) 
})); 

homeButton.events.on("click", function() { 
  chart.goHome(); 
  continentSeries.show(); 
  countrySeries.hide(); 
  states.hide(); 
  homeButton.hide(); 
}); 

}); // end am5.ready() 
</script> 

<!-- HTML --> 
<div id="chartdiv" class="bg-zinc-700"></div> 
