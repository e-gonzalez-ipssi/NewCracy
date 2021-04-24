function apiInscription(){
    event.preventDefault();

    let lName = document.getElementById("lName").value;
    let fName = document.getElementById("fName").value;
    let eMail = document.getElementById("eMail").value;
    let password = document.getElementById("pass").value;
    let modal = document.getElementById("register");

    var url = 'http://localhost:8000/api/register?';
    var params = 'nom=' + lName + '&'+ 'prenom=' + fName + '&'+ 'mail=' + eMail + '&'+ 'password=' + password + '&'+ 'confirmPassword=' + password ;
    var urlFull = url + params ;
    // var urltest = "http://localhost:8000/api/register?nom=Gonzalez&prenom=Esteban&mail=test@test.com&password=0123456Az&confirmPassword=0123456Az";

    fetch(urlFull  ,{method:"post",mode: "no-cors"})
        .then(
            function(response) {
                if (response.status !== 500){
                    alert("Bravo vous faites désormais partie de la team Neocracy");
                    return;
                }
                // Ce qui il ya dans le json
                response.json().then(function(data){
                    console.log(data);
                });
            }
        )
        .catch(function(err){
            console.log(err);
        });

    modal.style.display = "none";
}

function apiConnexion(){
    event.preventDefault();

    let email = document.getElementById("mailCo").value;
    let password = document.getElementById("passCo").value;

    var url = 'http://localhost:8000/api/connect?';
    var params = 'mail=' + email + '&'+ 'password=' + password ;
    var urlFull = url + params ;
    // var urltest = "http://localhost:8000/api/register?nom=Gonzalez&prenom=Esteban&mail=test@test.com&password=0123456Az&confirmPassword=0123456Az";


    fetch(urlFull  ,{method:"post",mode: "no-cors"})
        .then(
            function(response) {
                console.log(response);
                if (response.status == 0){
                    console.log(response);
                    alert("Connexion réussi " );
                    location.replace("../../static/vue/assets/home.html");
                }
                else if (response.status == 500 ){
                    console.log(response);
                    alert("Connexion pas réussi");
                }
            }
        )
        .catch(function(err){
            console.log(err);
    });

    sessionStorage.setItem('userMail' , email);
}

function apiDeconnexion(){
    event.preventDefault();

    let lName = document.getElementById("lName").value;
    let fName = document.getElementById("fName").value;
    let eMail = document.getElementById("eMail").value;
    let password = document.getElementById("pass").value;
    let modal = document.getElementById("register");

    var url = 'http://localhost:8000/api/disconnect?';
    var params = 'nom=' + lName + '&'+ 'prenom=' + fName + '&'+ 'mail=' + eMail + '&'+ 'password=' + password + '&'+ 'confirmPassword=' + password ;
    var urlFull = url + params ;
    // var urltest = "http://localhost:8000/api/register?nom=Gonzalez&prenom=Esteban&mail=test@test.com&password=0123456Az&confirmPassword=0123456Az";

    fetch(urlFull  ,{method:"post",mode: "no-cors"})
        .then(
            function(response) {
                if (response.status !== 500){
                    alert("Bravo vous faites désormais partie de la team Neocracy");
                    return;
                }
                // Ce qui il ya dans le json
                response.json().then(function(data){
                    console.log(data);
                });
            }
        )
        .catch(function(err){
            console.log(err);
        });

    modal.style.display = "none";
}




