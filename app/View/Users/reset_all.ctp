<div class="row">
    <div class="col-md-12">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4>
                    reset And 
                    <span class="semi-bold">Update</span>
                </h4>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="#grid-config" data-toggle="modal" class="config"></a>
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="grid-body no-border">
            <?php 
                echo $this->Html->link("Update All", array('controller'=>'Users', 'action'=>'update_all'),array('class'=>'btn btn-block btn-success','type'=>"button"));
             ?>

            </div>
        </div>
    </div>
</div>