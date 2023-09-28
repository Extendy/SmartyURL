$( document ).ready(function() {
    console.log( "newurl.js file is ready !" );



    //because tha button is  loaded dynamically through innerHTML
    $('#newurlcontainer').on('click', '#geoconditioncancel', function() {
        // Your JavaScript code to run when the button is clicked
        $('#geodiv').remove();
        $("#choosUrlCondition").prop("disabled", false);

    });


    //because tha button is  loaded dynamically through innerHTML
    $('#newurlcontainer').on('click', '#deviceconditioncancel', function() {
        // Your JavaScript code to run when the button is clicked
        $('#devicediv').remove();
        $("#choosUrlCondition").prop("disabled", false);

    });

});


document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById("conditions_div");
    const addButton = document.getElementById("addGeoloctionCond");

    addButton.addEventListener("click", function() {
        // Create a new div element with the HTML content you want to add
        const newDiv = document.createElement("div");
        newDiv.innerHTML = `
             <!--begin: geolocation condition -->

                            <div id="geodiv" class="card mt-4">
                                <div class="card-header bg-light">
                                    <span class="mr-auto">Condition type</span>
                                    <button id="geoconditioncancel" type="button" class="ms-4 btn btn-outline-danger btn-sm ml-auto">delete condition</button>
                                </div>
                                <div class="card-body">
                                    <p>
                                        Condition contents
                                    </p>

                                </div>
                            </div>

          <!-- end: condition -->
        `;
        $('#newurlconditionmenu').removeClass('show');
        //disalethe choosUrlCondition to avoid reusing it
        $("#choosUrlCondition").prop("disabled", true);



        // Append the new div to the container
        container.appendChild(newDiv);
    });
});



document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById("conditions_div");
    const addButton = document.getElementById("addDeviceCond");

    addButton.addEventListener("click", function() {
        // Create a new div element with the HTML content you want to add
        const newDiv = document.createElement("div");
        newDiv.innerHTML = `
             <!--begin: geolocation condition -->
            <div id="devicediv">
                            hello
                             <button id="deviceconditioncancel" type="button" class="ms-4 btn btn-outline-danger btn-sm ml-auto">delete condition</button>
           </div>
          <!-- end: condition -->
        `;
        $('#newurlconditionmenu').removeClass('show');
        //disalethe choosUrlCondition to avoid reusing it
        $("#choosUrlCondition").prop("disabled", true);


        // Append the new div to the container
        container.appendChild(newDiv);
    });
});

