function selectRating(ratingVal){  
    for(var i = 1; i <= 5; i++){
        if(i <= ratingVal){
            document.getElementById("img"+i).src="images/selected_star.png";
        }else{
            document.getElementById("img"+i).src = "images/unselected_star.png"
        }
    }
    var id = document.getElementById('rating');
    id.value = ratingVal;
}

function validations(){
    var rating = $("#rating").val(); 
    var songName = $("#songName").val(); 
    var artist = $("#artist").val(); 
    if(songName == ""){
        alert("Please enter song name");
        $("#songName").focus(); 
        return false;
    }else if(artist == 0){
        alert("Please select artist name");
        $("#artist").focus(); 
        return false;
    }else if(rating == 0){
        alert("Please select rating"); 
        $("#artist"+rating).focus(); 
        return false;
    }else{
        var ratingId = document.getElementById('finalRating');
        ratingId.value = rating;
        document.getElementById('mode').value = "addSong";
        document.songForm.submit();
    }
}
function validateLogin(){
    let userName = $("#userName").val();
    let password = $("#password").val();
    if(userName == ""){
        alert("Please enter your email id");
        $("#userName").focus();
        return false;
    }else if(password == ""){
        alert("Please enter your password");
        $("#password").focus();
        return false;
    }else{
        document.getElementById('mode').value = "userLogin";
        document.loginForm.submit();
    }
}
function validateRegister(){
    let name = $("#name").val();
    let email = $("#email").val();    
    let password = $("#userPassword").val();
    if(name == ""){
        alert("Please enter your name");
        $("#name").focus();
        return false;
    }else if(email == ""){
        alert("Please enter your email");
        $("#email").focus();
        return false;
    }else if(password == ""){
        alert("Please enter your password");
        $("#userPassword").focus();
        return false;
    }else{
        document.getElementById('mode').value = "registerMode";
        document.registrationForm.submit();
    }
}
function validateArtist(){
    var artistName = $("#artistName").val();
    var dob = $("#dob").val();
    var song = $("#song").val();
    if(artistName == ""){
        alert("Please enter artist name");
        $("#artistName").focus();
        return false;
    }else if(dob == ""){
        alert("Please enter Date of birth");
        $("#dob").focus();
        return false;
    }else if(song == ""){
        alert("Please enter song");
        $("#dob").focus();
        return false;
    }else{
        document.getElementById("mode").value = "addArtistMode";
        document.addArtistForm.submit();
    }
}