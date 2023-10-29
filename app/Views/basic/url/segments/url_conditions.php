<?php use Extendy\Smartyurl\WorldCountries;

if ($redirectCondition === 'geolocation') {
    $Countries          = new WorldCountries();
    $worldcountries     = $Countries->getCountriesList();
    $selected_countires = count($geocountry);
    ?>
<!--BEGIN: geolocation conditions -->
<div id="geodiv" class="card mt-4">
    <div id="geocard-headerdev" class="card-header bg-light">
        <span class="mr-auto"><?= lang('Url.ByvisitorsGeolocation'); ?></span>
        <button id="geoconditioncancel" type="button" class="ms-4  mt-1 btn btn-outline-danger btn-sm ml-auto">
            <?= lang('Url.DeleteCondition'); ?>
        </button>
    </div>
    <div id="geocardbody" class="card-body">

        <?php
            for ($i = 0; $i < count($geocountry); $i++) {
                ?>

            <div>


                <!-- Create a row element -->
                <div class="row">
                    <!-- Create a div element for the select input -->
                    <div class="col-md-3 mt-2">
                        <select placeholder="Country" name="geocountry[]"
                                class="GeoLocationCountry select2_el form-control" required>
                            <option value="" disabled selected><?= lang('Url.SelectCountry'); ?></option>
                            <?php

                                foreach ($worldcountries as $worldcountryKey => $worldcountryVal) {
                                    if ($geocountry[$i] === $worldcountryKey) {
                                        echo "<option selected value='{$worldcountryKey}'>{$worldcountryVal}</option>";
                                    } else {
                                        echo "<option value='{$worldcountryKey}'>{$worldcountryVal}</option>";
                                    }
                                }

                ?>


                        </select>
                    </div>

                    <!-- Create a div element for the text input -->
                    <div class="col-md-8 mt-2">
                        <input placeholder="رابط التوجية حال تحقق الشرط" type="url" name="geofinalurl[]"
                               class="form-control" value="<?= $geofinalurl[$i]; ?>" required="">
                    </div>


                    <!-- Create a div element for the delete button -->
                    <div class="col-md-1 mt-2">
                        <button type="button" class="delgeoCountrybtn btn btn-sm btn-danger" title="حذف"><i
                                class="bi bi-trash"></i></button>
                    </div>
                </div>
            </div>

            <?php
            }
    ?>


        <button id="addNewCountryBtn" class="btn btn-dark mt-4" aria-expanded="false" type="button">+</button>


    </div>
    <script>
        /*set the redirectCondition*/
        var redirectCondition = document.getElementById("redirectCondition");
        redirectCondition.value = "geolocation";
    </script>

    <!--END: geolocation conditions -->
    <?php
} ?>

    <?php if ($redirectCondition === 'device') {
        ?>
        <!--BEGIN: device conditions -->
        <div id="devicediv" class="card mt-4">
            <div id="devicediv-headerdev" class="card-header bg-light">
                <span class="mr-auto"><?= lang('Url.ByvisitorsDevice'); ?></span>
                <button id="deviceconditioncancel" type="button"
                        class="ms-4  mt-1 btn btn-outline-danger btn-sm ml-auto">
                    <?= lang('Url.DeleteCondition'); ?>
                </button>
            </div>
            <div id="devicecardbody" class="card-body">
                <?php
                for ($i = 0; $i < count($device); $i++) {
                    ?>

                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <select placeholder="Device" name="device[]" class=" select2_el form-control" required>
                                <option value="" disabled selected><?= lang('Url.SelectDeviceFeat'); ?></option>
                                <option <?php if ($device[$i] === 'andriodsmartphone') {
                                    echo 'selected';
                                } ?> value="andriodsmartphone"><?= lang('Url.DeviceAndroidSmartPhone'); ?></option>
                                <option <?php if ($device[$i] === 'applesmartphone') {
                                    echo 'selected';
                                } ?> value="applesmartphone"><?= lang('Url.DeviceAppleSmartPhone'); ?></option>
                                <option <?php if ($device[$i] === 'windowscomputer') {
                                    echo 'selected';
                                } ?> value="windowscomputer"><?= lang('Url.DeviceWindowsComputer'); ?></option>
                            </select>

                        </div>

                        <!-- Create a div element for the text input -->
                        <div class="col-md-8 mt-2">
                            <input placeholder="{$langUrlDeviceFeatURL}" type="url" name="devicefinalurl[]"
                                   value="<?= $devicefinalurl[$i]; ?> " class="form-control" required/>
                        </div>

                        <!-- Create a div element for the devcondition  delete button -->
                        <div class="col-md-1 mt-2">
                            <button type="button" class="delDevicebtn btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="{$langCommonbtnDelete}"><i class="bi bi-trash"></i>
                            </button>
                        </div>

                    </div>

                    <?php
                }
        ?>

                <button id="addNewDeviceBtn" class="btn btn-dark mt-4" aria-expanded="false" type="button">+</button>
            </div>
        </div>


        <script>
            /*set the redirectCondition*/
            var redirectCondition = document.getElementById("redirectCondition");
            redirectCondition.value = "device";
        </script>
        <!--END: device conditions -->
        <?php
    } ?>

    <script>
<?php
        // set hidechoosUrlCondition if $redirectCondition != "" to hide  choosUrlCondition
        if ($redirectCondition !== '') {
            echo 'var hidechoosUrlCondition = true';
        }
?>
    </script>

