<!DOCTYPE html>
<html>
<head>
<title>
Weather Forecast
</title>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> 
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="http://openlayers.org/api/OpenLayers.js"></script>
    <style type="text/css"> 
        .bck
        {
         background:url('images/bg.jpg'); 
         background-size:cover; 
         font-family:"Trebuchet MS", Helvetica, sans-serif !important;    
        }
        .col-centered
        {
           text-align:center;
        }
        .tiles
        {
         margin: 0px 7px 10px 7px; 
            
        }
        .transparent
        {
            background-color:rgba(0,0,0,0.12);            
        }
        .imagelogo
        {
        width: 100px;
        height:50px;    
            
        }
        .formColor
        {
         color:White;   
        }
        .gutter-10
        {
         margin-right: -20px;   
        }
        .nowDataTable tr:nth-child(odd)
        {
           background-color:#F9F9F9;
            text-align:left;
            height: 32px;
        }
       .nowDataTable tr:nth-child(even)
        {
           background-color:pink; 
            text-align:left;
            height: 32px;
        }
        
        .nxt24Table tr td
        {
            background-color:White;
            
        }
        .nav-tabs li a
        {
        background-color:White;
        color:#3071A9;
        }
        .nav-tabs > li.active > a,
        .nav-tabs > li.active > a:hover,
        .nav-tabs > li.active > a:focus
        {
            color: White;
            background-color: #3071A9;  
        }
        .col-no-gutter 
        {
        margin-left: 0;
        margin-right: 0;
        padding-right: 0;
        padding-left: 0;    
        }
        .input
        {
          margin:10px 5px;
        }
        .submitBtn
        {
         background-color:#3074AF;
         color:White; 
        border-radius:3px;    
        }
        .clearBtn
        {
        color:Black;
        background-color:    
        }
        .select option
        {
         width:100%;   
        }
    </style> 
    
<script>
var fb_icon="http://52.34.69.150/images/";
var fb_summary="";
var fb_temperature="";
  window.fbAsyncInit = function() 
  {
    FB.init({
      appId      : '1730601783834964',
      xfbml      : true,
      version    : 'v2.2',
     status      : true,
     cookie      : true
    });
  };

(function(d) 
 {
 var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
 if(d.getElementById(id)) { return; }
 js = d.createElement('script');
 js.id = id;
 js.src = "//connect.facebook.net/en_US/all.js";
 js.async = true;
 ref.parentNode.insertBefore(js, ref);
}(document));

 function sharefb()
{ 
  var city=$.trim($("#City").val());
  var state=$("#State option:selected").val(); 
  var name="Current Weather in "+city+","+state;
  var picture = fb_icon;
  var caption= "Weather Forecast from Forecast.io";
  var link = "http://forecast.io";
  var description = fb_summary+", "+fb_temperature;
    //alert(description);
 var obj =
 {
  method: 'feed',
  link: link,
  picture: picture,
  name: name,
  caption: caption,
  description: description,
 };

 function callback(response)
 {
  if(response)
  {
   alert("Post was Successfull");
  }
  else
  {
   alert("Could not Post!!");
  }
 }

 FB.ui(obj, callback);
 return false;
 
}
    
</script>
    
<script type="text/javascript">
var latitude="";
var longitude="";
  function generateMap(latitude,longitude){
  $("#Weathermap").html('');
  try{
  var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
        var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
        var position       = new OpenLayers.LonLat(longitude,latitude).transform( fromProjection, toProjection);
        
    var map = new OpenLayers.Map("Weathermap");
    // Create OSM overlays
    var mapnik = new OpenLayers.Layer.OSM();

     var layer_precipitation = new OpenLayers.Layer.XYZ(
        "precipitation",
        "http://${s}.tile.openweathermap.org/map/precipitation/${z}/${x}/${y}.png",
        {
            isBaseLayer: false,
            opacity: 0.5,
            sphericalMercator: true
        }
    );
 
  var layer_cloud = new OpenLayers.Layer.XYZ(
        "clouds",
        "http://${s}.tile.openweathermap.org/map/clouds/${z}/${x}/${y}.png",
        {
            isBaseLayer: false,
            opacity: 0.5,
            sphericalMercator: true
        }
    );
 
 
map.addLayers([mapnik,layer_precipitation,layer_cloud]);
map.setCenter(position,10);
  }
  catch(err){
   $("#Weathermap").html("<div style='margin:10px;'><h3> Map not available for this location</h3></div>");
  }
  }
</script>
    

<script> 
    
 $(document).ready(function()
{
$('#resultTab').hide();     
     
 $('#Street').blur(function()
{
  var street= $('#Street').val();
    $('#streetVal').hide();
    if($.trim(street)==='')
    {
      $('#streetVal').show();  
    }
    
}); 

$('#City').blur(function()
{
  var street= $('#City').val();
    $('#cityVal').hide();
    if($.trim(street)==='')
    {
      $('#cityVal').show();  
    }
    
}); 
     
$('#State').blur(function()
{
  var street= $('#State').val();
    $('#stateVal').hide();
    if($.trim(street)==='')
    {
      $('#stateVal').show();  
    }
    
});      


function CurrentData(current,city,state,deg)
{
         
     var nowData="";
     nowData+="<div class='row'><div class='col-md-6' >";
     nowData+="<div class='row' style='background-color:#FA5881;'><div class='col-md-6' >";
     nowData+="<div class='col-xs-10' style='text-align: center;'>";
     nowData+= " <img src='images/"+current['icon']+".png' alt='"+current['summary']+"' title='";
     nowData+=obj['currData']['summary']+"' style='width:100px;height:100px;margin-top:10px;' /></div></div>  ";
     nowData+=" <div class='col-md-6'><div class='row'><div class='col-xs-10' style='text-align:center;'>";
     nowData+="<span style='color:White;'>"+current['summary']+" in "+city +", " +state+"</span></div></div> ";
     nowData+="<div class='row' style='text-align:center;'><div class='col-xs-10'>"; 
     nowData+="<p style='color:White;'><span style='font-size:50px;font-weight:bold;'>"+current['temperature']+"</span><sup style='font-size:26px;'>&deg"+deg+"</sup></p> </div> </div>";
     nowData+="<div class='row'><div class='col-xs-10' style='text-align:center;'>";     
     nowData+="<span style='color:Purple;'> L:"+current['minTemp']+"&deg</span> | <span style='color:Green;'>H: "+current['maxTemp']+"&deg </span></div>";
    nowData+="<div class='col-xs-2' style='text-align:center;'>";   
    nowData+="<a href='javascript:void(0);' onclick='sharefb();'><img src='images/fb_icon.png' alt='image'  style='width:30px;height:30px;vertical-align:text-bottom;' /></a></div></div>";
    nowData+="</div></div>" ;    
    nowData+="<div class='row'><table class='nowDataTable' style='width:100%;' >" ; 
    nowData+=  "<tr><td>Precipitation</td><td>" +current['precipitation']+"</td>  </tr>";      
    nowData+="<tr><td> Chance of Rain </td><td>"+current['rain']+"</td></tr>   ";      
    nowData+=  "<tr><td>Wind Speed</td><td>" +current['wind']+  "</td>  </tr>";      
    nowData+="<tr><td> Dew Point </td><td>"+current['dew']+"</td></tr>   ";   
    nowData+=  "<tr><td>Humidity</td><td>"+current['humidity']+"  </td>  </tr>";      
    nowData+="<tr><td>Visibility </td><td>"+current['visibility']+" </td></tr>   ";   
    nowData+=  "<tr><td>Sunrise</td><td>"+current['rise']+"</td>  </tr>";      
    nowData+="<tr><td>Sunset </td><td>"+current['set']+"</td></tr> </table></div></div>";
    nowData+="<div class='col-md-6 col-no-gutter' style='height:390px;border: 2px solid White;' ><div id='Weathermap' class='col-md-6 col-no-gutter' style='width:100%;height:100%;' >  </div></div> </div>";
     
$("#Now").html(nowData); 
   
         
     }
          
function Next24data(nxt24,deg)
{
    var data24='';
        
        data24+= "<table class='col-md-12 table table-condensed'>";
        data24+= " <tr style='background-color:#3071A9;color:White;' >";
        data24+=    "<th class='col-md-3' style='text-align:center;'>Time</th>";
        data24+=    "<th class='col-md-2' style='text-align:center;'>Summary</th>";
        data24+=    "<th class='col-md-2' style='text-align:center;'>Cloud Cover</th>";
        data24+=   "<th class='col-md-2' style='text-align:center;'>Temp (&deg"+deg+") </th>";
        data24+=    "<th class='col-md-3' style='text-align:center;'>View Details</th>";
        data24+="</tr>";
     for(i=1;i<=24;i++)
     {
        
        data24+="<tr style='background-color:White;color:Black;'>";
        data24+=    "<td class='col-md-3' style='text-align:center;'>"+nxt24[i]['time']+"</td>";
        data24+=    "<td class='col-md-2' style='text-align:center;'><img src='/images/"+nxt24[i]['icon']+".png'";               data24+="alt='"+nxt24[i]['summary']+"' title='"+nxt24[i]['summary']+"' style='width:35px;height:35px;'/></td> ";
        data24+=    "<td class='col-md-2' style='text-align:center;'>"+nxt24[i]['cloudCover']+"</td>";
        data24+=    "<td class='col-md-2' style='text-align:center;'>"+nxt24[i]['temperature']+"</td>";
        data24+=   "<td class='col-md-3' style='text-align:center;'>";
        data24+="<span data-toggle='collapse' data-target='#next24"+i+"' class='accordion-toggle glyphicons glyphicons-plus ' ";
        data24+="style='color:#2F70A8;font-size:2.0em; cursor:pointer'>+</span></td>";
        data24+=" </tr>";
        data24+="<tr class='accordian-body collapse' id='next24"+i+"' style='background-color:#EEEEEE;'>";
        data24+=            "<td colspan='5' class='col-md-12' >";
        data24+=            "<div class='container col-md-12 ' >  ";  
        data24+=            "<table class='col-md-12 ' style='border:0.8px gray;width:100%;'> ";
        data24+=             "<tr style='color:Black;background-color:White;'>";
        data24+=                 "<th class='col-md-3' style='text-align:center;'> Wind </th> ";  
        data24+=                  "<th class='col-md-3' style='text-align:center;'> Humidity </th> ";
        data24+=                  "<th class='col-md-3' style='text-align:center;'> Visibility </th>";
        data24+=                 "<th class='col-md-3' style='text-align:center;'> Pressure </th>";   
        data24+=            "</tr>";
        data24+=            "<tr style='background-color:#EEEEEE;'>";
        data24+=                "<td class='col-md-3' style='text-align:center;'>"+nxt24[i]['windSpeed']+" </td> ";   
        data24+=                "<td class='col-md-3' style='text-align:center;'>"+nxt24[i]['humidity']+" </td>"; 
        data24+=                "<td class='col-md-3' style='text-align:center;'>"+nxt24[i]['visibility']+ "</td>"; 
        data24+=                "<td class='col-md-3' style='text-align:center;'>"+nxt24[i]['pressure']+"</td>"; 
        data24+=            "</tr>";
        data24+=            "</table>";
        data24+=            "</div>";     
        data24+=            "</td>";
        data24+=        "</tr>";
         
     }
        data24+="</table>";
       
    $('#Next24').html(data24);
}         
 
function Next7data(nxt7,city,state)
{
        var data7='';
        $('#modalData').html('');
        var color='';
    data7+="<div class='row col-md-12' style='color:White;'>";
        for(j=1;j<=7;j++)
        {       
       modalWindow(nxt7,j,city,state);     
        switch(j)
        {
            case 1:
                {
            color='#367DB5';
                break;
                }
            case 2:
            color='#EC4444';
                break;
                case 3:
            color='#E68E4F';
                break;
            case 4:
            color='#A7A439';
                break;
                case 5:
            color='#9770A7';
                break;
            case 6:
            color='#F37C7E';
                break;
                case 7:
            color='#CE4571';
                break;
        }
        
        if(j==1)            
        {
                   
data7+= "<div style='margin:10px 7px 10px 7px;'><div class='col-md-1 col-md-offset-2' data-toggle='modal' data-target='#Next7Modal"+j+"' style='border-radius:10px;background-color:"+color+";text-align:center;'>";
data7+="<p style='font-size:15px;font-weight:bold;width:100%;'>"+nxt7[j]['day']+"</p>";
    data7+="<p style='font-size:15px;font-weight:bold;'>"+nxt7[j]['monthDate']+"</p>";
    data7+="<img src='/images/"+nxt7[j]['icon']+".png' alt='"+nxt7[j]['summary'];
    data7+="' title='"+nxt7[j]['summary']+"' style='width:70px;height:65px;'/> ";
    data7+="<p style='font-size:15px'>Min Temp</p>";
    data7+="<p style='font-size:37px;font-weight:bold;'>"+nxt7[j]['minTemp']+"</p>";
    data7+="<p style='font-size:15px'>Max Temp</p>";
    data7+="<p style='font-size:37px;font-weight:bold;'>"+nxt7[j]['maxTemp']+"</p>";
    data7+="</div> </div>";              
        }
        else
        {             
        data7+= "<div class='col-md-1 tiles' data-toggle='modal' data-target='#Next7Modal"+j+"' style='border-radius:10px;background-color:"+color+";text-align:center;'>";
    data7+="<p style='font-size:15px;font-weight:bold;width:100%;'>"+nxt7[j]['day']+"</p>";
    data7+="<p style='font-size:15px;font-weight:bold;'>"+nxt7[j]['monthDate']+"</p>";
    data7+="<img src='/images/"+nxt7[j]['icon']+".png' alt='"+nxt7[j]['summary'];
    data7+="' title='"+nxt7[j]['summary']+"' style='width:70px;height:65px;'/> ";
    data7+="<p style='font-size:15px'>Min Temp</p>";
    data7+="<p style='font-size:37px;font-weight:bold;'>"+nxt7[j]['minTemp']+"</p>";
    data7+="<p style='font-size:15px'>Max Temp</p>";
    data7+="<p style='font-size:37px;font-weight:bold;'>"+nxt7[j]['maxTemp']+"</p>";
    data7+=" </div>";  
        
        }
    }
    data7+="</div>"
    $('#Next7Tiles').html(data7);
}  
     
function modalWindow(nxt7,k,city,state)
{
  var mData="";
  mData+=" <div id='Next7Modal"+k+"' class='modal fade' role='dialog'>";
  mData+="  <div class='modal-dialog'>";                     
  mData+=" <div class='modal-content col-md-12'>";
  mData+="  <div class='modal-header'>";
  mData+="    <button type='button' class='close' data-dismiss='modal'>&times;</button>";
  mData+="    <p style='font-weight:bold;font-size:16px'>Weather in "+city+" on "+nxt7[k]['monthDate']+"</p>";
  mData+="  </div>";
  mData+="   <div class='modal-body col-md-12'>";
  mData+="   <div class='row col-md-12'>";
  mData+="     <div class='col-md-12' style='text-align:center;margin:20px 0px;font-weight:bold;'>";
  mData+="     <img src='/images/"+nxt7[k]['icon']+".png' alt='"+nxt7[k]['summary']+"' title='"+nxt7[k]['summary']+"' style='width:100px;height:100px;'/> ";
  mData+="    </div>";              
  mData+="  </div>";
  mData+="  <div class='row' style='text-align:center;'>";
  mData+="  <div class='col-md-11' style='text-align:center;'>";
  mData+="<p style='font-size:22px'> "+nxt7[k]['day']+": <span style='color:orange;'>"+nxt7[k]['summary']+"</span></p>";
  mData+=" </div> ";                       
  mData+="    </div>";
  mData+="  <div class='row col-md-12' style='text-align:center;'>";
  mData+="  <div class='col-md-4'>";
  mData+="    <p style='font-weight:bold;font-size:16px'> Sunrise Time</p>";
  mData+="    <p> "+nxt7[k]['sunriseTime']+"</p>";              
  mData+="    </div>";
  mData+="      <div class='col-md-4' style='text-align:center;'>";
  mData+="        <p style='font-weight:bold;font-size:16px'> Sunset Time</p>";
  mData+="          <p>"+nxt7[k]['sunsetTime']+"</p> ";             
  mData+="         </div>";
  mData+="       <div class='col-md-4' style='text-align:center;'>";
  mData+="         <p style='font-weight:bold;font-size:16px'> Humidity</p>";
  mData+="        <p>"+nxt7[k]['humidity']+"</p>";           
  mData+="         </div>";
  mData+="       </div>";
  mData+="        <div class='row col-md-12'>";
  mData+="       <div class='col-md-4' style='text-align:center;'>";
  mData+="        <p style='font-weight:bold;font-size:16px'> Windspeed</p>";
  mData+="        <p>"+nxt7[k]['windSpeed']+"</p> ";              
  mData+="        </div> ";
  mData+="         <div class='col-md-4' style='text-align:center;'>";
  mData+="          <p style='font-weight:bold;font-size:16px'> Visibility</p>";
  mData+="          <p>"+nxt7[k]['visibility']+"</p>";              
  mData+="         </div>";
  mData+="         <div class='col-md-4' style='text-align:center;'>";
  mData+="         <p style='font-weight:bold;font-size:16px'> Pressure</p>";
  mData+="         <p>"+nxt7[k]['pressure']+"</p>";               
  mData+="        </div>";
  mData+="       </div>";
  mData+="       </div>";
  mData+="       <div class='modal-footer'>";
  mData+="          <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>";
  mData+="       </div></div></div></div>";
 $("#modalData").append(mData);
 } 

$("#clear").click(function(event)
{
    
 $("#Street").val('');    
 $("#City").val('');   
$("#State option:selected").val('');    
$("input[name=Degree]:checked").val('us');    
});
 
$("#submit").click(function(event){
 event.preventDefault();
var street= $("#Street").val();    
var city= $("#City").val();
var state=$("#State option:selected").val();
var degree=$("input[name=Degree]:checked").val();
var deg='';    
    if(degree=='us')
    {
        deg = 'F'; 
    }
    else
    {
        deg='C';        
    }
if($.trim(street)==''||$.trim(city)==''||state=='')
 {
   if($.trim(street)=='')
       $('#streetVal').show();
   if ($.trim(city)=='')
       $('#cityVal').show();
   if (state=='')
       $('#stateVal').show();
     
     return false;
 }
 
 $.ajax({ 
url: 'WeatherForecastHW8.php',
data: { 
Street:street, 
City:city , 
State:state,
Degree:degree
},
method: 'GET',
success: function(output) 
 {
obj = jQuery.parseJSON(output);
//alert(output);
var res=obj['Error'];     
     if(res== null )
     {
        $('#resultTab').show();    
        var current=obj['currData'];
        fb_icon+=current['icon']+'.png'; 
        fb_summary=current['summary'];
        fb_temperature=current['temperature']+"\xB0"+deg; 
        latitude= current['lat'];
        longitude=current['lng']; 
        CurrentData(current,city,state,deg);
        generateMap(latitude,longitude);      
        var nxt24= obj['Next24Data'];
        Next24data(nxt24,deg);
        var nxt7= obj['Next7Data']; 
        Next7data(nxt7,city,state); 
            
     }
     else
     {
         $('#resultTab').hide();
         alert('No results found!!');
        return false;         
     }
     
}
 });
});
 });
    
    </script>
    
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> 
</head>
<body class="bck">
    <form id="forecast" name="forecast" method="post" action="">
        <div class="container"> 
            <div class="row">  
            <div class="col-md-12 col-centered"> 
              <h1>Forecast Search</h1>  
            </div>         
               </div>
        <div class="row transparent">                    
            <div class="col-md-3 col-no-gutter input ">    
   <lable> <span style="color:White;">Street Address: </span> <span style="color:red;">*</span></lable> <br> <input type="text" style="width:100%;border-radius:3px;" placeholder="Enter Street Address" id="Street" name="Street" ></input>
                    <div id="streetVal" style="display:none"><span style="color:red">Please enter the Street Address </span></div>
                    </div>
            <div class="col-md-2 col-no-gutter input" > 
                    <lable><span style="color:White;">City:</span> <span style="color:red;">*</span></lable><br> <input type="text" placeholder="Enter the City Name" id="City" style="width:100%;border-radius:3px; " name="City" > </input>  
            <div id="cityVal" style="display:none"><span style="color:red">Please enter the City </span></div>
                </div>
            <div class="col-md-2  col-no-gutter input"> 
            <label style='margin-bottom: 0px;'><span style="color:White;">State:</span><span style="color:red;">*</span></label> <br> <select id="State" name="State" placeholder=" " style='height: 25px; width:100%;border-radius:3px;' >
        <option value="">Select your State</option>       
        <option value="AL">Alabama</option>
        <option value="AK">Alaska</option>
        <option value="AZ">Arizona</option>         
        <option value="AS">Arkansas</option>        
        <option value="CA">California</option>
        <option value="CO">Colorado</option>
        <option value="DE">Delaware</option>
        <option value="DC">District of Columbia</option>         
        <option value="FL">Florida </option>        
        <option value="GA">Georgia</option>
        <option value="HI">Hawaii</option>
        <option value="ID">Idaho</option>
        <option value="IL">Illinois</option>         
        <option value="IN">Indiana</option>        
        <option value="IA">Iowa</option>
        <option value="KS">Kansas</option>
        <option value="KY">Kentucky</option>
        <option value="LA">Louisiana</option>         
        <option value="ME">Maine </option>        
        <option value="MD">Maryland</option>
        <option value="MA">Massachusetts</option>
        <option value="MI">Michigan</option>
        <option value="MN">Minnesota</option>         
        <option value="MS">Mississippi</option>        
        <option value="MO">Missouri</option>
        <option value="MT">Montana</option>
        <option value="NE">Nebraska</option>
        <option value="NV">Nevada</option>         
        <option value="NH">New Hampshire </option>        
        <option value="NJ">New Jersey</option>
        <option value="NM">New Mexico</option>
        <option value="NY">New York</option>
        <option value="NC">North Carolina</option>         
        <option value="ND">North Dakota</option>        
        <option value="OH">Ohio</option>
        <option value="OK">Oklahoma</option>
        <option value="OR">Oregon</option>
        <option value="PA">Pennsylvania</option>         
        <option value="RI">Rhode Island </option>        
        <option value="SC">South Carolina</option> 
        <option value="SD">South Dakota</option>
        <option value="TN">Tennessee</option>
        <option value="TX">Texas</option>
        <option value="UT">Utah</option>         
        <option value="VT">Vermont</option>        
        <option value="VA">Virginia</option>
        <option value="WA">Washington</option>
        <option value="WV">West Virginia</option>
        <option value="WI">Wisconsin</option>         
        <option value="WY">Wyoming</option>        
        <option value="VA">Virginia</option>
        </select> 
       
        <div id="stateVal" style="display:none"><span style="color:red">Please enter the State </span></div>    
            </div>        
            <div class="col-md-2 col-no-gutter input" >
                <label> <span style="color:White;">Degree:</span> <span style="color:red;">*</span></label>
                <br>
<input type="radio" name="Degree" value="us" id="Degree" checked="true" > </input><label style="color:White;margin:0px 5px;"> Fahrenheit  </label>
<input type="radio" name="Degree" value="si" id="Degree" > </input><label style="color:White;margin:0px 5px;">Celsius</label>
        
        </div> 
            <div class='col-md-1'> </div>
            <div class="col-md-2 col-no-gutter input" style='text-align: right;' > 
    <button  name="submit" id="submit" class="btn btn-primary btn-sm" ><span class="glyphicon glyphicon-search" > Search</span></button>
    <button  name="submit" id="clear" class="btn btn-default btn-sm" ><span class="glyphicon glyphicon-refresh" > Clear</span></button>   
    
     <br> 
    <br>
    <div class="col-md-12 col-no-gutter">
        <label><span style="color:White;font-size:12px;">Powered by:</span> <a href="http://forecast.io"><img class="imagelogo" src="http://cs-server.usc.edu:45678/hw/hw8/images/forecast_logo.png" style='width:80px;height:40px;' alt="image" /></a></label>
    </div>
        </div>
        </div>

<div class='row'><hr></div>
</form>

<div class="tab-content row" id='resultTab'>
     <div id="resultTabList">
      <ul class="nav nav-tabs" role="tablist">
       <li role="presentation" class="active"><a href="#Now" aria-controls="Now" role="tab" data-toggle="tab">Right Now</a></li>
       <li role="presentation"><a href="#Next24" aria-controls="Next24" role="tab" data-toggle="tab">Next 24 Hours</a></li>
       <li role="presentation"><a href="#Next7" aria-controls="Next7" role="tab" data-toggle="tab">Next 7 Days</a></li>
      </ul>
     </div>
     <div role="tabpanel" class="tab-pane col-md-12 active" id="Now" style='margin-bottom: 20px;'> </div>
     <div role="tabpanel" class="tab-pane" id="Next24"></div>
     <div role="tabpanel" class="tab-pane " id="Next7">
          <div class='container'>
                <div class='row' style='background-color:Black;'>
                    <div id='Next7Tiles'>
                            
                    </div>    
                    <div id='modalData'>  
                    </div>
                </div>
         </div>
    </div>
    </div>


</div>   

</body>
</html>