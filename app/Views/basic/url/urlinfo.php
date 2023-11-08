<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?><?= smarty_pagetitle(esc($url_identifier) . ' ' . lang('Url.urlInfoTitle')); ?>  <?= $this->endSection() ?>

<?= $this->section('cssheaderarea') ?>
<style>
    /*why yo but the redirection conditions table in center??
    .center-table {
        display: flex;
        justify-content: center;
     */
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('main') ?>

<style>

</style>

<div id="urlpage" style="display: ">

    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0"><?= esc($url_identifier); ?> </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a
                                href="<?= site_url('dashboard'); ?>">
                                <?= lang('Common.dashboardLnk'); ?>
                            </a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="<?= site_url('url'); ?>">
                                <?= lang('Url.urlsLink'); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= esc($url_identifier); ?>
                        </li>
                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>


    <div class="app-content-header">
        <!--begin::Container-->
        <div id="newurlcontainer" class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!--BEGIN: urllist main card -->
                    <div class="card">

                        <div class="card-header">

                            <div class="row">
                                <div class="col-6">
                                    <h3 class="card-title">
                                        <?= $go_url; ?>

                                        <i data-bs-toggle="tooltip" data-bs-placement="top"
                                           title='<?= lang('Url.CopyURL'); ?>' class='bi bi-clipboard copy-button'
                                           data-content='<?= $go_url; ?>' data-target='link2'></i>

                                    </h3>
                                </div>
                                <div class="col-6 text-end">

                                    <a data-bs-toggle="tooltip" data-bs-placement="top"
                                       title='<?= lang('Url.UpdateUrlSubmitbtn'); ?>'
                                       href='<?= site_url("url/edit/{$url_id}"); ?>' class='link-dark edit-link'><i
                                            class='bi bi-pencil edit-link-btn'></i></a>


                                </div>
                            </div>

                        </div>

                        <div class="card-body" style="box-sizing: border-box; display: block;">

                            <?php if (session('success') !== null) : ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert" id="Message"
                                     style="">
                                    <?= session('success') ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                </div>

                            <?php endif ?>


                            <!-- -->

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="child-div">

                                        <div class="mb-2">
                                            <?= lang('Url.UrlTitle'); ?>: <?= $url_title; ?>
                                        </div>


                                        <div class="mb-2">
                                            <?= lang('Url.OriginalUrl'); ?>: <?= $url_targeturl; ?>
                                            <a data-bs-toggle="tooltip" data-bs-placement="top" target='_blank'
                                               title='<?= lang('Url.visitOriginalUrl') . ' ' . $url_targeturl; ?>'
                                               href='<?= $url_targeturl; ?>' class='link-dark edit-link'><i
                                                    class='bi bi-box-arrow-up-right'></i></a>
                                        </div>


                                        <div class="mb-2">
                                            <?= lang('Url.UrlHitsNo'); ?>: <?= $url_hitscounter; ?>
                                        </div>


                                        <div class="mb-2">
                                            <?= lang('Url.UrlOwner'); ?>: <?= $url_owner_username; ?>
                                        </div>


                                        <div class="row col-8 mb-2">
                                            <div class="col-6">
                                                <?= lang('Url.UrlCreateDate'); ?>: <?= $created_at; ?>
                                            </div>
                                            <div class="col-6">
                                                <?= lang('Url.UrlUpdateDate'); ?>: <?= $updated_at; ?>
                                            </div>
                                        </div>

                                        <!-- -->

                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div style="" class="child-div child-div text-center d-flex flex-column align-items-center">
                                        <!-- Content for the second child div -->
                                        <img width=250 src="<?= site_url("url/qrcode/{$url_id}"); ?>" alt="Your SVG Image" class="pb-1">
                                        <a href="<?= site_url("url/qrcode/{$url_id}"); ?>?download=1" class="btn btn-sm btn-outline-dark">
                                            Download QR
                                            <i class="pt-1 bi bi-cloud-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>


                            <hr class="bg-dark border-2 border-top border-dark"/>

                            <div class="mb-2">
                                <div class="pb-2">
                                    <?= lang('Url.urlListTags'); ?>:
                                </div>
                                <?php
                                $url_tags_string = '';
if (count($url_tags) > 0) {
    foreach ($url_tags as $tag) {
        $tag_id   = $tag->tag_id;
        $tag_name = esc($tag->value);

        $url_tags_string .= "<a class='btn btn-sm btn-outline-dark mx-1' href='" . site_url('url/tag/' . $tag_id) . "'>{$tag_name}</a>";
    }
    $url_tags_string = "<div class='mt-1'>" . $url_tags_string . '</div>';
} else {
    $url_tags_string = "<div class='text-center'>" . lang('Url.urlListTagsNoTags') . '</div>';
}
echo $url_tags_string;
?>
                            </div>

                            <hr class="bg-dark border-2 border-top border-dark"/>

                            <?php
                            if ($condition !== null) { ?>

                                <div class="mb-2">
                                    <div class="pb-2">
                                        <?= lang('Url.urlInfoRecdirectCondition'); ?>: <?= $condition_text; ?>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                           title='<?= lang('Url.UpdateUrlConditionsTooltip'); ?>'
                                           href='<?= site_url("url/edit/{$url_id}"); ?>' class='link-dark edit-link'><i
                                                class='bi bi-pencil edit-link-btn'></i></a>
                                    </div>
                                    <div class="center-table table-responsive">
                                        <table class="table table-bordered" style="width: 100%;">
                                            <tbody>
                                            <?php
            foreach ($conditions as $cond => $link) {
                ?>

                                                <tr>
                                                    <td class="text-center"><?php
                        switch ($cond) {
                            case 'applesmartphone':
                                $cond_text = lang('Url.DeviceAppleSmartPhone');
                                break;

                            case 'andriodsmartphone':
                                $cond_text = lang('Url.DeviceAndroidSmartPhone');
                                break;

                            case 'windowscomputer':
                                $cond_text = lang('Url.DeviceWindowsComputer');
                                break;

                            default:
                                $cond_text = $cond;
                                break;
                        }

                ?>
                                                        <?= $cond_text; ?>
                                                    </td>
                                                    <td class="text-nowrap" dir="ltr">
                                                        <?= esc($link); ?>
                                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                                           target='_blank'
                                                           title='<?= lang('Url.visitOriginalUrl'); ?>'
                                                           href='<?= $link ?>'
                                                           class='link-dark edit-link'><i
                                                                class='bi bi-box-arrow-up-right'></i></a>
                                                    </td>
                                                </tr>
                                                <?php
            }
                                ?>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            <?php } else { ?>

                                <div class="mb-2">

                                    <div class="pb-2">
                                        <?= lang('Url.urlInfoRecdirectCondition'); ?>: <?= $condition_text; ?>
                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                           title='<?= lang('Url.UpdateUrlConditionsTooltip'); ?>'
                                           href='<?= site_url("url/edit/{$url_id}"); ?>' class='link-dark edit-link'><i
                                                class='bi bi-pencil edit-link-btn'></i></a>
                                    </div>

                                </div>

                            <?php } ?>

                            <hr class="bg-dark border-2 border-top border-dark"/>
                            <div class="mb-2">
                                <div class="pb-2">
                                    <?= lang('Url.urlInfoLast25Hits'); ?>:
                                    <a href="<?= site_url("url/hits/{$url_id}"); ?>"
                                       class="btn btn-sm btn-outline-dark"><?= lang('Url.urlInfoSeeAllHits'); ?></a>
                                </div>
                                <?php
                                if (isset($lasthits) && count($lasthits) > 0) {
                                    ?>

                                    <div class="container table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th><?= lang('Url.urlInfoVisitDate'); ?></th>
                                                <th><?= lang('Url.urlInfoVisitorIP'); ?></th>
                                                <th><?= lang('Url.urlInfoVisitorCountry'); ?></th>
                                                <th><?= lang('Url.urlInfoVisitorDevice'); ?></th>
                                                <th><?= lang('Url.urlInfoVisitorUserAgent'); ?></th>
                                                <th><?= lang('Url.urlInfoFinalTarget'); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            foreach ($lasthits as $hit) {
                                                ?>
                                                <tr>
                                                    <td><?= esc($hit->urlhit_at); ?></td>
                                                    <td><?= esc($hit->urlhit_ip); ?></td>
                                                    <td><?= esc($hit->urlhit_country); ?></td>
                                                    <td><?= esc($hit->urlhit_visitordevice); ?></td>
                                                    <td><?= esc($hit->urlhit_useragent); ?></td>
                                                    <td dir="ltr"><?= esc($hit->urlhit_finaltarget); ?>
                                                        <a data-bs-toggle="tooltip" data-bs-placement="top"
                                                           target='_blank'
                                                           title='<?= lang('Url.visitOriginalUrl'); ?>'
                                                           href='<?= $hit->urlhit_finaltarget; ?>'
                                                           class='link-dark edit-link'><i
                                                                class='bi bi-box-arrow-up-right'></i></a>

                                                    </td>
                                                </tr>
                                                <?php
                                            } // foreach ($lasthits as $hit)
                                    ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php
                                } else {
                                    // no hits
                                    ?>

                                    <div class="container">
                                        <table class="table table-borderless">
                                            <thead>
                                            <tr>
                                                <th class="text-center" style="font-weight: normal;">
                                                    <?= lang('Url.urlInfoNoHitsYet'); ?>
                                                </th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>


                                    <?php
                                } // else of   if (isset($lasthits) && count($lasthits) > 0) {
?>


                            </div>


                        </div> <!-- class="card-body" -->
                    </div> <!-- class="card" -->
                </div>
            </div>
        </div>


    </div> <!-- class app-content-header -->


</div>


<?= $this->endSection() ?> <!-- main -->


<?= $this->section('jsfooterarea') ?>
<script type="application/javascript">

    $(document).ready(function () {


        document.getElementById('urlpage').addEventListener('mouseover', function (event) {
            const copyButton = event.target;
            const editLinkBtn = event.target;

            if (copyButton.classList.contains('copy-button')) {

                copyButton.classList.add('bi-clipboard-fill');
                copyButton.classList.remove('bi-clipboard');
            }

            if (editLinkBtn.classList.contains('edit-link-btn')) {

                editLinkBtn.classList.add('bi-pencil-fill');
                editLinkBtn.classList.remove('bi-pencil');
            }


        });

        document.getElementById('urlpage').addEventListener('mouseout', function (event) {
            const copyButton = event.target;
            const editLinkBtn = event.target;

            if (copyButton.classList.contains('copy-button')) {

                copyButton.classList.remove('bi-clipboard-fill');
                copyButton.classList.add('bi-clipboard');
            }

            if (editLinkBtn.classList.contains('edit-link-btn')) {

                editLinkBtn.classList.add('bi-pencil');
                editLinkBtn.classList.remove('bi-pencil-fill');
            }

        });


        $("#urlpage").on("click", ".copy-button", function () {
            var content = $(this).data("content");


            var textArea = document.createElement("textarea");
            textArea.value = content;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("copy");
            document.body.removeChild(textArea);


            $(this).removeClass('bi-clipboard-fill');
            $(this).addClass('bi-clipboard-check-fill');

            Swal.fire({
                text: "<?= lang('Url.urlCopiedtoClipboard'); ?>",
                icon: "success",
                timer: 1000,
                timerProgressBar: true,
                position: "top-start",
                allowEscapeKey: true,
                showConfirmButton: false,
                toast: true,

            });


            setTimeout(function () {
                $(".copy-button").removeClass('bi-clipboard-check-fill');
            }, 500);
        });


    });


</script>
<?= $this->endSection() ?>
