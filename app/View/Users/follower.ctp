<div class="row">
    <?php foreach ($data as $key => $value): ?>
    <div class="col-md-6">
        <div class="grid simple">
            <div class="grid-title no-border">
                <h4><?php  echo $key; ?></h4>
            </div>
            <div class="grid-body no-border">
                <div class="row">
                    <div class="col-md-12">
                        <h2>follower : <?php echo $value['followed_by']; ?></h2>
                        <h2>following : <?php echo $value['follows']; ?></h2>
                        <h2>media : <?php echo $value['media']; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>
