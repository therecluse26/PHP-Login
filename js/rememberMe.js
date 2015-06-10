//get the cookie from the Browser
function getCookie(identifier)
{
    if(document.cookie.length > 0)
    {
        idstart = document.cookie.indexOf(identifier + "=");

        if(idstart != -1)
        {
            idstart += (identifier.length + 1);
            idend = document.cookie.indexOf("^",idstart);

            if(idend == -1)
            {
                idend = document.cookie.length;
            }
            return unescape(document.cookie.substring(idstart, idend));
        }
    }
    return "";
}

//set the cookie of browser
function setCookie(usr, pwd,chec, expiredays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);

    document.cookie = "usrname" + "=" + escape(usr) + "^" + "password" + "=" + escape(pwd) + "^" + "checked" + "=" + escape(chec) + ((expiredays == null)?"":"^; expires =" + exdate.toGMTString());

    console.log(document.cookie);
}

//test the cookie have or not 
function checkCookie()
{
    //alert("Hello World")
    //alert(document.cookie);
    
    var usrname = getCookie('usrname');
    var password = getCookie('password');
    var checked = getCookie('checked');

    if(usrname != null && usrname != "" && password != null && password != "" && checked != null && checked != "")
    {
        //alert('Your name:' + usrname + 'Your password:' + password);
        //if the Cookie have storage,set the HTML element val
        $("#myusername").val(usrname);
        $("#mypassword").val(password);
        $("#checkme").attr("checked", "checked");
    }
}

//clear the cookie
function cleanCookie(usrname, password, checked)
{
    document.cookie = usrname+"="+";"+password+"="+";"+checked+"="+";";expires = (new Date(0)).toGMTString();  
}
