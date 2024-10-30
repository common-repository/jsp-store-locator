function getGeoloc(){

if (navigator.geolocation)
	{   
      
		navigator.geolocation.getCurrentPosition(function(position) {
   		document.getElementById('geolat').value = position.coords.latitude;
   		document.getElementById('geolong').value = position.coords.longitude;
			
		
   		document.getElementById('locateme').value = "true";
		
		
			
 		submitForm.submit();
		
		
 		}); 
						
	}
	else
	{
  		alert("geolocation services are not supported by your browser.");
	}

}

jQuery( document ).ready(function() {
	// var session = sessionStorage.getItem("SessionName");
	// if(session == "SessionData")
	// {
	// 	alert( "ready" );
	// 	sessionStorage.removeItem("SessionName")
	// }
	jQuery("#map_type").change(function() {
		sessionStorage.SessionName = "SessionData"
		// var url = window.location.href;
		// url = url.replace(/(map_type=).*?(&|$)/,'$1'+'0'+'$2')
		// url += '?map_type=1'
		// window.location.href = url;
		if ('URLSearchParams' in window) {
		    var searchParams = new URLSearchParams(window.location.search);
		    searchParams.set("map_type",jQuery(this).val());
		    window.location.search = searchParams.toString();
		}
	});
});

	  
	  