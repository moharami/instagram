<?php foreach ($result as $key => $field): ?>
    

<div class="row">
    <div class="col-md-12">
        <div class="grid simple ">
            <div class="grid-title no-border">
                <h4>
                    <?php echo $field[0]['Field']['alias'] ?>
                </h4>
                <div class="tools">
                    <a href="javascript:;" class="collapse"></a>
                    <a href="javascript:;" class="remove"></a>
                </div>
            </div>
            <div class="grid-body no-border">                
                <table class="table table-bordered no-more-tables">
                    <thead>
                        <tr>                            
                            <th class="text-center" style="width:12%">Hour</th>
                            <th class="text-center" style="width:22%">Minutes</th>
                            <th class="text-center" style="width:6%">Media</th>
                            <th class="text-center" style="width:6%">Follower</th>
                            <th class="text-center" style="width:6%">Follower_diff</th>
                            <th class="text-center" style="width:6%">Following</th>
                            <th class="text-center" style="width:6%">Following_diff</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($field as $key => $value): ?>
                        <tr>                            
                            <td class="text-center"><?php echo $value['Follower']['hour']; ?></td>
                            <td class="text-center"><?php echo $value['Follower']['minute']; ?></td>
                            <td class="text-center"><?php echo $value['Follower']['media']; ?></td>
                            <td class="text-center"><?php echo $value['Follower']['follower']; ?></td>
                            <td class="text-center"><?php echo $value['Follower']['follower_diff']; ?></td>
                            <td class="text-center"><?php echo $value['Follower']['following']; ?></td>
                            <td class="text-center"><?php echo $value['Follower']['following_diff']; ?></td>
                        </tr>                            
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php endforeach ?>