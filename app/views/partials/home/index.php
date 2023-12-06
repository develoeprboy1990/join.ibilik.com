<?php 

$page_id = null;

$comp_model = new SharedController;

$current_page = $this->set_current_page_link();

?>

<div>

    <div  class="bg-light p-3 mb-3">

        <div class="container">

            <div class="row ">

                <div class="col-md-12 comp-grid">

                    <h4 >The Dashboard</h4>

                </div>

            </div>

        </div>

    </div>

    <div  class="">

        <div class="container">

            <div class="row ">

                <div class="col-sm-3 comp-grid">

                    <?php $rec_count = $comp_model->getcount_totalinquiries();  ?>

                    <a class="animated zoomIn record-count alert alert-primary"  href="<?php print_link("inquiry/") ?>">

                        <div class="row">

                            <div class="col-2">

                                <i class="icon-list "></i>

                            </div>

                            <div class="col-10">

                                <div class="flex-column justify-content align-center">

                                    <div class="title">Total Inquiries</div>

                                    <small class=""></small>

                                </div>

                            </div>

                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>

                        </div>

                    </a>

                </div>

                <div class="col-md-3 comp-grid">

                    <?php $rec_count = $comp_model->getcount_handled();  ?>

                    <a class="animated zoomIn record-count card bg-success text-white"  href="<?php print_link("inquiry/?inquiry_handled=true&inquiry_handledlabel=true") ?>">

                        <div class="row">

                            <div class="col-2">

                                <i class="icon-check "></i>

                            </div>

                            <div class="col-10">

                                <div class="flex-column justify-content align-center">

                                    <div class="title">Handled</div>

                                    <small class=""></small>

                                </div>

                            </div>

                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>

                        </div>

                    </a>

                    <?php $rec_count = $comp_model->getcount_pending();  ?>

                    <a class="animated zoomIn record-count card bg-light text-dark"  href="<?php print_link("inquiry/?inquiry_handled=false&inquiry_handledlabel=false") ?>">

                        <div class="row">

                            <div class="col-2">

                                <i class="icon-globe"></i>

                            </div>

                            <div class="col-10">

                                <div class="flex-column justify-content align-center">

                                    <div class="title">Pending</div>

                                    <small class=""></small>

                                </div>

                            </div>

                            <h4 class="value"><strong><?php echo $rec_count; ?></strong></h4>

                        </div>

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

