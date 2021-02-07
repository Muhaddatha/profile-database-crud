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

        return true;

    } catch(e) {

        return false;

    }

    return false;

}