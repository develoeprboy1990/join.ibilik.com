<?php

$comp_model = new SharedController;

$page_element_id = "edit-page-" . random_str();

$current_page = $this->set_current_page_link();

$csrf_token = Csrf::$token;

$data = $this->view_data;

//$rec_id = $data['__tableprimarykey'];

$page_id = $this->route->page_id;

$show_header = $this->show_header;

$view_title = $this->view_title;

$redirect_to = $this->redirect_to;

?>

<section class="page" id="<?php echo $page_element_id; ?>" data-page-type="edit" data-display-type="" data-page-url="<?php print_link($current_page); ?>">

    <?php

    if ($show_header == true) {

    ?>

        <div class="bg-light p-3 mb-3">

            <div class="container">

                <div class="row ">

                    <div class="col ">

                        <h4 class="record-title">Edit Inquiry</h4>

                    </div>

                </div>

            </div>

        </div>

    <?php

    }

    ?>

    <div class="">

        <div class="container">

            <div class="row ">

                <div class="col-md-7 comp-grid">

                    <?php $this::display_page_errors(); ?>

                    <div class="bg-light p-3 animated fadeIn page-content">

                        <form novalidate id="" role="form" enctype="multipart/form-data" class="form page-form form-horizontal needs-validation" action="<?php print_link("inquiry/edit/$page_id/?csrf_token=$csrf_token"); ?>" method="post">

                            <div>

                                <div class="form-group ">

                                    <div class="row">

                                        <div class="col-sm-4">

                                            <label class="control-label" for="prospect_name">Your Name <span class="text-danger">*</span></label>

                                        </div>

                                        <div class="col-sm-8">

                                            <div class="">

                                                <input id="ctrl-prospect_name" value="<?php echo $data['prospect_name']; ?>" type="text" placeholder="Enter Prospect Name" required="" name="prospect_name" class="form-control " />

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="form-group ">

                                    <div class="row">

                                        <div class="col-sm-4">

                                            <label class="control-label" for="prospect_phone">Your Phone <span class="text-danger">*</span></label>

                                        </div>

                                        <div class="col-sm-8">

                                            <div class="">

                                                <input id="ctrl-prospect_phone" value="<?php echo $data['prospect_phone']; ?>" type="text" placeholder="Enter Prospect Phone" required="" name="prospect_phone" class="form-control " />

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <div class="form-group ">

                                    <div class="row">

                                        <div class="col-sm-4">

                                            <label class="control-label" for="select_location">Select Location </label>

                                        </div>

                                        <div class="col-sm-8">

                                            <div class="">

                                                <select id="ctrl-select_location" name="select_location" placeholder="Select a value ..." class="custom-select">

                                                    <option value="">Select a value ...</option>

                                                    <?php
                                                    $rec = $data['select_location'];
                                                    $select_location_options = $comp_model->inquiry_select_location_option_list();
                                                    if (!empty($select_location_options)) {
                                                        foreach ($select_location_options as $option) {
                                                            $value = (!empty($option['value']) ? $option['value'] : null);
                                                            $label = (!empty($option['label']) ? $option['label'] : $value);
                                                            $selected = ($value == $rec ? 'selected' : null); ?>
                                                            <option <?php echo $selected; ?> value="<?php echo $value; ?>"><?php echo $label; ?>
                                                            </option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label class="control-label" for="select_location">Budget rental</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class=""> <select required="" class="custom-select" aria-label="Default select example" name="budget_rental" id="budget_rental">
                                                    <option value=""> Budget rental </option> 
                                                    <option value="Below : RM500" <?php if($data['budget_rental'] == 'Below : RM500') { echo 'selected'; } ?>>Below : RM500</option>
                                                    <option value="Above : RM500" <?php if($data['budget_rental'] == 'Above : RM500') { echo 'selected'; } ?>>Above : RM500</option>
                                                    <option value="Above : RM800" <?php if($data['budget_rental'] == 'Above : RM800') { echo 'selected'; } ?>>Above : RM800</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              
                            </div>

                            <div class="form-ajax-status"></div>

                            <div class="form-group text-center">

                                <button class="btn btn-primary" type="submit">

                                    Update

                                    <i class="icon-paper-plane"></i>

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>