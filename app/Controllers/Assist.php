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

    public function getURLtags()
    {
        $this->response->setContentType('application/json', 'utf-8');
        $arrayData = ['sam', 'aka'];
        $jsonCode  = json_encode($arrayData);

        return $this->response->setBody($jsonCode);
    }

    /**
     * This Controller return the new url javascriot needed codes as application/javascript response
     */
    public function getAddNewUrlJsAssist(): \CodeIgniter\HTTP\ResponseInterface
    {
        $jsCode = '';
        // lang vals
        $langByvisitorsGeolocation   = lang('Url.ByvisitorsGeolocation');
        $langDeleteCondition         = lang('Url.DeleteCondition');
        $langSelectCountry           = lang('Url.SelectCountry');
        $langGeographicalLocationURL = lang('Url.GeographicalLocationURL');
        $langbtnDelete               = lang('Common.btnDelete');
        $this->response->setContentType('application/javascript', 'utf-8');
        // refer for view ur/new.php because the following code will embedded to it
        $jsCode .= <<<EOT
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

                //hide choosUrlCondition if hidechoosUrlCondition is defined any where
                //hidechoosUrlCondition defined true when coming from proccess form or edit to hide the choosUrlCondition at start
                //and let javascript decide when to show it.
                if (typeof hidechoosUrlCondition !== "undefined") {
                    if (hidechoosUrlCondition){
                        $("#choosUrlCondition").hide();
                    }
                }


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



                //when deviceconditioncancel button clicked
                $('#newurlcontainer').on('click', '#deviceconditioncancel', function() {
                    delDeviceCondition();

                });

                function delDeviceCondition(){
                     $('#devicediv').remove();
                     $("#choosUrlCondition").prop("disabled", false);
                     $("#choosUrlCondition").show();
                     hiddenInput.value = "";
                }

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
                        delDeviceCondition();
                    }



                 });

              //initailize select2
              initailizeSelect2()

            }); //$( document ).ready(function() {



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
                         <span class="mr-auto">{$langByvisitorsGeolocation}</span>
                         <button id="geoconditioncancel" type="button" class="ms-4  mt-1 btn btn-outline-danger btn-sm ml-auto">{$langDeleteCondition}</button>
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
                           <option value="" disabled selected>{$langSelectCountry}</option>
            EOT;
        $Countries      = new WorldCountries();
        $worldcountries = $Countries->getCountriesList();

        foreach ($worldcountries as $worldcountryKey => $worldcountryVal) {
            $jsCode .= "<option value='" . $worldcountryKey . "'>" . $worldcountryVal . '</option>
                        ';
        }

        $jsCode .= '
                      </select>';

        $jsCode .= <<<EOT
                               </div>

                               <!-- Create a div element for the text input -->
                               <div class="col-md-8 mt-2">
                                 <input placeholder="{$langGeographicalLocationURL}" type="url" name="geofinalurl[]" class="form-control" required />
                               </div>

                               <!-- Create a div element for the delete button -->
                               <div class="col-md-1 mt-2">
                                 <button type="button" class="delgeoCountrybtn btn btn-sm btn-danger"  title="{$langbtnDelete}"><i class="bi bi-trash"></i></button>
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

            EOT;

        // for device condition
        $langUrlByvisitorsDevice        = lang('Url.ByvisitorsDevice');
        $langUrlDeleteCondition         = lang('Url.DeleteCondition');
        $langUrlSelectDeviceFeat        = lang('Url.SelectDeviceFeat');
        $langUrlDeviceAndroidSmartPhone = lang('Url.DeviceAndroidSmartPhone');
        $langUrlDeviceAppleSmartPhone   = lang('Url.DeviceAppleSmartPhone');
        $langUrlDeviceWindowsComputer   = lang('Url.DeviceWindowsComputer');
        $langUrlDeviceFeatURL           = lang('Url.DeviceFeatURL');
        $langCommonbtnDelete            = lang('Common.btnDelete');

        $jsCode .= <<< EOT

                 //for device....

                //this function add new device input to device card body
                function appendDeviceConditionSpace(){
                    const devicecardbodyDiv = document.getElementById("devicecardbody");
                    const addNewDeviceBtn = document.getElementById("addNewDeviceBtn");
                    const devicesfiledsdiv =  document.createElement("div");

                    devicesfiledsdiv.innerHTML = `
                                        <div class="row">
                                            <div class="col-md-3 mt-2">
                                                <select placeholder="Device" name="device[]" class=" select2_el form-control" required>
                                                    <option value="" disabled selected>{$langUrlSelectDeviceFeat}</option>
                                                    <option value="andriodsmartphone">{$langUrlDeviceAndroidSmartPhone}</option>
                                                    <option value="applesmartphone">{$langUrlDeviceAppleSmartPhone}</option>
                                                    <option value="windowscomputer">{$langUrlDeviceWindowsComputer}</option>
                                                </select>

                                            </div>

                                            <!-- Create a div element for the text input -->
                                            <div class="col-md-8 mt-2">
                                                <input placeholder="{$langUrlDeviceFeatURL}" type="url" name="devicefinalurl[]" class="form-control" required />
                                            </div>

                                            <!-- Create a div element for the devcondition  delete button -->
                                            <div class="col-md-1 mt-2">
                                                <button type="button" class="delDevicebtn btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="{$langCommonbtnDelete}"><i class="bi bi-trash"></i></button>
                                            </div>

                                        </div>


                    `;
                    devicecardbodyDiv.insertBefore(devicesfiledsdiv,addNewDeviceBtn);
                    initailizeSelect2();
                } //function appendDeviceConditionSpace



                //for device
                document.addEventListener("DOMContentLoaded", function() {
                    const container = document.getElementById("conditions_div");
                    const addDeviceCond = document.getElementById("addDeviceCond");


                    addDeviceCond.addEventListener("click", function() {
                    hiddenInput.value = "device";

                    //adding devicediv
                    const newDiv = document.createElement("div");
                    newDiv.classList.add("card", "mt-4"); // Add one or more classes as needed
                    newDiv.id = "devicediv";
                    container.appendChild(newDiv);
                    //add devicediv-headerdev
                    const devicedivheaderdevDiv = document.createElement("div");
                    devicedivheaderdevDiv.classList.add("card-header", "bg-light"); // Add one or more classes as needed
                    devicedivheaderdevDiv.id  = "devicediv-headerdev";
                    devicedivheaderdevDiv.innerHTML =`
                                        <span class="mr-auto">{$langUrlByvisitorsDevice}</span>
                                        <button id="deviceconditioncancel" type="button"
                                                class="ms-4  mt-1 btn btn-outline-danger btn-sm ml-auto">
                                            {$langUrlDeleteCondition}
                                        </button>
                    `;

                    newDiv.appendChild(devicedivheaderdevDiv);

                    //adding devicecardbody
                    const devicecardbodyDiv = document.createElement("div");
                    devicecardbodyDiv.classList.add("card-body"); // Add one or more classes as needed
                    devicecardbodyDiv.id = "devicecardbody"
                    newDiv.appendChild(devicecardbodyDiv);

                    //adding addNewDeviceBtn
                    const addNewDeviceBtn = document.createElement("button");
                    addNewDeviceBtn.id = "addNewDeviceBtn";
                    addNewDeviceBtn.innerHTML = "+";
                    addNewDeviceBtn.classList.add("btn","btn-dark" , "mt-4"); // Add one or more classes as needed
                    addNewDeviceBtn.setAttribute("aria-expanded", false);
                    addNewDeviceBtn.setAttribute("type", "button");

                    devicecardbodyDiv.appendChild(addNewDeviceBtn);
                    appendDeviceConditionSpace();

                    $('#newurlconditionmenu').removeClass('show');
                    //disalethe choosUrlCondition to avoid reusing it
                    $("#choosUrlCondition").prop("disabled", true);
                    $("#choosUrlCondition").hide();



                });  //addDeviceCond.addEventListener("click", function() {


                //adding new Device input text when clock on add new device btn for device condition
                 $('#newurlcontainer').on('click', '#addNewDeviceBtn', function() {
                   appendDeviceConditionSpace();
                  }); // $('#newurlcontainer').on('click', '#addNewDeviceBtn', function() {

                });


            EOT;

        // remove spinner
        $jsCode .= <<< 'EOT'


                $(document).ready(function() {
                    //remove loading spinner
                    $('#spinner').hide();
                    $("#spinner").css("display", "none");
                    $("#spinner").removeClass();
                    $('#addnewurlcontent').show();
                });


            EOT;

        // Adding listener to the beforeunload to alert the user when he have unsaved
        $langunSavedFormConfirmMsg = lang('Common.unSavedFormConfirmMsg');
        $jsCode .= <<< EOT


            // Add a function to display a confirmation message when leaving the page
            window.addEventListener('beforeunload', (e) => {
                // Check if there are unsaved changes
                if (hasUnsavedChanges) {
                    // Display a confirmation message
                    e.preventDefault();
                    e.returnValue = '{$langunSavedFormConfirmMsg}';
                }
            });

            // Set a flag for unsaved changes whenever the form changes
            let hasUnsavedChanges = false;

            // Add event listeners to form fields to track changes
            const formFields = document.querySelectorAll('input, textarea, select');
            formFields.forEach((field) => {
                field.addEventListener('change', () => {
                    hasUnsavedChanges = true;
                });
            });

            // Optionally, if you want to reset the flag when the form is submitted
            const form = document.getElementById('addNewURL');
            form.addEventListener('submit', () => {
                hasUnsavedChanges = false;
            });
            EOT;

        $urltags   = service('urltags');
        $tagscloud = $urltags->getUserUrlTagsCloud(user_id(), setting('Smartyurl.urlTagsCloudLimit'), false);
        $whitelist = '[';

        foreach ($tagscloud as $tag) {
            $whitelist .= "{value:'" . $tag['tag_name'] . "', tag_id:'" . $tag['tag_id'] . "'},";
        }
        $whitelist .= '],';
        // dd($whitelist);

        // urlTags
        $jsCode .= <<< EOT
                var input = document.querySelector('input[name="urlTags"]'),
                // init Tagify script on the above inputs
                tagify = new Tagify(input, {
                    whitelist:  {$whitelist}

                    maxTags: 10,
                    dropdown: {
                    maxItems: 20,           // <- mixumum allowed rendered suggestions
                    classname: "tags-look", // <- custom classname for this dropdown, so it could be targeted
                    enabled: 0,             // <- show suggestions on focus
                    closeOnSelect: false    // <- do not hide the suggestions dropdown once an item has been selected
                    }
                })
            EOT;

        // Return javascript contents
        return $this->response->setBody($jsCode);
    }

    public function getSmartyUrlGlobalJsAssist(): \CodeIgniter\HTTP\ResponseInterface
    {
        $smaryUrlGlobaljsCode = '';
        $this->response->setContentType('application/javascript', 'utf-8');

        $smaryUrlGlobaljsCode = <<< 'EOT'
                 /*!
                 * SmartyURL
                 * https://extendy.net/
                 *
                 * SmartyURL Global JS Code
                 *
                 * Copyright (c) 2023 Mohammed AlShannaq
                 * Released under the MIT license
                 * https://github.com/Extendy/SmartyURL/blob/main/LICENSE
                 *
                 * Date: 2023-10-04T01:22Z
                 */



                //this enable popper tooltip
                function initializeTooltips() {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl, {
                                container: 'body' // Set the container option to 'body'
                            });
                        });

                }



                $( document ).ready(function() {

                     //Enable Enable Popper bootstrap tooltips everywhere
                    initializeTooltips();

                    //for .smarty-clickable-link prevent it from changing the URL or causing the page to scroll to the top
                    $('.smarty-clickable-link').click(function (event) {
                        event.preventDefault();
                    });

                }); //  $( document ).ready(function() {





            EOT;

        return $this->response->setBody($smaryUrlGlobaljsCode);
    }
}
