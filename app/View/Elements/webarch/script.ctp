<?php 
    echo $this->Html->script('/webarch/plugins/jquery-1.8.3.min');
    echo $this->Html->script(array(
                                // BEGIN CORE JS FRAMEWORK
                                '/webarch/plugins/jquery-1.8.3.min',
                                '/webarch/plugins/jquery-ui/jquery-ui-1.10.1.custom.min',
                                '/webarch/plugins/bootstrap/js/bootstrap.min',
                                '/webarch/plugins/bootstrap/js/bootstrap.min',
                                '/webarch/plugins/breakpoints',
                                '/webarch/plugins/breakpoints',
                                '/webarch/plugins/jquery-unveil/jquery.unveil.min',
                                '/webarch/plugins/jquery-block-ui/jqueryblockui',
                                // END CORE JS FRAMEWORK
                                

                                // BEGIN PAGE LEVEL JS
                                '/webarch/plugins/jquery-slider/jquery.sidr.min',
                                '/webarch/plugins/jquery-slimscroll/jquery.slimscroll.min',
                                '/webarch/plugins/pace/pace.min',
                                '/webarch/plugins/jquery-numberAnimate/jquery.animateNumbers',
                                // END PAGE LEVEL PLUGINS

                                
                                // BEGIN CORE TEMPLATE JS
                                '/webarch/js/core',
                                '/webarch/js/chat',
                                '/webarch/js/demo',
                                // END CORE TEMPLATE JS
                                )
                            );
?>

