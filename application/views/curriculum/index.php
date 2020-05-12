<style type="text/css">
	.curr-list-container .list-head{
		border-bottom:1px solid #CCC;padding-bottom:5px;
	}
	.curr-list-container .list-head *{
		color:#00698C!important;
	}
	.curr-list-container .list-counter{
		background: #154360;
		padding:5px;
		border-radius: 5px;
	}
	.curr-list-container .list-counter *{
		color:#FFF!important;
	}
	.curr-list-container .list-content ul{
		list-style: none;
	}
	.curr-list-container .list-content ul.curr-list li{
		padding:2px;
		width:100%;
		margin-bottom:2px;
		cursor: pointer;
	}
	.curr-list-container .list-content ul.curr-list li:hover{
		background:#F3f3f3;
	}
	.curr-list-container .list-content ul.curr-list li a{
		text-decoration: none;
		color:#00698C;
	}
	.curr-list-container .list-content ul.curr-list li a span.lbl-sem{
		color:#00698C;
	}
	.curr-list-container .list-content ul.curr-list li a span.lbl-sy{
		color:#00698C;
	}
	table#table-list-curr tbody tr td{
		border:none!important;
	}

	/*CURRICULUM PREVIEW*/
	.preview-select-sem{
		background:#FFF;border-color:#FFF;color:#00698C;outline:none;
	}
	.preview-select-sy{
		background:#FFF;border-color:#FFF;color:#00698C;outline:none;
	}
	.preview-select-title{
		background:#FFF;border-color:#FFF;color:#00698C;outline:none;
	}
	.table-curr{
		font-size:11px;
	}
	.table-curr thead tr td{
		padding:3px;
		color:#00698C;
	}
	.table-curr tbody tr td{
		border:none;
		padding:3px;
		color:#00698C;
	}
	.table-curr tbody tr.tfooter td{
		border-top:1px solid #CCC;
		margin-top:10px;
		text-align:left!important;
	}
	.curr-container{
		max-height:650px;
		min-height:300px;
		overflow-y:auto;
	}
	.curr-preview-footer{
		margin-top:10px;
		padding-top:5px;
	}

	#table-list-curr_filter{
		display: none;
	}


	.tooltip {
	    position: relative;
	}
	.tooltip > div {
	    display: none;
	    position: absolute;
	    bottom: 100%;
	    left: 50%;
	    margin-left: -150px;
	    width: 300px;
	    
	}
	.tooltipContent {
	    background-color: #eee;
	    border: 1px solid #555;
	    border-radius: 5px;
	    padding: 5px;
	}

	/*.select2-search-field input{
		width:100px!important;
	}*/
/*	.select2-selection.select2-selection--multiple{
		width:200px;
	}

	.editable-container.editable-inline{
		width:300px!important;
	}*/
</style>
<?php $this->load->view('curriculum/modal/modal-set-curriculum'); ?>

<div id="content" class="content">

    <div class="row">
        <!-- LEFT SIDE -->
        <div class="col-lg-5">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <div class="row">
                        <div class="col-md-5">
                            <h4 class="panel-title">Curriculum List</h4>
                        </div>
                        <div class="col-md-7">
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                <input onkeyup="searchCurriculum($(this).val())" type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <section class="row">
                        <div class="col-sm-12">
                            <button onclick="$('#setCurriculumModal').modal('show')" class='btn btn-sm btn-primary'>Set new curriculum</button>
                        </div>
                    </section>
                    <table id="table-list-curr" class="table" style="margin-top:0px!important">
                        <thead class="hide">
                            <tr>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- RIGHT SIDE -->
        <div class="col-lg-7">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title">Curriculum Preview</h4>
                </div>
                <div class="panel-body" id="currPreviewContainer">
                    <p>Please select curriculum to view . . .</p>
                </div>
            </div>
        </div>
        
    </div>
</div>
<!-- MODAL -->