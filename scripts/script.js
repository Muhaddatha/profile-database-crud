function validateFrom() {

    console.log('Validating...');

    try {

        pw = document.getElementById('id_1723').value;
        username = document.getElementById('nam').value;

        console.log("Validating pw="+pw);

        if (pw == null || pw == "" || username == null || username == "") {

            alert("Both fields must be filled out");

            return false;

        }

        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(re.test(email) == false){
            alert("Invalid email address");
            return false;
        }

        return true;

    } catch(e) {

        return false;

    }

    return false;

}