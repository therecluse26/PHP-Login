document.getElementById('keyword-div').onkeydown = function(e){
   if(e.keyCode == 13){
     // submit
rowCount();
   }
};
function countAndSearchPrev() {
    //rowCount();

    var count = parseInt(document.getElementById("rowcount-div").value);

    searchResults(-20, count);
}

function countAndSearchNext() {
    //rowCount();

    var count = parseInt(document.getElementById("rowcount-div").value);

    searchResults(20, count);
}

function searchResults(num, count){
var start = document.getElementById("offset-div").value;

var offset = parseInt(start);

document.getElementById("resultText").style.visibility = "visible";
    // Resets search result to 0 when search button is hit (Passes 0 parameter)
    if(num == 0){
        offset = 0;
        document.getElementById("countDisplay").innerHTML=document.getElementById('rowcount-div').value;

    } else {
        offset = (offset + parseInt(num));
        document.getElementById("countDisplay").innerHTML=document.getElementById('rowcount-div').value;
    }
    if(offset <= 0) {
      document.getElementById('offset-div').value=0;
      document.getElementById("prevBtn").style.visibility = "hidden";
      document.getElementById("nextBtn").style.visibility = "visible";
      document.getElementById("countDisplay").innerHTML=document.getElementById('rowcount-div').value;
      if(count <= 20) {
           document.getElementById("offsetDisplay").innerHTML=document.getElementById('offset-div').value + " - " + document.getElementById('rowcount-div').value;
      } else{
            document.getElementById("offsetDisplay").innerHTML=document.getElementById('offset-div').value + " - " + (parseInt(document.getElementById('offset-div').value) + 20); }
      document.getElementById("countDisplay").innerHTML=document.getElementById('rowcount-div').value;
    }
    else if((offset + 20) >= count) {
      document.getElementById('offset-div').value=(count - 20)
      document.getElementById("nextBtn").style.visibility = "hidden";
      document.getElementById("prevBtn").style.visibility = "visible";
      if((offset + 20) >= count) {
         document.getElementById("offsetDisplay").innerHTML=document.getElementById('offset-div').value + " - " + document.getElementById('rowcount-div').value;
        };
      document.getElementById("countDisplay").innerHTML=document.getElementById('rowcount-div').value;
    }
    else {
      document.getElementById("nextBtn").style.visibility = "visible";
      document.getElementById("prevBtn").style.visibility = "visible";
      document.getElementById('offset-div').value=offset;
      document.getElementById("offsetDisplay").innerHTML=document.getElementById('offset-div').value + " - " + (parseInt(document.getElementById('offset-div').value) + 20);
      document.getElementById("countDisplay").innerHTML=document.getElementById('rowcount-div').value;
    };

var text2 =    document.getElementById("keyword-div").value;
var rowoffset2 = document.getElementById("offset-div").value;
var xmlhttp2;
    if (window.XDomainRequest) xmlhttp2 = new XDomainRequest();
    else if (window.XMLHttpRequest) xmlhttp2 = new XMLHttpRequest();
    else xmlhttp2 = new ActiveXObject("Microsoft.XMLHTTP");
    if (window.XDomainRequest) xmlhttp2.onload = function() {
    document.getElementById("feature-text-keyword").innerHTML = xmlhttp2.responseText;
    }
    else {
    xmlhttp2.onreadystatechange = function() {
        if (xmlhttp2.readyState == 4 && xmlhttp2.status == 200) {
            document.getElementById("feature-text-keyword").innerHTML = xmlhttp2.responseText;
        }
    }
    }
    xmlhttp2.open("GET", document.location.origin + "/inventorgdbconn/keysearch.php?TEXT="+text2+"&OFFSET="+rowoffset2, true);
    xmlhttp2.send();
};

function rowCount(){
var text3 =    document.getElementById("keyword-div").value;
var rowoffset2 = document.getElementById("offset-div").value;
var xmlhttp3;
    if (window.XDomainRequest) xmlhttp3 = new XDomainRequest();
    else if (window.XMLHttpRequest) xmlhttp3 = new XMLHttpRequest();
    else xmlhttp3 = new ActiveXObject("Microsoft.XMLHTTP");
    if (window.XDomainRequest) xmlhttp3.onload = function() {
       document.getElementById("rowcount-div").value = xmlhttp3.responseText;
       var count = parseInt(document.getElementById("rowcount-div").value);
       searchResults(0, count);
        if (parseInt(xmlhttp3.responseText) <= 20){
            document.getElementById("nextBtn").style.visibility = "hidden";
        };
    }
    else {
    xmlhttp3.onreadystatechange = function() {
        if (xmlhttp3.readyState == 4 && xmlhttp3.status == 200) {
            document.getElementById("rowcount-div").value = xmlhttp3.responseText;
            var count = parseInt(document.getElementById("rowcount-div").value);
               searchResults(0, count);
            if (parseInt(xmlhttp3.responseText) <= 20){
                document.getElementById("nextBtn").style.visibility = "hidden";
            };
        }
    }
    }
    xmlhttp3.open("GET", document.location.origin + "/inventorgdbconn/rowcount.php?TEXT="+text3, true);
    xmlhttp3.send();


};


