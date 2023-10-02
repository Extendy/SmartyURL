<?php

namespace App\Controllers;

use Extendy\Smartyurl\WorldCountries;

/**
 * This contoller use as assist of the app
 */
class Assist extends BaseController
{
    public function index()
    {
    }

    /**
     * This Controller return the new url javascriot needed codes as application/javascript response
     */
    public function getAddNewUrlJsAssist(): \CodeIgniter\HTTP\ResponseInterface
    {
        $tests  = 'sam';
        $jsCode = '';

        $this->response->setContentType('application/javascript', 'utf-8');
        // refer for view ur/new.php because the following code will embedded to it
        $jsCode .= <<< EOT
            /*!
             * SmartyURL
             * https://extendy.net/
             *
             * Copyright (c) 2023 Mohammed AlShannaq
             * Released under the MIT license
             * https://github.com/Extendy/SmartyURL/blob/main/LICENSE
             *
             * Date: 2023-09-29T05:11Z
             */
            $( document ).ready(function() {
                console.log("{$tests}");



                //because tha button is  loaded dynamically through innerHTML
                $('#newurlcontainer').on('click', '#geoconditioncancel', function() {
                    delGeocondition()
                });


                function delGeocondition(){
                    const divgeodiv = document.getElementById("geodiv");
                    $(divgeodiv).remove();
                    $("#choosUrlCondition").prop("disabled", false);
                    $("#choosUrlCondition").show();
                     hiddenInput.value = "";
                }




                $('#newurlcontainer').on('click', '#deviceconditioncancel', function() {
                    $('#devicediv').remove();
                    $("#choosUrlCondition").prop("disabled", false);
                    $("#choosUrlCondition").show();
                    hiddenInput.value = "";

                });

                //adding new Country input text for geolocation condition
                 $('#newurlcontainer').on('click', '#addNewCountryBtn', function() {
                   appendGeoLocationCountrySpace();

                });



                 $(document).on("click", ".btn-danger", function() {
                   // Remove the row that the delete button is inside of
                    $(this).closest(".row").remove();
                   //check if this is the last country in the condition
                    const divElement = $("#conditions_div");
                    if (!divElement.find(".btn-danger").length > 0) {
                        // The div element does not contain any `.btn-danger`  so i will delete the condtion
                        delGeocondition()
                    }



                 });



            });


             const hiddenformInput = document.createElement("input");
             hiddenformInput.type = "hidden";
             const hiddenInput = document.querySelector("#redirectCondition");



            //for geolocation condition
            document.addEventListener("DOMContentLoaded", function() {
                const container = document.getElementById("conditions_div");
                const addButton = document.getElementById("addGeoloctionCond");






                addButton.addEventListener("click", function() {




                    // Create a new div element with the HTML content you want to add
                    hiddenInput.value = "geolocation";
                    const newDiv = document.createElement("div");
                    newDiv.classList.add("card", "mt-4"); // Add one or more classes as needed
                    newDiv.id = "geodiv";


                     container.appendChild(newDiv);

                     const geodivcardheaderwDiv = document.createElement("div");
                     geodivcardheaderwDiv.classList.add("card-header", "bg-light"); // Add one or more classes as needed
                      geodivcardheaderwDiv.id = "geocard-headerdev";
                       geodivcardheaderwDiv.innerHTML = `
                         <span class="mr-auto">Geographical Location</span>
                         <button id="geoconditioncancel" type="button" class="ms-4  mt-1 btn btn-outline-danger btn-sm ml-auto">delete condition</button>
                       `;

                     newDiv.appendChild(geodivcardheaderwDiv);

                     const geocardbodyDev = document.createElement("div");
                     geocardbodyDev.id = "geocardbody"
                     geocardbodyDev.classList.add("card-body"); // Add one or more classes as needed
                        geocardbodyDev.innerHTML = `




                        `;
                    newDiv.appendChild(geocardbodyDev);


                    const addNewCountryBtn = document.createElement("button");
                    addNewCountryBtn.id = "addNewCountryBtn";
                    addNewCountryBtn.innerHTML = "+";
                    addNewCountryBtn.classList.add("btn","btn-dark" , "mt-4"); // Add one or more classes as needed
                    addNewCountryBtn.setAttribute("aria-expanded", false);
                    addNewCountryBtn.setAttribute("type", "button");

                    geocardbodyDev.appendChild(addNewCountryBtn);
                    appendGeoLocationCountrySpace();


                    $('#newurlconditionmenu').removeClass('show');
                    //disalethe choosUrlCondition to avoid reusing it
                    $("#choosUrlCondition").prop("disabled", true);
                    $("#choosUrlCondition").hide();















                });
            });



            function appendGeoLocationCountrySpace() {
                const geocardbody = document.getElementById("geocardbody");
                const addNewCountryBtn = document.getElementById("addNewCountryBtn");
                const geocountryfiledsdiv =  document.createElement("div");

                    geocountryfiledsdiv.innerHTML = `


                <!-- Create a row element -->
                  <div class="row">
                    <!-- Create a div element for the select input -->
                    <div class="col-md-3 mt-2">
                      <select placeholder="Country" name="geocountry[]" class="GeoLocationCountry select2_el form-control" required>
                           <option value="">Select Country</option>
            EOT;
        $Countries      = new WorldCountries();
        $worldcountries = $Countries->getCountriesList();

        foreach ($worldcountries as $worldcountryKey => $worldcountryVal) {
            $jsCode .= "<option value='" . $worldcountryKey . "'>" . $worldcountryVal . '</option>
                        ';
        }

        $jsCode .= '
                      </select>';

        $jsCode .= <<< 'EOT'
                               </div>

                               <!-- Create a div element for the text input -->
                               <div class="col-md-8 mt-2">
                                 <input placeholder="URL" type="url" name="geofinalurl[]" class="form-control" required />
                               </div>

                               <!-- Create a div element for the delete button -->
                               <div class="col-md-1 mt-2">
                                 <button type="button" class="delgeoCountrybtn btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                               </div>
                             </div>
                       `;


                               geocardbody.insertBefore(geocountryfiledsdiv,addNewCountryBtn);
                               initailizeSelect2();



                       }


                   function initailizeSelect2(){
                        $(".select2_el").select2({
                        searching: true,
                        theme: 'bootstrap'
                        });


                   }




                       console.log( "controller assist/newurl.js file is ready ! @TODO remove me in production" );

            EOT;

        // for device
        $jsCode .= <<< 'EOT'
                        //for device
                        document.addEventListener("DOMContentLoaded", function() {
                            const container = document.getElementById("conditions_div");
                            const addButton = document.getElementById("addDeviceCond");




                            addButton.addEventListener("click", function() {
                                // Create a new div element with the HTML content you want to add
                                hiddenInput.value = "device";
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
                                $("#choosUrlCondition").hide();

                                // Append the new div to the container
                                container.appendChild(newDiv);

                            });
                        });


            EOT;

        // Return javascript contents
        return $this->response->setBody($jsCode);
    }
}
