<?= $this->extend(smarty_view('layout')); ?>

<?= $this->section('title') ?>
<?= smarty_pagetitle(lang('Url.TagsCloud')); ?>
<?= $this->endSection() ?>

<?= $this->section('cssheaderarea') ?>
<style>
    /* Custom CSS for tag cloud */
    .tag {
        display: inline-block;
        padding: 8px 15px;
        /* Padding for inside the button */
        margin: 5px;


        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        min-width: 80px;
        /* Adjust the width as needed */

        line-height: 40px;
        /* Center the text vertically */
        text-align: center;
        /* Center the text horizontally */
        transition: background-color 0.3s ease;
        /* Smooth transition for hover effect */
    }

    .tag:hover {
        background-color: #0056b3;
        /* Darker shade on hover */
    }



    /* Add more styles as needed */
</style>
<?= $this->endSection() ?>


<?= $this->section('main') ?>
<div id="urltags" style="display: ">

    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">
                        <?= lang('Url.TagsCloud'); ?>
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="<?= site_url('dashboard'); ?>">
                                <?= lang('Common.dashboardLnk'); ?>
                            </a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="<?= site_url('url'); ?>">
                                <?= lang('Url.urlsLink'); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= lang('Url.TagsCloud'); ?>
                        </li>



                    </ol>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>

</div> <!-- end of urltags -->



<div class="app-content-header">
    <!--begin::Container-->
    <div id="urltagscontainer" class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!--BEGIN: urllist main card -->
                <div class="card">

                    <div class="card-header">

                        <div class="row">
                            <div class="col-6">
                                <h3 class="card-title">
                                    <?= lang('Url.TagsCloud'); ?>
                                </h3>
                            </div>
                            <div class="col-6 text-end">




                            </div>
                        </div>


                    </div> <!-- card-header -->

                    <div class="card-body" style="box-sizing: border-box; display: block;">



                        <div>




                            <div class="row">
                                <div class="col">




                                    <div class="tag-cloud">

                                        <?php foreach ($tags as $tag): ?>

                                            <a href="<?= site_url('url/tag/' . $tag['tag_id']); ?>"
                                                class="tag btn btn-sm btn-outline-dark mx-1" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= ($tag['creator_username'] !== '') ? lang('Url.TagCreator') . ' ' . $tag['creator_username'] : '' ?><?= "\n" . $tag['created_at']; ?>">
                                                <?= $tag['tag_name']; ?>
                                                <?php if ($tag['url_count'] > 0): ?>
                                                    <span class="badge bg-success">
                                                        <?= $tag['url_count']; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </a>


                                        <?php endforeach; ?>




                                        <!-- Add more tags as needed -->
                                    </div>
                                </div>
                            </div>








                        </div>

                    </div> <!-- card-body -->


                </div> <!-- card -->
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>