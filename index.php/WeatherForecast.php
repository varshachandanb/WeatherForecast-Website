<?php

$Street = $_GET['Street']; 
$street =urlencode(trim($Street));
 $City= $_GET['City']; 
$city= urlencode(trim($City));
$state=$_GET['State'];
$degree=$_GET['Degree'];


$geocode_key="AIzaSyCDzBAcz1SgVh6W7wX6vrFh9v4P9BK8wrg";
$xmlurl="https://maps.googleapis.com/maps/api/geocode/xml?address=".$street.",".$city.",".$state."&key=".$geocode_key;
$xml=simplexml_load_file($xmlurl);
$xml->result->geometry->location->lat;
$WeatherData = array();
if($xml->status!="ZERO_RESULTS")
{

$lat=$xml->result->geometry->location->lat;
$lng=$xml->result->geometry->location->lng;    
$api_key="c740254888f9547179f0e8192e3eadad";
$forecastURL = "https://api.forecast.io/forecast/".$api_key."/".$lat.",".$lng."?units=".$degree."&exclude=flags";
$var_dump=(json_decode(file_get_contents($forecastURL)));
 date_default_timezone_set($var_dump->timezone);    
function CurrentlyInfo($var_dump,$degree,$city,$state,$lat,$lng)
   {
     
    $summary=$var_dump->currently->summary;
    $wspeed= $var_dump->currently->windSpeed;   
    $dew=($var_dump->currently->dewPoint);
    $h = $var_dump->currently->humidity;
    $prprob =$var_dump->currently->precipProbability;   
    $hmd=intval($h*100)." %";    
    $srise=$var_dump->daily->data[0]->sunriseTime;
    $rise=date("h:i A",$srise);
    $sset=$var_dump->daily->data[0]->sunsetTime;
    $set=date("h:i A",$sset);    
    $prec= $var_dump->currently->precipIntensity;
    if($degree=='us')
     {
        $temp= intval($var_dump->currently->temperature);    
        $wind=round($wspeed,2)." mph";     
        $dew= round($dew,2)."&deg F";
        if(isset($var_dump->currently->visibility))
        {
            $vb=$var_dump->currently->visibility;
            $vsb=round($vb,2)." mi";
            
        }
        else
        {$vsb='N.A';  }  
     }
    else if($degree=='si')
    {
        $prec= ($prec/25.4);
        $temp=intval($var_dump->currently->temperature);
        if(isset($var_dump->currently->visibility))            
        {
            $vb=$var_dump->currently->visibility;
            $vsb=round($vb,2)." km";
        } 
        else
        {$vsb='N.A'; }
        $wind=round($wspeed,2)." m/s";
        $dew= round($dew,2)."&deg C";
    }
        
        if ($prec >= 0 && $prec<0.002)
            $prp="None";        
        elseif ($prec >= 0.002 && $prec < 0.017)
            $prp="Very Light";        
        elseif ($prec >=0.017 && $prec <0.1)
            $prp="Light";
        elseif ($prec >= 0.1 && $prec <0.4)
            $prp="Moderate";
        elseif ($prec >= 0.4)
            $prp="Heavy";
    
    $rain= intval($prprob*100)." %"; 
    $curr= $var_dump->currently->icon;
    $crnt =  array();
    $crnt['icon']=$var_dump->currently->icon;   
    $crnt['summary']=$summary;
    $crnt['minTemp']=intval($var_dump->daily->data[0]->temperatureMin);  
    $crnt['maxTemp']=intval($var_dump->daily->data[0]->temperatureMax);   
    $crnt['temperature']=$temp;
    $crnt['precipitation']=$prp;
    $crnt['rain']=$rain;
    $crnt['wind']=$wind;   
    $crnt['dew']=$dew;
    $crnt['humidity']=$hmd;
    $crnt['visibility']=$vsb;
    $crnt['rise']=$rise;
    $crnt['set']=$set;        
    $crnt['lat']=$lat;
    $crnt['lng']=$lng;
    
       return $crnt;
   }

function Next24Info($hourlyData,$degree)
//var_dump->hourly->data   
{
    
$nxt= array();   
$tm= $hourlyData->time;
$time=date("h:i A",$tm);
$nxt['time']=$time;
$nxt['summary']=$hourlyData->summary;
$nxt['icon']=$hourlyData->icon;
$cCover= intval(($hourlyData->cloudCover)*100)."%";   
$nxt['cloudCover']=$cCover;    
$tmp= round($hourlyData->temperature,2);
$hmdt= intval(($hourlyData->humidity)*100)."%"; 
$nxt['humidity']=$hmdt;
$wspeed= $hourlyData->windSpeed; 
$nxt['temperature']=$tmp; 
         
if($degree=='us')
{ 
        if(isset($hourlyData->visibility))
        {
          $vb=$hourlyData->visibility; 
          $vsb=round($vb,2)." mi";
        }
        else
        {
           $vsb='N.A'; 
        }    

$windSpeed=$wspeed."mph";
$nxt['pressure']=$hourlyData->pressure."mb";   
}
if ($degree=='si')
 {
        if(isset($hourlyData->visibility))
        {
          $vb=$hourlyData->visibility; 
          $vsb=round($vb,2)." mi";
        }
        else
        {
           $vsb='N.A'; 
        }   
$windSpeed=$wspeed."m/s";
$nxt['pressure']=$hourlyData->pressure."hPa";      
     
 }
    $nxt['visibility']=$vsb;
    $nxt['windSpeed']=$windSpeed;
    return $nxt;
}

function Next7Info($weeklyData,$degree)
{
    //var_dump->daily->data   
$week= array();
$day=date("l",$weeklyData->time);        
 $week['day']= $day;  
$mDate=date("M j",$weeklyData->time);     
 $week['monthDate']=$mDate;
$week['summary']=$weeklyData->summary;    
$srise =$weeklyData->sunriseTime;   
$riseTime=date("h:i a",$srise);  
$sset =$weeklyData->sunsetTime; 
$setTime=date("h:i a",$sset);      
$week['sunriseTime']=$riseTime;
$week['sunsetTime']=$setTime;
$hmdt= intval(($weeklyData->humidity)*100)."%"; 
$week['humidity']=$hmdt;     
$week['icon']=$weeklyData->icon;    
$week['minTemp']=intval($weeklyData->temperatureMin)."&deg";  
$week['maxTemp']=intval($weeklyData->temperatureMax)."&deg";
$wspeed= $weeklyData->windSpeed;       
if($degree=='us')
{  
       if(isset($weeklyData->visibility))
        {
          $vb=$weeklyData->visibility; 
          $vsb=round($vb,2)." mi";
        }
        else
        {
           $vsb='N.A'; 
        }   
$windSpeed=$wspeed." mph";
$week['pressure']=$weeklyData->pressure." mb";   
}
else
 {
    if(isset($weeklyData->visibility))
        {
          $vb=$weeklyData->visibility; 
          $vsb=round($vb,2)." mi";
        }
        else
        {
           $vsb='N.A'; 
        }   

$windSpeed=$wspeed." m/s";
$week['pressure']=$weeklyData->pressure." hPa";      
     
 }
    $week['visibility']=$vsb;
    $week['windSpeed']=$windSpeed;
    return $week;
}
    
    
$lt=explode(":",$lat)[0];
$lg=explode(":",$lng)[0];   
$currData=CurrentlyInfo($var_dump,$degree,$city,$state,$lt,$lg);
$Next24Data= array();
foreach($var_dump->hourly->data as $Next24Object)
{
array_push($Next24Data,Next24Info($Next24Object,$degree));
    
}

$Next7Data= array();
foreach($var_dump->daily->data as $Next7Object)
{
array_push($Next7Data,Next7Info($Next7Object,$degree));   
}

$WeatherData['currData']=$currData;
$WeatherData['Next24Data']=$Next24Data;
$WeatherData['Next7Data']=$Next7Data;
$WeatherData['Error']= null;     
}
else
{
  $WeatherData['Error']='No results found!!';
  
}
$FinalData=json_encode($WeatherData);
echo $FinalData;
?>