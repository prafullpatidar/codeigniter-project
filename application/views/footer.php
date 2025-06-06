<!-- START PAGE CONTENT -->
<section id="page-content" class="new-layout">
    <?php $this->load->view($content_view); ?>
    <!-- Start footer content -->
    <footer class="footer-content">
        <span id="tour-19">
            <!-- <span id="copyright-year"></span> &copy; Powered by <a href="http://www.franchisesoft.com/" target="_blank">IMS</a> -->
        </span>
    </footer><!-- /.footer-content -->
    <!--/ End footer content -->
</section><!-- /#page-content -->
<!--/ END PAGE CONTENT -->

</section>
<!-- END @WRAPPER -->

<!--/ START MODEL -->
<div class="modal fade modal-primary custom-modal2" id="modal-view-datatable" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="pop_heading"></h4>
            </div>
            <div class="modal-body" id="modal_content">
            </div>
            <!-- <div class="modal-footer">
                Footer Button Here
            </div> -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div class="modal fade modal-primary" id="view-table-modal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog add-shipping-company">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title pop_heading"></h4>
            </div>
            <div class="modal-body modal_content" >
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>